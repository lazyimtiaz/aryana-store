<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NewsletterSubscriptionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email:rfc', 'max:255'],
        ]);

        $email = strtolower(trim($validated['email']));

        $subscriber = NewsletterSubscriber::query()
            ->where('email', $email)
            ->first();

        if ($subscriber && $subscriber->status === 'subscribed') {
            return back()->with(
                'success',
                'You are already subscribed to The ARANYA Edit.'
            );
        }

        if (! $subscriber) {
            $subscriber = new NewsletterSubscriber();
            $subscriber->email = $email;
        }

        $subscriber->status = 'subscribed';
        $subscriber->source = 'footer';
        $subscriber->subscribed_at = now();
        $subscriber->unsubscribed_at = null;
        $subscriber->save();

        return back()->with(
            'success',
            'Thank you for subscribing to The ARANYA Edit!'
        );
    }
}
