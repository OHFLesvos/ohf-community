    <ul class="list-group list-group-flush shadow-sm">
        @isset($transaction->receipt_no)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>{{ __('Receipt') }} #</strong></div>
                    <div class="col-sm">
                        {{ $transaction->receipt_no }}
                    </div>
                </div>
            </li>
        @endisset
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-4"><strong>{{ __('Date') }}</strong></div>
                <div class="col-sm">
                    {{ $transaction->date }}
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-4"><strong>{{ __('Amount') }}
                    @if($transaction->type == 'income') ({{ __('Income') }}) @endif
                    @if($transaction->type == 'spending') ({{ __('Spending') }}) @endif
                </strong></div>
                <div class="col-sm @if($transaction->type == 'income') text-success @elseif($transaction->type == 'spending') text-danger @endif">
                    {{ number_format($transaction->amount, 2) }}
                </div>
            </div>
        </li>
        @isset($transaction->fees)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>{{ __('Transaction fees') }}</strong></div>
                    <div class="col-sm text-danger">
                        {{ number_format($transaction->fees, 2) }}
                    </div>
                </div>
            </li>
        @endisset
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-4"><strong>{{ __('Category') }}</strong></div>
                <div class="col-sm">
                    {{ $transaction->category->getPathElements()->pluck('name')->join(' » ')  }}
                </div>
            </div>
        </li>
        @isset($transaction->secondary_category)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>{{ __('Secondary Category') }}</strong></div>
                    <div class="col-sm">
                        {{ $transaction->secondary_category }}
                    </div>
                </div>
            </li>
        @endisset
        @isset($transaction->project)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>{{ __('Project') }}</strong></div>
                    <div class="col-sm">
                        {{ $transaction->project->getPathElements()->pluck('name')->join(' » ')  }}
                    </div>
                </div>
            </li>
        @endisset
        @isset($transaction->location)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>{{ __('Location') }}</strong></div>
                    <div class="col-sm">
                        {{ $transaction->location }}
                    </div>
                </div>
            </li>
        @endisset
        @isset($transaction->cost_center)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>{{ __('Cost Center') }}</strong></div>
                    <div class="col-sm">
                        {{ $transaction->cost_center }}
                    </div>
                </div>
            </li>
        @endisset
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-4"><strong>{{ __('Description') }}</strong></div>
                <div class="col-sm">
                    {{ $transaction->description }}
                </div>
            </div>
        </li>
        @isset($transaction->supplier)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>{{ __('Supplier') }}</strong></div>
                    <div class="col-sm">
                        @can('view', $transaction->supplier)
                            <a href="{{ route('accounting.suppliers.show', $transaction->supplier) }}">
                                {{ $transaction->supplier->name }}
                            </a>
                        @else
                            {{ $transaction->supplier->name }}
                        @endcan
                        @isset($transaction->supplier->category)
                            <br><small>{{ $transaction->supplier->category }}</small>
                        @endisset
                    </div>
                </div>
            </li>
        @endisset
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-4"><strong>{{ __('Attendee') }}</strong></div>
                <div class="col-sm">
                    {{ $transaction->attendee }}
                </div>
            </div>
        </li>
        @isset($transaction->remarks)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>{{ __('Remarks') }}</strong></div>
                    <div class="col-sm">
                        {{ $transaction->remarks }}
                    </div>
                </div>
            </li>
        @endisset

        {{-- Registered --}}
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-4"><strong>{{ __('Registered') }}</strong></div>
                <div class="col-sm">
                    @php
                        $audit = $transaction->audits()->first();
                    @endphp
                    {{ $transaction->created_at }} @if(isset($audit) && isset($audit->getMetadata()['user_name']))({{ $audit->getMetadata()['user_name'] }})@endif
                </div>
            </div>
        </li>

        {{-- Controlled --}}
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-4"><strong>{{ __('Controlled') }}</strong></div>
                <div class="col-sm">
                    @if($transaction->controlled_at)
                        {{ $transaction->controlled_at }}
                        @isset($transaction->controlled_by)
                            ({{ $transaction->controller->name }})
                            @can('undoControlling', $transaction)
                                <button class="btn btn-secondary btn-sm undo-controlled"
                                    data-url="{{ route('api.accounting.transactions.undoControlled', $transaction) }}"
                                >
                                    {{ __('Undo') }}
                                </button>
                            @endcan
                        @endif
                    @else
                        <button class="btn btn-primary btn-sm mark-controlled"
                            data-url="{{ route('api.accounting.transactions.markControlled', $transaction) }}"
                        >
                            {{ __('Mark as controlled') }}
                        </button>
                    @endif
                </div>
            </div>
        </li>

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
