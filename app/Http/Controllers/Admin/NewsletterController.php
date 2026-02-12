<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::query();

        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subscribers = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        $totalActive = NewsletterSubscriber::where('status', 'active')->count();

        return view('admin.newsletter.index', compact('subscribers', 'totalActive'));
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();
        return back()->with('success', 'Inscrito removido com sucesso.');
    }
}
