<?php

use App\Http\Controllers\AdoptionQueueController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Animals\Index;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => redirect()->route('animals.index'))->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/adoption-queue', [AdoptionQueueController::class, 'index'])
        ->name('adoption.queue');

    Route::get('/animals', Index::class)->name('animals.index');

    // Update just the priority for an animal
    Route::patch('/animals/{animal}/priority', [AdoptionQueueController::class, 'updatePriority'])
        ->name('animals.priority.update');
});

Route::get('/hello-inertia', function () {
    return Inertia::render('Hello');
})->name('hello.inertia');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
