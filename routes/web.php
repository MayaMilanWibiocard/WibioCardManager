<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\TeamsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::view('/authenticaC', 'cards/authentica-C')->name('authenticaC');
    Route::view('/authenticaF', 'cards/authentica-F')->name('authenticaF');

    Route::get('/teams/index', [TeamsController::class, 'index'])->name('teams-view');
});

Route::group(['prefix' => 'admin', 'middleware' => ['role:admin,team_id']], function() {
    //Route::get('/{team_id}/users', ['middleware' => ['permission:views-users'], 'uses' => 'CommonController@commonUsers']);
    Route::get('/{team_id}/user/edit', ['middleware' => ['permission:edit-users,team_id'], 'uses' => 'UserController@userEdit']);
});

require __DIR__.'/auth.php';
