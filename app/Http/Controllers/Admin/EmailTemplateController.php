<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::orderBy('name')->get();
        return view('admin.emails.index', compact('templates'));
    }

    public function edit(EmailTemplate $user_email_template)
    {
        // Renomeando para bater com a rota resource se necessário, ou apenas usando o objeto
        $template = $user_email_template;
        return view('admin.emails.edit', compact('template'));
    }

    public function update(Request $request, EmailTemplate $user_email_template)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $user_email_template->update([
            'subject' => $request->subject,
            'content' => $request->content,
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.emails.index')
            ->with('success', 'Modelo de e-mail atualizado com sucesso!');
    }
}
