    <ul class="list-group list-group-flush">
        @isset($transaction->receipt_no)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>@lang('accounting.receipt') #</strong></div>
                    <div class="col-sm">
                        {{ $transaction->receipt_no }}
                    </div>
                </div>
            </li>
        @endisset
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-4"><strong>@lang('app.date')</strong></div>
                <div class="col-sm">
                    {{ $transaction->date }}
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-4"><strong>@lang('app.amount')
                    @if($transaction->type == 'income') (@lang('accounting.income')) @endif
                    @if($transaction->type == 'spending') (@lang('accounting.spending')) @endif
                </strong></div>
                <div class="col-sm @if($transaction->type == 'income') text-success @elseif($transaction->type == 'spending') text-danger @endif">
                    {{ number_format($transaction->amount, 2) }}
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-4"><strong>@lang('app.category')</strong></div>
                <div class="col-sm">
                    {{ $transaction->category }}
                </div>
            </div>
        </li>
        @isset($transaction->secondary_category)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>@lang('app.secondary_category')</strong></div>
                    <div class="col-sm">
                        {{ $transaction->secondary_category }}
                    </div>
                </div>
            </li>
        @endisset
        @isset($transaction->project)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>@lang('app.project')</strong></div>
                    <div class="col-sm">
                        {{ $transaction->project }}
                    </div>
                </div>
            </li>
        @endisset
        @isset($transaction->location)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>@lang('app.location')</strong></div>
                    <div class="col-sm">
                        {{ $transaction->location }}
                    </div>
                </div>
            </li>
        @endisset
        @isset($transaction->cost_center)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>@lang('accounting.cost_center')</strong></div>
                    <div class="col-sm">
                        {{ $transaction->cost_center }}
                    </div>
                </div>
            </li>
        @endisset
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-4"><strong>@lang('app.description')</strong></div>
                <div class="col-sm">
                    {{ $transaction->description }}
                </div>
            </div>
        </li>
        @isset($transaction->supplier)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>@lang('accounting.supplier')</strong></div>
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
                <div class="col-sm-4"><strong>@lang('accounting.attendee')</strong></div>
                <div class="col-sm">
                    {{ $transaction->attendee }}
                </div>
            </div>
        </li>
        @isset($transaction->remarks)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>@lang('app.remarks')</strong></div>
                    <div class="col-sm">
                        {{ $transaction->remarks }}
                    </div>
                </div>
            </li>
        @endisset

        {{-- Registered --}}
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-4"><strong>@lang('app.registered')</strong></div>
                <div class="col-sm">
                    @php
                        $audit = $transaction->audits()->first();
                    @endphp
                    {{ $transaction->created_at }} @isset($audit)({{ $audit->getMetadata()['user_name'] }})@endisset
                </div>
            </div>
        </li>

        {{-- Controlled --}}
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-4"><strong>@lang('accounting.controlled')</strong></div>
                <div class="col-sm">
                    @if($transaction->controlled_at)
                        {{ $transaction->controlled_at }}
                        @isset($transaction->controlled_by)
                            ({{ $transaction->controller->name }})
                            @can('undoControlling', $transaction)
                                <button class="btn btn-secondary btn-sm undo-controlled"
                                    data-url="{{ route('api.accounting.transactions.undoControlled', $transaction) }}"
                                >
                                    @lang('app.undo')
                                </button>
                            @endcan
                        @endif
                    @else
                        <button class="btn btn-primary btn-sm mark-controlled"
                            data-url="{{ route('api.accounting.transactions.markControlled', $transaction) }}"
                        >
                            @lang('accounting.mark_controlled')
                        </button>
                    @endif
                </div>
            </div>
        </li>

        {{-- Booked --}}
        @if($transaction->booked)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>@lang('accounting.booked')</strong></div>
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
                                @lang('app.yes')
                            @endif
                        @else
                            @lang('app.yes')
                        @endcan
                        @can('undoBooking', $transaction)
                            {!! Form::model($transaction, ['route' => ['accounting.transactions.undoBooking', $transaction], 'method' => 'put']) !!}
                                <p class="mb-0 mt-2">{{ Form::button('<i class="fa fa-undo"></i> '.__('accounting.undo_booking'), [ 'type' => 'submit', 'class' => 'btn btn-sm btn-outline-danger', 'onclick' => "return confirm('".__('accounting.really_undo_booking')."')" ] ) }}</p>
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
                            <a href="{{ Storage::url($picture) }}" data-lity>
                                @component('components.thumbnail', ['size' => config('accounting.thumbnail_size')])
                                    @if(Storage::exists(thumb_path($picture)))
                                        {{ Storage::url(thumb_path($picture)) }}
                                    @else
                                        {{ Storage::url($picture) }}
                                    @endif
                                @endcomponent
                            </a>
                        @else
                            @if(Storage::exists(thumb_path($picture, 'jpeg')))
                                <a href="{{ Storage::url($picture) }}" target="_blank">
                                    @component('components.thumbnail', ['size' => config('accounting.thumbnail_size')])
                                        {{ Storage::url(thumb_path($picture, 'jpeg')) }}
                                    @endcomponent
                                </a>
                            @else
                                <a href="{{ Storage::url($picture) }}" target="_blank">
                                <span class="display-4" title="{{ Storage::mimeType($picture) }}">
                                    @if(Storage::mimeType($picture) == 'application/pdf')@icon(file-pdf)@else @icon(file)@endif</span></a> {{ bytes_to_human(Storage::size($picture)) }}
                            @endif
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    @endunless
