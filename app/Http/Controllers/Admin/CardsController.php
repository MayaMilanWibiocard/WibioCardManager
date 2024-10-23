<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Models\User;
use App\Models\Sales\Customer;
use App\Models\Sales\CustomerCard;
use Jurager\Teams\Models\Team;
use App\Models\Backend\HardwareToken;

class CardsController extends Controller
{
    public function index($team_id)
    {
        $user = Auth::user();
        $team = Team::find($team_id);
        if(Auth::user()->ownsTeam($team) || Auth::user()->teamRole($team)->capabilities->where('code', 'cards.*')->count() >0)
        {
            $customer = Customer::where('company', $team->name)->get();
            if ($customer->count() > 0) {
                return view('admin.cards', compact('team', 'customer'));
            }
            else
                return Redirect::back()->withError('Unable to retrive customer order for this team. Contact support to repair this issue');
        }
        return Redirect::back()->withError('Unauthorized action for this user');
    }

}
