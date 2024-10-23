
@vite(['resources/js/webcard.js', 'resources/js/cardmanager.js'])
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
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
</x-app-layout>
