<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::orderBy('is_system', 'desc')->orderBy('name')->get();
        return view('admin.emails.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.emails.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $slug = \Illuminate\Support\Str::slug($request->name);

        // Garantir slug único
        $count = EmailTemplate::where('slug', 'like', $slug . '%')->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        EmailTemplate::create([
            'slug' => $slug,
            'name' => $request->name,
            'subject' => $request->subject,
            'content' => $request->content,
            'description' => $request->description,
            'is_active' => true,
            'is_system' => false,
        ]);

        return redirect()->route('admin.emails.index')
            ->with('success', 'Modelo de e-mail criado com sucesso!');
    }

    public function edit(EmailTemplate $user_email_template)
    {
        $template = $user_email_template;
        return view('admin.emails.edit', compact('template'));
    }

    public function update(Request $request, EmailTemplate $user_email_template)
    {
        $rules = [
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ];

        // Se não for sistema, permite editar o nome
        if (!$user_email_template->is_system) {
            $rules['name'] = 'required|string|max:255';
        }

        $request->validate($rules);

        $data = [
            'subject' => $request->subject,
            'content' => $request->content,
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ];

        if (!$user_email_template->is_system) {
            $data['name'] = $request->name;
        }

        $user_email_template->update($data);

        return redirect()->route('admin.emails.index')
            ->with('success', 'Modelo de e-mail atualizado com sucesso!');
    }

    public function destroy(EmailTemplate $user_email_template)
    {
        if ($user_email_template->is_system) {
            return redirect()->back()->with('error', 'Modelos de sistema não podem ser excluídos!');
        }

        $user_email_template->delete();

        return redirect()->route('admin.emails.index')
            ->with('success', 'Modelo de e-mail excluído com sucesso!');
    }
}
