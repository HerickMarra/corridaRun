<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function show($slug)
    {
        $lp = \App\Models\LandingPage::where('slug', $slug)
            ->where('is_active', true)
            ->with('template')
            ->firstOrFail();

        $content = $lp->content;
        $templatePath = "landing_pages.templates.{$lp->template->identifier}";

        if (!view()->exists($templatePath)) {
            abort(404, "Template não encontrado.");
        }

        return view($templatePath, compact('lp', 'content'));
    }
}
