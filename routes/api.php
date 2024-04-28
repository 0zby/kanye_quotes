<?php

use App\Http\Controllers\QuotesController;
use Illuminate\Support\Facades\Route;

Route::get('/quotes/kanye', [QuotesController::class, 'getKanyeQuotes'])->name('quotes.kanye.get');
Route::patch('/quotes/kanye', [QuotesController::class, 'resetKanyeQuotes'])->name('quotes.kanye.reset');
