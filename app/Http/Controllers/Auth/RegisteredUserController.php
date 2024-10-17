<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Jurager\Teams\Teams;
use \Jurager\Teams\Models\Team;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

use App\Models\Backend\DesktopSecretKeys;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'team' => ['required', 'string', 'max:255'],
            'scode' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $team = Team::where('name', $request->team)->first();
        if (!$team && empty($request->scode))
            return back()->withErrors(['team' => 'Invalid team name']);
        else if (!$team && !empty($request->scode))
        {
            $desktopsecretkeys = DesktopSecretKeys::where('oneShotSecretKey', $request->scode)
                            ->where('is_valid', 1)
                            ->where('software', 'WibioSmartManager_cloud')
                            ->whereNull('userEmail')
                            ->whereNull('macAddress')
                            ->first();
            if (!$desktopsecretkeys) {
                return back()->withErrors(['scode' => 'Invalid secret code']);
            }

            $desktopsecretkeys->userEmail = $request->email;
            $desktopsecretkeys->macAddress = 'cloud-registration';
            $desktopsecretkeys->save();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $team = Team::create([
                'name' => $request->team,
                'user_id' => $user->id,
            ]);

            $team->addRole('External user', ['card.view']);
            event(new Registered($user));

            Auth::login($user);

            return redirect(route('dashboard', absolute: false));
        }
        else
        {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $team->users()->attach($user, ['role_id' => $team->findRole('External user')->id]);
            event(new Registered($user));

            Auth::login($user);

            return redirect(route('dashboard', absolute: false));
        }
        return back()->withErrors(['team' => 'Sometime went wrong']);
    }
}
