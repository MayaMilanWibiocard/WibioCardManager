<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cards') }}
        </h2>
    </x-slot>
    <div class="d-flex d-flex-inline w-75  mx-auto my-3">
    <input type="text" class="form-control mx-auto w-75" id="search" placeholder="Search for a card..">
    <button class="btn btn-primary mx-auto anchor_link">Search</button>
    </div>
    <table class="table table-striped small w-75 mx-auto">
        @foreach ($customer->CustomerCards as $card)
            <thead>
                <tr class="table-success">
                    <th scope="col"  id="section-{{ $card->card_uid }}"><img src="{{ Vite::asset('resources/images/type '. $card->card_type .'.png') }}" alt="Wibio smartcard type-F" width="100px" class=" float-start mx-auto my-auto"></th>
                    <th scope="col">{{ $card->card_uid }}<a {{ @route('token.lock', $card->id) }} class="btn btn-danger float-end"><i class="bi bi-small bi-ban text-light"></i></a></th>
                </tr>
            </thead>
            <tbody>

            @foreach ($card->HardwareTokens as $HardwareToken)
                <tr>
                    <th scope="col">Token</th>
                    <th scope="col">Type</th>
                </tr>
                <tr>
                    <td>{{ $HardwareToken->token }}</td>
                    <td>{{ $HardwareToken->otp_type }}-{{ $HardwareToken->tlen }}<br>{{ $HardwareToken->intend }}<br>{{ $HardwareToken->comment }}</td>
                </tr>
                <tr>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </td>
                <tr>
                    <td>
                        @if($HardwareToken->owner)
                            {{ $HardwareToken->owner }} - {{ $HardwareToken->email }}
                        @else
                            <a href="" class="btn btn-primary"><i class="bi bi-small bi-key-fill text-light"></i></a>
                        @endif
                    </td>
                    <td>
                        @if($HardwareToken->owner)
                            <a {{ @route('token.lock', $HardwareToken->id) }} class="btn btn-danger"><i class="bi bi-small bi-file-lock2-fill text-light"></i></a>
                        @else
                            No security actions available!
                        @endif
                    </td>
                </tr>
            @endforeach
                <tr class="card-footer">
                    <td colspan="5"><small class="text-muted">Reset count: {{ $card->reset_count }}</small></td>
                </tr>
            </tbody>
            </div>
        @endforeach
    </table>
</x-app-layout>

