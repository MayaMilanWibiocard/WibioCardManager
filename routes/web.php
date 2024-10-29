<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\TeamsController;
use App\Http\Controllers\Admin\CardsController;
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

    Route::get('/teams/index', [TeamsController::class, 'index'])->name('teams');
    Route::post('/teams/rename/{team_id}', [TeamsController::class, 'renameTeam'])->name('teams.rename');
    Route::post('/teams/rules/{team_id}', [TeamsController::class, 'createRules'])->name('teams.rules.new');
    Route::get('/teams/rules/delete/{team_id}/{role}', [TeamsController::class, 'deleteRules'])->name('teams.rules.del');
    Route::post('/teams/invite/{team_id}', [TeamsController::class, 'inviteMember'])->name('teams.invite');
    Route::post('/teams/groups/{team_id}', [TeamsController::class, 'createGroups'])->name('teams.groups.new');
    Route::get('/teams/groups/delete/{team_id}/{group}', [TeamsController::class, 'deleteGroups'])->name('teams.groups.del');
    Route::get('/teams/groups/ban/{team_id}/{group}/{user}', [TeamsController::class, 'banGroups'])->name('teams.groups.ban');
    Route::post('/teams/groups/attach/{team_id}/{group}', [TeamsController::class, 'attachGroups'])->name('teams.groups.attach');

    Route::get('/cards/index/{team_id}', [CardsController::class, 'index'])->name('cards');
    Route::get('/cards/token/lock/{id}', [CardsController::class, 'lockToken'])->name('token.lock');
    Route::get('/cards/token/attach/{team_id}/{token}', [CardsController::class, 'attachToken'])->name('cards.attach');
});

Route::group(['prefix' => 'admin', 'middleware' => ['role:admin,team_id']], function() {
    //Route::get('/{team_id}/users', ['middleware' => ['permission:views-users'], 'uses' => 'CommonController@commonUsers']);
    Route::get('/{team_id}/user/edit', ['middleware' => ['permission:edit-users,team_id'], 'uses' => [TeamsController::class, 'userEdit']]);
});

Route::get('/invitation/accept', function () {return redirect('/register'); })->name('invitations.accept');

require __DIR__.'/auth.php';
