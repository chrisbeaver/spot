<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfferRequest;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class CreateOfferToSell extends Controller
{
    /**
     * Show the form to create a sell offer.
     */
    public function create(): Response
    {
        // Auto-login first user for testing
        if (!Auth::check()) {
            Auth::login(User::first());
        }

        return Inertia::render('Offers/CreateSell');
    }

    /**
     * Store a new sell offer.
     */
    public function store(OfferRequest $request): RedirectResponse
    {
        // Auto-login first user for testing
        if (!Auth::check()) {
            Auth::login(User::first());
        }

        Offer::create([
            'user_id' => Auth::id(),
            'type' => 'sell',
            'metal' => $request->validated('metal'),
            'weight' => $request->validated('weight'),
            'weight_unit' => $request->validated('weight_unit'),
            'description' => $request->validated('description'),
        ]);

        return redirect()->route('offers.sell')->with('success', 'Sell offer created successfully.');
    }
}
