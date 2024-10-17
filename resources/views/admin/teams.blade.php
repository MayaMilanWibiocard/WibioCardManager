<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teams') }}
        </h2>
    </x-slot>
    @foreach($teams as $team)
    <div class="my-4 mx-2 row">
        <div class="col-9">
            <div class="card border-success bg-light-success mb-3 h-100 w-100">
                <div class="card-title text-success my-auto mx-5">
                        <h3>TEAM : {{ $team->name }}</h3>
                        <button class="btn btn-success float-end ms-2" type="submit"><i class="bi bi-envelope-plus text-light"></i></button>
                </div>
            </div>
        </div>
        <div class="col-3">
            <h4>Owner: {{ $team->owner->name }}</h4>
            @if(Auth::user()->ownsTeam($team))
                <form class="mt-0" action="{{ route('teams.rename', $team->id) }}" method="POST">
                    <div class="mb-3">
                        @csrf
                        <label for="team_name" class="form-label">Change team name</label>
                        <div class="d-flex d-flex-inline">
                            <input type="team_name" class="form-control" name="team_name">
                            <button type="submit" class="btn btn-primary float-end ms-2"><i class="bi bi-small bi-floppy text-light"></i></button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
    <div class="my-4 mx-2 row">
        <h3 class="text-center text-success">Members & Rules</h3>
        @if(Auth::user()->ownsTeam($team) || Auth::user()->teamRole($team)->capabilities->where('code', 'securities.*')->count() >0)
            <div class="col-8">
                <div class="card border-success mb-3 h-100">
                    <div class="card-header bg-light-success fw-bold text-success"><i class="bi bi-tools"></i> Rules & Abilities</div>
                    <div class="card-body text-success overflow-auto" style="max-height: 300px;">
                        @foreach ($team->roles as $role)
                            <div class="row mb-3 border border-top">
                                <h4 class="card-text col-11 my-auto">{{ $role->name }}</h4>
                                <button class="btn btn-danger btn-sm float-end text-light col-1 ajax_request" data-action="{{@route('teams.rules.del', [$team->id, $role->name])}}"><i class="bi bi-small bi-trash text-light"></i></button>
                            </div>
                            <ul>
                                @foreach ($role->capabilities as $capability)
                                    <li class="my-1">
                                        <span class="my-auto">{{ $capability->code }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                    @if(Auth::user()->ownsTeam($team) || Auth::user()->teamRole($team)->capabilities->where('code', 'administrators.*')->count() >0)
                        <div class="card-footer bg-light-success">
                            <form class="mt-0" action="{{ route('teams.rules.new', $team->id) }}" method="POST">
                                <div class="mb-3">
                                    @csrf
                                    <label for="rule_name" class="form-label">Create rule</label>
                                    <div class="d-flex d-flex-inline">
                                        <input type="rule_name" class="form-control col" name="rule_name" placeholder="Rule name">
                                        <div class="dropdown col">
                                            <button class="btn dropdown-toggle w-100 text-start" id="dropdownBtn_capabilities"  type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Select capabilities
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="multiSelectDropdown" id="capabilities">
                                                @foreach ($capabilities as $capability)
                                                    <li>
                                                        <label><input type="checkbox"  value="{{ $capability->code }}"> {{ $capability->name }}</label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <input type="hidden" id="hidden_capabilities" name="capabilities" value="">
                                        </div>
                                        <button type="submit" class="btn btn-primary float-end ms-2"><i class="bi bi-small bi-plus text-light"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @endif
        <div class="col-4">
            <div class="card border-success mb-3 h-100">
                <div class="card-header bg-light-success fw-bold text-success"><i class="bi bi-person-lines-fill"></i> Members</div>
                <div class="card-body">
                    <ul>
                        @foreach ($team->allUsers() as $user)
                            <li>
                                <b class="text-uppercase">{{$user->teamRole($team)->name}}: </b> <span class="float-end">{{ $user->name }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @if(Auth::user()->ownsTeam($team) || Auth::user()->teamRole($team)->capabilities->where('code', 'employees.*')->count() >0)
                    <div class="card-footer bg-light-success">
                        <form class="mt-0" action="{{ route('teams.invite', [$team->id]) }}" method="POST">
                            <div class="mb-3">
                                <div class="d-flex d-flex-inline">
                                    @csrf
                                    <input type="email" class="form-control col" name="email" placeholder="email">
                                    <select name="role">
                                        @foreach ($team->roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary float-end ms-2"><i class="bi bi-small bi-envelope text-light"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="my-4 mx-2 row">
        <h3 class="text-center text-success">Groups</h3>
        @if(Auth::user()->ownsTeam($team) || Auth::user()->teamRole($team)->capabilities->where('code', 'employees.*')->count() >0)
            <div class="col-12 mt-2">
                <div class="card border-danger mb-3 h-100">
                    <div class="card-header bg-light-danger fw-bold text-danger"><i class="bi bi-people-fill text-danger"></i> Create new group</div>
                    <div class="card-body">
                        <form class="mt-0" action="{{ route('teams.groups.new', $team->id) }}" method="POST">
                            <div class="mb-3">
                                @csrf
                                <label for="group_name" class="form-label">Create group</label>
                                <input type="group_name" class="form-control col" name="group_name" placeholder="group name">
                                <div class="dropdown col">
                                    <button class="btn dropdown-toggle w-100 text-start" id="dropdownBtn_users" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Select members
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="multiSelectDropdown" id="users">
                                        @foreach ($team->allUsers() as $user)
                                            <li>
                                                <label><input type="checkbox"  value="{{ $user->id }}"> {{ $user->name }}</label>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <input type="hidden" id="hidden_users" name="users" value="">
                                </div>
                                <button type="submit" class="btn btn-primary float-end ms-2"><i class="bi bi-small bi-person-fill-add text-light"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
        @foreach($team->groups as $group)
            <div class="col-3 mt-2">
                <div class="card border-success mb-3 h-100">
                    <div class="card-header bg-light-success fw-bold text-success"><i class="bi bi-people-fill"></i> {{ $group->name }}</div>
                    <div class="card-body text-success">
                        <h5 class="card-title">Users</h5>
                        @foreach ($group->users as $user)
                            <div class="row mb-3 border border-top">
                                <span class="card-text ps-5 col-11 my-auto"><b>{{$user->teamRole($team)->name}}: </b> {{ $user->name }}</span>
                                @if(Auth::user()->ownsTeam($team) || Auth::user()->teamRole($team)->capabilities->where('code', 'employees.*')->count() >0)
                                    <button class="col-1 btn btn-warning float-end text-light" data-action="{{@route('teams.groups.ban', [$team->id, $group->id, $user->id])}}"><i class="bi bi-small bi-ban text-light"></i></button></div>
                                @endif
                         @endforeach
                    </div>
                    @if(Auth::user()->ownsTeam($team) || Auth::user()->teamRole($team)->capabilities->where('code', 'employees.*')->count() >0)
                        <div class="card-footer bg-light-success">
                            <form class="mt-0" action="{{ route('teams.groups.attach', [$team->id, $group->code]) }}" method="POST">
                                <div class="mb-3">
                                    <div class="d-flex d-flex-inline">
                                        @csrf
                                        <div class="dropdown col">
                                            <button class="btn dropdown-toggle w-100 text-start" id="dropdownBtn_users" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Attach users
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="multiSelectDropdown" id="users">
                                                @foreach ($team->allUsers() as $user)
                                                    @if (!$group->users->contains($user))
                                                        <li>
                                                            <label><input type="checkbox"  value="{{ $user->id }}"> {{ $user->name }}</label>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                            <input type="hidden" id="hidden_users" name="users" value="">
                                        </div>
                                        <button type="submit" class="btn btn-primary float-end ms-2"><i class="bi bi-small bi-person-fill-add text-light"></i></button>
                                    </div>
                                </div>
                            </form>
                            <button class="btn btn-danger float-end text-light" data-action="{{@route('teams.groups.del', [$team->id, $group->id])}}"><i class="bi bi-small bi-trash text-light"></i></button>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    @endforeach

</x-app-layout>
