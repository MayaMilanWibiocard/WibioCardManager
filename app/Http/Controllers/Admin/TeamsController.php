<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Actions\Teams\InviteTeamMember;

use Jurager\Teams\Models\Team;
use Jurager\Teams\Models\Capability;

class TeamsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teams = new \Illuminate\Database\Eloquent\Collection;
        $teams = $teams ->concat($user->teams)->concat($user->ownedTeams);
        $capabilities = Capability::All();
        return view('admin.teams', compact('teams', 'capabilities'));
    }

    public function renameTeam($team_id, Request $request)
    {
        if (!$request->team_name) {
            return Redirect::back()->withError('Team name is required');
        }
        $team = Auth::user()->ownedTeams()->where('id', $team_id)->first();
        if ($team) {
            $team->name = $request->team_name;
            $team->save();
        }
        else
            return Redirect::back()->withError('You do not own this team');
        return Redirect::back()->withSuccess('Team name updated successfully');
    }

    public function createRules($team_id, Request $request)
    {
        if (!$request->rule_name) {
            return Redirect::back()->withError('Rule name is required');
        }
        $team = Team::where('id', $team_id)->first();
        if ($team && $this->checkCapability($team, Auth::user(), 'administrators.*'))
        {
            $team->addRole($request->rule_name, explode(",",$request->capabilities));
            return Redirect::back()->withSuccess('Rule created successfully');
        }
        return Redirect::back()->withError('Unauthorized action for this team');
    }

    public function deleteRules($team_id, $role)
    {
        $team = Team::where('id', $team_id)->first();
        if ($team && $this->checkCapability($team, Auth::user(), 'administrators.*'))
        {
            $team->findRole($role)->delete();
            return Redirect::back()->withSuccess('Rule deleted successfully');
        }
        return Redirect::back()->withError('Unauthorized action for this team');
    }

    public function createGroups($team_id, Request $request)
    {
        if (!$request->group_name) {
            return Redirect::back()->withError('Group name is required');
        }
        $team = Team::where('id', $team_id)->first();
        if ($team && $this->checkCapability($team, Auth::user(), 'employees.*'))
        {
            $groupCode = $request->group_name.date('dmy');
            $team->addGroup($groupCode, $request->group_name);
            foreach (explode(",", $request->users) as $user) {
                $team->group($groupCode)->attachUser(User::find($user));
            }
            return Redirect::back()->withSuccess('Group created successfully');
        }
        return Redirect::back()->withError('Unauthorized action for this team');
    }

    public function deleteGroups($team_id, $group)
    {
        $team = Team::where('id', $team_id)->first();
        if ($team && $this->checkCapability($team, Auth::user(), 'employees.*'))
        {
            $team->group($group)->delete();
            return Redirect::back()->withSuccess('Group deleted successfully');
        }
        return Redirect::back()->withError('Unauthorized action for this team');
    }

    public function banGroups($team_id, $group, $user)
    {
        $team = Team::where('id', $team_id)->first();
        if ($team && $this->checkCapability($team, Auth::user(), 'employees.*'))
        {
            $team->group($group)->detachUser(User::find($user));
            return Redirect::back()->withSuccess('User banned successfully');
        }
        return Redirect::back()->withError('Unauthorized action for this team');
    }

    public function attachGroups($team_id, $group, Request $request)
    {
        $team = Team::where('id', $team_id)->first();
        if ($team && $this->checkCapability($team, Auth::user(), 'employees.*'))
        {
            foreach($request->users as $user)
                $team->group($group)->attachUser($user);
            return Redirect::back()->withSuccess('User attached successfully');
        }
        return Redirect::back()->withError('Unauthorized action for this team');
    }

    public function inviteMember($team_id, Request $request)
    {
        $team = Team::where('id', $team_id)->first();
        if ($team && $this->checkCapability($team, Auth::user(), 'employees.*'))
        {
            $invite = new InviteTeamMember();
            $invite->invite(Auth::user(), $team, $request->email, $request->role);
            return Redirect::back()->withSuccess('User invitation sended');
        }
        return Redirect::back()->withError('Unauthorized action for this team');
    }



    protected function checkCapability($team, $user, $capability)
    {
        return (
            $user->ownedTeams()->where('id', $team->id)->count() == 1 ||
            (
                $user->belongsToTeam($team) &&
                $user->teamRole($team)->capabilities->where('code', $capability)->count() >0
            )
        );
    }

}
