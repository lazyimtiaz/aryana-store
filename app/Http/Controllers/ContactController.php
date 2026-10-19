<?php

namespace App\Http\Controllers;

use App\Models\StoreSetting;
use Illuminate\Contracts\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        $settings = StoreSetting::query()->first();

        return view('pages.contact', compact('settings'));
    }
}
