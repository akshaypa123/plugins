<?php

namespace Modules\Form\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FormModule\Entities\Form;

class FormController extends Controller
{
    public function create() { return view('form::create'); }

  public function showForm()
    {
        return view('formmodule::form');
    }

    public function submitForm(Request $request)
    {
        $data = $request->validate([
            'name'    => 'nullable|string|max:255',
            'email'   => 'nullable|email|max:255',
            'message' => 'nullable|string',
        ]);

        Form::create($data);

        return redirect()->route('forms.index')->with('success', 'Form submitted successfully.');
    }

    public function index()
    {
        $rows = Form::orderBy('created_at', 'desc')->paginate(15);
        return view('formmodule::list', compact('rows'));
    }
}