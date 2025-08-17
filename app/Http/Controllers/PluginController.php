<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Nwidart\Modules\Facades\Module;
use ZipArchive;

class PluginController extends Controller
{
    public function index()
    {
        $modules = Module::all();
        return view('plugins.index', compact('modules'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'zip' => 'required|file|mimes:zip|max:102400', // 100MB
        ]);

        $zipPath = $request->file('zip')->store('tmp');
        $fullZip = storage_path('app/' . $zipPath);

        $zip = new ZipArchive;
        if ($zip->open($fullZip) !== true) {
            return back()->withErrors('Could not open zip.');
        }

        // Basic traversal protection
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if (str_contains($name, '../') || str_starts_with($name, '/')) {
                $zip->close();
                Storage::delete($zipPath);
                return back()->withErrors('Zip contains invalid paths.');
            }
        }

        $extractTo = base_path('Modules');
        if (!is_dir($extractTo)) mkdir($extractTo, 0775, true);

        // Remember existing module folders before extraction
        $before = collect(glob($extractTo . '/*', GLOB_ONLYDIR))
            ->map(fn($p) => basename($p))->all();

        $zip->extractTo($extractTo);
        $zip->close();
        Storage::delete($zipPath);

        $after = collect(glob($extractTo . '/*', GLOB_ONLYDIR))
            ->map(fn($p) => basename($p))->all();
        $created = array_values(array_diff($after, $before));

        // Optimize & discover
        Artisan::call('optimize:clear');
        Artisan::call('module:list');

        // Auto-run migrations if any
        foreach ($created as $name) {
            try {
                Artisan::call('module:migrate', ['module' => $name, '--force' => true]);
            } catch (\Throwable $e) {
                // ignore migration errors to not block upload UI
            }
        }

        return back()->with('status', 'Uploaded modules: ' . (empty($created) ? 'None detected' : implode(', ', $created)));
    }

    public function enable($module)
    {
        $m = Module::find($module);
        if (!$m) return back()->withErrors('Module not found');
        $m->enable();
        Artisan::call('optimize:clear');
        return back()->with('status', "$module enabled");
    }

    public function disable($module)
    {
        $m = Module::find($module);
        if (!$m) return back()->withErrors('Module not found');
        $m->disable();
        Artisan::call('optimize:clear');
        return back()->with('status', "$module disabled");
    }

    public function destroy($module)
    {
        $m = Module::find($module);
        if (!$m) return back()->withErrors('Module not found');
        if ($m->isEnabled()) return back()->withErrors('Disable the module before deleting.');
        $this->rrmdir($m->getPath());
        Artisan::call('optimize:clear');
        return back()->with('status', "$module deleted");
    }
    private function rrmdir($dir)
    {
        if (!is_dir($dir)) return;
        $items = array_diff(scandir($dir), ['.', '..']);
        foreach ($items as $item) {
            $path = "$dir/$item";
            is_dir($path) ? $this->rrmdir($path) : @unlink($path);
        }
        @rmdir($dir);
    }
}
