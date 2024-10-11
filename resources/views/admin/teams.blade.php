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
                    <button class="btn btn-success float-end ms-2"><i class="bi bi-envelope-plus text-light"></i></button>
                </div>
            </div>
        </div>
        <div class="col-3">
            <h4>Owner: {{ $team->owner->name }}</h4>
            <form class="mt-0">
                <div class="mb-3">
                    <label for="team_name" class="form-label">Change team name</label>
                    <div class="d-flex d-flex-inline">
                        <input type="team_name" class="form-control" id="team_name">
                        <button type="submit" class="btn btn-primary float-end ms-2"><i class="bi bi-small bi-floppy text-light"></i></button>
                    </div>
                  </div>
            </form>
        </div>

    <div class="my-4 mx-2 row">
        @foreach($team->groups as $group)
            <div class="col-4 mt-2">
                <div class="card border-success mb-3">
                    <div class="card-header bg-light-success">Group: <b>{{ $group->name }}</b></div>
                    <div class="card-body text-success">
                        <h5 class="card-title">Users</h5>
                        @foreach ($group->users as $user)
                            <div class="row mb-3 border border-top"><span class="card-text col-11">{{ $user->name }}</span> <button class="col-1 btn btn-warning float-end text-light" id="delete_group_{{$group->id}}"><i class="bi bi-small bi-ban text-light"></i></button></div>
                            <div class="row mb-3 border border-top"><span class="card-text col-11">{{ $user->name }}</span> <button class="col-1 btn btn-warning float-end text-light" id="delete_group_{{$group->id}}"><i class="bi bi-small bi-ban text-light"></i></button></div>
                            <div class="row mb-3 border border-top"><span class="card-text col-11">{{ $user->name }}</span> <button class="col-1 btn btn-warning float-end text-light" id="delete_group_{{$group->id}}"><i class="bi bi-small bi-ban text-light"></i></button></div>
                        @endforeach
                    </div>
                    <div class="card-footer bg-light-success">
                        <form>
                            <button class="btn btn-danger float-end text-light mx-2" id="delete_group_{{$group->id}}"><i class="bi bi-small bi-trash text-light"></i></button>
                            <button class="btn btn-primary float-end text-light"><i class="bi bi-small bi-person-fill-add text-light"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endforeach

</x-app-layout>
