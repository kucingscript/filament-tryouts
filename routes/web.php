<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\TryoutOnline;

Route::middleware('auth')->group(function () {
    Route::get('/tryouts/{id}', TryoutOnline::class)->name('tryouts');
});

Route::get('/login', function () {
    return redirect('admin/login');
})->name('login');
