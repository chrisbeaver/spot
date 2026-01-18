<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfferRequest;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class CreateOfferToBuy extends Controller
{
    /**
     * Show the form to create a buy offer.
     */
    public function create(): Response
    {
        // Auto-login first user for testing
        if (!Auth::check()) {
            Auth::login(User::first());
        }

        return Inertia::render('Offers/CreateBuy');
    }

    /**
     * Store a new buy offer.
     */
    public function store(OfferRequest $request): RedirectResponse
    {
        // Auto-login first user for testing
        if (!Auth::check()) {
            Auth::login(User::first());
        }

        Offer::create([
            'user_id' => Auth::id(),
            'type' => 'buy',
            'metal' => $request->validated('metal'),
            'weight' => $request->validated('weight'),
            'weight_unit' => $request->validated('weight_unit'),
            'description' => $request->validated('description'),
        ]);

        return redirect()->route('offers.buy')->with('success', 'Buy offer created successfully.');
    }
}
