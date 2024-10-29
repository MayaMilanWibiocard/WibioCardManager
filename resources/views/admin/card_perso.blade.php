@vite(['resources/js/webcard.js', 'resources/js/cardpersonalizer.js'])
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Personalize card') }}
        </h2>
    </x-slot>
    <div class="mx-auto mt-2 sm:px-6 lg:px-8">
        <div class="card">
            <div class="card-header text-white bg-success" id="h_mess"></div>
            <div class="card-body">
                <h5 class="card-title" id="t_mess"></h5>
                <p class="card-text" id="b_mess">{{ __("You're logged in!") }}</p>
            </div>
            <div class="card-footer text-success bg-light-success" id="f_mess"></div>
        </div>
    </div>
    <div id="spinner-wrapper" class="spinner-wrapper"><img class="mx-auto my-auto" src={{ Vite::asset('resources/images/rolling-slow.gif') }} alt="Wait..."></div>
    <div id="card_div" class="mt-2"></div>
    <div class="w-75 mx-auto">
        <form class="mt-2" action="{{ route('cards.attach', [$Team->id, $HardwareToken->token]) }}">
            @csrf
            <div class="mb-3">
                <label for="hardware" class="form-label">Card Info:</label>
                <span class="form-control">{{ $HardwareToken->card_id }}<br><small>Resetted {{$CustomerCard->reset_count}} times of {{$CustomerCard->crmProduct->product_reusability}} available</small></span>
            </div>
            <div class="mb-3">
                <label for="hardware" class="form-label">Token Info:</label>
                <span class="form-control">{{ $HardwareToken->token }}<br><small>Type {{ $HardwareToken->card_type }}-{{ $HardwareToken->otp_type }} {{ $HardwareToken->tlen }} digits</small></span>
            </div>
            <label for="user" class="form-label">Attach user</label>
            <select class="form-select" aria-label="user" id="user" name="user">
                <option selected>Attach user</option>
                @foreach ($Team->allUsers() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            <input type="hidden" id="token" name="token" value="{{ $HardwareToken->token }}">
            <button id="run_perso" class="btn btn-primary float-end m-2"><i class="bi bi-small bi-key-fill text-light"></i></button>
        </form>
    </div>
</x-app-layout>
