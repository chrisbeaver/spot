<?php

use App\Http\Controllers\CreateOfferToBuy;
use App\Http\Controllers\CreateOfferToSell;
use App\Http\Controllers\ShowOffersToBuy;
use App\Http\Controllers\ShowOffersToSell;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('offers/buy', ShowOffersToBuy::class)
    // ->middleware(['auth', 'verified'])
    ->name('offers.buy');

Route::get('offers/buy/create', [CreateOfferToBuy::class, 'create'])
    // ->middleware(['auth', 'verified'])
    ->name('offers.buy.create');

Route::post('offers/buy', [CreateOfferToBuy::class, 'store'])
    // ->middleware(['auth', 'verified'])
    ->name('offers.buy.store');

Route::get('offers/sell', ShowOffersToSell::class)
    // ->middleware(['auth', 'verified'])
    ->name('offers.sell');

Route::get('offers/sell/create', [CreateOfferToSell::class, 'create'])
    // ->middleware(['auth', 'verified'])
    ->name('offers.sell.create');

Route::post('offers/sell', [CreateOfferToSell::class, 'store'])
    // ->middleware(['auth', 'verified'])
    ->name('offers.sell.store');

require __DIR__.'/settings.php';
