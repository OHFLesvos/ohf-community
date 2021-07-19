    <ul class="list-group list-group-flush shadow-sm">

        {{-- Booked --}}
        @if($transaction->booked)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>{{ __('Booked') }}</strong></div>
                    <div class="col-sm">
                        @can('book-accounting-transactions-externally')
                            @isset($transaction->external_id)
                                @php
                                    $url = $transaction->externalUrl;
                                @endphp
                                Webling:
                                @isset($url)
                                    <a href="{{ $url }}" target="_blank">{{ $transaction->external_id }}</a>
                                @else
                                    {{ $transaction->external_id }}
                                @endisset
                            @else
                                {{ __('Yes') }}
                            @endif
                        @else
                            {{ __('Yes') }}
                        @endcan
                        @can('undoBooking', $transaction)
                            {!! Form::model($transaction, ['route' => ['accounting.transactions.undoBooking', $transaction], 'method' => 'put']) !!}
                                <p class="mb-0 mt-2">
                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('{{ __('Really undo booking?') }}')"
                                    >
                                        <x-icon icon="undo"/>
                                        {{ __('Undo booking') }}
                                    </button>
                                </p>
                            {!! Form::close() !!}
                        @endcan
                    </div>
                </div>
            </li>
        @endif
    </ul>

    {{-- Pictures --}}
    @unless(empty($transaction->receipt_pictures))
        <hr class="mt-0">
            <div class="form-row mx-3 mb-2">
                @foreach($transaction->receipt_pictures as $picture)
                    @if(Storage::exists($picture))
                    <div class="col-auto mb-2">
                        @if(Str::startsWith(Storage::mimeType($picture), 'image/'))
                            <a href="{{ Storage::url($picture) }}" data-fslightbox="gallery">
                                <x-thumbnail :size="config('accounting.thumbnail_size')">
                                    @if(Storage::exists(thumb_path($picture)))
                                        {{ Storage::url(thumb_path($picture)) }}
                                    @else
                                        {{ Storage::url($picture) }}
                                    @endif
                                </x-thumbnail>
                            </a>
                        @else
                            @if(Storage::exists(thumb_path($picture, 'jpeg')))
                                <a href="{{ Storage::url($picture) }}" target="_blank">
                                    <x-thumbnail :size="config('accounting.thumbnail_size')">
                                        {{ Storage::url(thumb_path($picture, 'jpeg')) }}
                                    </x-thumbnail>
                                </a>
                            @else
                                <a href="{{ Storage::url($picture) }}" target="_blank">
                                <span class="display-4" title="{{ Storage::mimeType($picture) }}">
                                    @if(Storage::mimeType($picture) == 'application/pdf')<x-icon icon="file-pdf"/>@else <x-icon icon="file"/>@endif</span></a> {{ bytes_to_human(Storage::size($picture)) }}
                            @endif
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    @endunless
