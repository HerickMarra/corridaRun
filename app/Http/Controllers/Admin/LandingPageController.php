<?php

namespace App\Http\Controllers\Admin;

use App\Models\LandingPage;
use App\Models\LandingPageTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class LandingPageController extends Controller
{
    public function index()
    {
        $pages = LandingPage::with('template')->latest()->paginate(10);
        return view('admin.landing_pages.index', compact('pages'));
    }

    public function create()
    {
        $templates = LandingPageTemplate::all();
        return view('admin.landing_pages.create', compact('templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:landing_pages,slug',
            'landing_page_template_id' => 'required|exists:landing_page_templates,id',
            'is_active' => 'boolean',
        ]);

        $template = LandingPageTemplate::findOrFail($request->landing_page_template_id);

        // Inicializa o conteúdo com base no schema do template usando valores padrão
        $content = [];
        foreach ($template->config_schema as $section => $fields) {
            foreach ($fields as $field) {
                if ($field['type'] === 'array') {
                    $content[$section][$field['key']] = $field['default'] ?? [];
                } else {
                    $content[$section][$field['key']] = $field['default'] ?? '';
                }
            }
        }

        $page = LandingPage::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'landing_page_template_id' => $validated['landing_page_template_id'],
            'content' => $content,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.landing-pages.edit', $page->id)
            ->with('success', 'Landing Page criada! Agora configure o conteúdo.');
    }

    public function edit(LandingPage $landingPage)
    {
        $landingPage->load('template');
        return view('admin.landing_pages.edit', compact('landingPage'));
    }

    public function update(Request $request, LandingPage $landingPage)
    {
        Log::info('LP Update Payload', [
            'all' => $request->all(),
            'files' => $request->file('files')
        ]);

        $content = $request->input('content', []);

        // Processar uploads de arquivos no array 'files' de forma robusta usando dot notation
        if ($request->hasFile('files')) {
            $uploadedFiles = Arr::dot($request->file('files'));
            Log::info('Flattened Files Detail', ['dot_keys' => array_keys($uploadedFiles)]);

            foreach ($uploadedFiles as $dotPath => $file) {
                Log::info("Processing dot path: $dotPath");
                if ($file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
                    $storedPath = $file->store('landing_pages/images', 'public');
                    Log::info("File Stored: $dotPath -> $storedPath");
                    Arr::set($content, $dotPath, '/storage/' . $storedPath);
                } else {
                    Log::warning("Invalid file or not an instance of UploadedFile at path: $dotPath");
                }
            }
        }

        Log::info('Final Content to Save', ['content' => $content]);

        $landingPage->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'content' => $content,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.landing-pages.index')
            ->with('success', 'Landing Page atualizada com sucesso!');
    }

    public function destroy(LandingPage $landingPage)
    {
        $landingPage->delete();
        return redirect()->route('admin.landing-pages.index')
            ->with('success', 'Landing Page excluída.');
    }
}
