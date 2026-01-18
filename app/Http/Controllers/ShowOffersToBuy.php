<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchOffersRequest;
use App\Models\Offer;
use App\Models\User;
use App\Services\SearchService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ShowOffersToBuy extends Controller
{
    public function __construct(
        protected SearchService $searchService
    ) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(SearchOffersRequest $request): Response
    {
        // Auto-login first user for testing
        if (!Auth::check()) {
            Auth::login(User::first());
        }

        $query = Offer::query()
            ->where('type', 'buy')
            ->whereHas('user')
            ->with('user');

        // Apply search filters
        $this->searchService->apply($query, $request->validated());

        // Default ordering if not specified
        if (empty($request->validated('orderby'))) {
            $query->latest();
        }

        $offers = $query->paginate(15)->withQueryString();

        return Inertia::render('Offers/Buy', [
            'offers' => $offers,
            'filters' => $this->searchService->getFilterValues($request->validated()),
        ]);
    }
}
