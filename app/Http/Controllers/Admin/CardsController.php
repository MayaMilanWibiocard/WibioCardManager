<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use http;

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
            $customer = Customer::with('CustomerCards')->where('company', $team->name)->first();
            if ($customer->count() > 0) {
                return view('admin.cards', compact('team', 'customer'));
            }
            else
                return Redirect::back()->withError('Unable to retrive customer order for this team. Contact support to repair this issue');
        }
        return Redirect::back()->withError('Unauthorized action for this user');
    }

    public function lockToken($id)
    {
        $token = HardwareToken::find($id);
        if ($token->count() > 0) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://otpsandbox.wibiocard.com/admin/login');
            curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "username=linotp_adm&password=flPaPhaL");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_COOKIESESSION, true);
            curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie-name');  //could be empty, but cause problems on some hosts
            curl_setopt($ch, CURLOPT_COOKIEFILE, '/var/www/ip4.x/file/tmp');  //could be empty, but cause problems on some hosts
            $answer = curl_exec($ch);
            if (curl_error($ch)) {
                echo curl_error($ch);
            }

            curl_setopt($ch, CURLOPT_URL, 'https://otpsandbox.wibiocard.com/admin/unassign');
            curl_setopt($ch, CURLOPT_POST, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "serial=".$token->token."user=".$token->owner);
            $answer = curl_exec($ch);
            if (curl_error($ch)) {
                echo curl_error($ch);
            }
            return Redirect::back()->withSuccess('Token locked successfully');
        }
        return Redirect::back()->withError('Unable to lock token');
    }
}
