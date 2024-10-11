<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TeamsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teams = $user->ownedTeams ?? [];
        return view('admin.teams', compact('teams'));
    }

    public function create()
    {
        return view('admin.teams.create');
    }
}
