<?php

use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogController::class, 'index']);

Route::get('/product/{id}', [CatalogController::class, 'showProduct']);

Route::get('/categories', [CatalogController::class, 'categories']);

Route::get('/categories/{slug}', [CatalogController::class, 'showCategory']);

Route::post('/currency/{code}', [CatalogController::class, 'setCurrency'])->name('currency.set');

Route::get('/about', [CatalogController::class, 'about']);

Route::get('/sitemap.xml', [CatalogController::class, 'sitemap'])->name('sitemap');
