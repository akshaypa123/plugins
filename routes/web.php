<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PluginController;
use Modules\Form\Http\Controllers\FormController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::prefix('admin/plugins')->group(function () {
    Route::get('/', [PluginController::class, 'index'])->name('plugins.index');
    Route::post('/upload', [PluginController::class, 'upload'])->name('plugins.upload');
    Route::post('/{module}/enable', [PluginController::class, 'enable'])->name('plugins.enable');
    Route::post('/{module}/disable', [PluginController::class, 'disable'])->name('plugins.disable');
    Route::delete('/{module}', [PluginController::class, 'destroy'])->name('plugins.delete');
});




Route::group(['middleware' => ['web']], function () {
    Route::get('form', [FormController::class, 'showForm'])->name('form.show');
    Route::post('form', [FormController::class, 'submitForm'])->name('form.submit');
    Route::get('forms', [FormController::class, 'index'])->name('forms.index');
});