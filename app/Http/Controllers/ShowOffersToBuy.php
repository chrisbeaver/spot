<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ShowOffersToBuy extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        // Auto-login first user for testing
        if (!Auth::check()) {
            Auth::login(User::first());
        }

        $offers = Offer::query()
            ->where('type', 'buy')
            ->whereHas('user')
            ->with('user')
            ->latest()
            ->paginate(15);

        return Inertia::render('Offers/Buy', [
            'offers' => $offers,
        ]);
    }
}
