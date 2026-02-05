<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    private $path = 'landing_pages/images';

    public function index(Request $request)
    {
        $files = Storage::disk('public')->files($this->path);

        // Formatar para exibição
        $media = collect($files)->map(function ($file) {
            return [
                'name' => basename($file),
                'url' => Storage::url($file),
                'path' => $file,
                'last_modified' => Storage::disk('public')->lastModified($file)
            ];
        })->sortByDesc('last_modified')->values();

        // Paginação manual simples
        $page = (int) $request->input('page', 1);
        $perPage = 20;
        $paginated = $media->forPage($page, $perPage);

        return response()->json([
            'data' => $paginated,
            'current_page' => $page,
            'last_page' => ceil($media->count() / $perPage),
            'total' => $media->count()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        $uploaded = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($this->path, $filename, 'public');
                $uploaded[] = [
                    'name' => $filename,
                    'url' => Storage::url($path)
                ];
            }
        }

        return response()->json(['success' => true, 'uploaded' => $uploaded]);
    }

    public function destroy(Request $request)
    {
        $path = $request->input('path');
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Arquivo não encontrado'], 404);
    }
}
