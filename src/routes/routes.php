<?php

use Explt13\Nosmi\Routing\Route;
use Surfsail\controllers\AuthController;
use Surfsail\controllers\CartController;
use Surfsail\controllers\CatalogController;
use Surfsail\controllers\CurrencyController;
use Surfsail\controllers\FavoriteController;
use Surfsail\controllers\MainController;
use Surfsail\controllers\ProductController;
use Surfsail\controllers\SearchController;
use Surfsail\controllers\UserController;


Route::get('/', MainController::class, 'index');
Route::get('/auth', AuthController::class, 'index');
Route::get('/cart', CartController::class, 'index');
Route::get('/favorite', FavoriteController::class, 'index');
Route::get('/catalog', CatalogController::class, 'index');
Route::get('/product/<slug>:alias', ProductController::class, 'index');
Route::get('/cart/buy', CartController::class, 'buy');

Route::get('/api/search', SearchController::class);

Route::post('/api/currency', CurrencyController::class);
Route::get('/api/currency', CurrencyController::class);

Route::get('/api/cart/items', CartController::class);
Route::delete('/api/cart/items/<int>:product_id', CartController::class);
Route::post('/api/cart/items', CartController::class);
Route::patch('/api/cart/items/<int>:product_id', CartController::class);

Route::get('/api/favorite/<string>:entity/items', FavoriteController::class);
Route::post('/api/favorite/<string>:entity/items', FavoriteController::class);
Route::delete('/api/favorite/<string>:entity/items/<int>:item_id', FavoriteController::class);

Route::get('/api/catalog', CatalogController::class, 'index');

Route::post('/api/user/login', UserController::class, 'login');
Route::post('/api/user/register', UserController::class, 'register');

Route::get('/user/logout', UserController::class, 'logout');