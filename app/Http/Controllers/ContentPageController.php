<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\StorePage;
use Illuminate\Contracts\View\View;

class ContentPageController extends Controller
{
    public function faq(): View
    {
        $faqs = Faq::published()->get();

        return view('pages.faq', compact('faqs'));
    }

    public function show(string $slug): View
    {
        $page = StorePage::published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('pages.show', compact('page'));
    }
}
