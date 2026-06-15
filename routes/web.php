<?php

use App\Http\Controllers\Artisan\DashboardController as ArtisanDashboardController;
use App\Http\Controllers\Artisan\FormationController as ArtisanFormationController;
use App\Http\Controllers\Artisan\ProductController as ArtisanProductController;
use App\Http\Controllers\ArtisanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/catalogue', [CatalogueController::class, 'index'])->name('catalogue.index');
Route::get('/catalogue/{product:slug}', [CatalogueController::class, 'show'])->name('catalogue.show');

Route::get('/artisans', [ArtisanController::class, 'index'])->name('artisans.index');
Route::get('/artisans/{artisan}', [ArtisanController::class, 'show'])->name('artisans.show');

Route::get('/formations', [FormationController::class, 'index'])->name('formations.index');
Route::get('/formations/{formation}', [FormationController::class, 'show'])->name('formations.show');

Route::get('/panier', [CartController::class, 'show'])->name('cart.show');
Route::post('/panier/ajouter', [CartController::class, 'add'])->name('cart.add');
Route::post('/panier/supprimer', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/panier/mettre-a-jour', [CartController::class, 'update'])->name('cart.update');

Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'showForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::post('/formations/{formation}/enroll', [FormationController::class, 'enroll'])->name('formations.enroll');

    Route::get('/commande', [CommandeController::class, 'checkout'])->name('commandes.checkout');
    Route::post('/commande', [CommandeController::class, 'store'])->name('commandes.store');
    Route::get('/commande/{commande}/confirmation', [CommandeController::class, 'confirmation'])->name('commandes.confirmation');
    Route::get('/mes-commandes', [CommandeController::class, 'index'])->name('commandes.index');
    Route::get('/mes-commandes/{commande}', [CommandeController::class, 'show'])->name('commandes.show');

    Route::get('/profil', [ProfilController::class, 'show'])->name('profil.show');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
});

Route::middleware(['auth', 'artisan'])->prefix('artisan')->name('artisan.')->group(function () {
    Route::get('/dashboard', [ArtisanDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', ArtisanProductController::class);
    Route::get('/formations', [ArtisanFormationController::class, 'index'])->name('formations.index');
});
