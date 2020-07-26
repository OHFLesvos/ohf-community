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
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-4"><strong>@lang('accounting.beneficiary')</strong></div>
                <div class="col-sm">
                    {{ $transaction->beneficiary }}
                </div>
            </div>
        </li>
        @isset($transaction->wallet_owner)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>@lang('accounting.wallet_owner')</strong></div>
                    <div class="col-sm">
                        {{ $transaction->wallet_owner }}
                    </div>
                </div>
            </li>
        @endisset
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
        @unless(empty($transaction->receipt_pictures))
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>@lang('accounting.receipt')</strong></div>
                    <div class="col-sm">
                        @foreach($transaction->receipt_pictures as $picture)
                            @if(Storage::exists($picture))
                                @if(Str::startsWith(Storage::mimeType($picture), 'image/'))
                                    <a href="{{ Storage::url($picture) }}" data-lity>
                                        @component('components.thumbnail', ['size' => 150])
                                            {{ Storage::url($picture) }}
                                        @endcomponent
                                    </a>
                                @else
                                    <a href="{{ Storage::url($picture) }}" target="_blank">
                                        File: {{ Storage::mimeType($picture) }}</a>
                                    {{ bytes_to_human(Storage::size($picture)) }}
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            </li>
        @endunless

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
                                    data-url="{{ route('accounting.transactions.undoControlled', $transaction) }}"
                                >
                                    @lang('app.undo')
                                </button>
                            @endcan
                        @endif
                    @else
                        <button class="btn btn-primary btn-sm mark-controlled"
                            data-url="{{ route('accounting.transactions.markControlled', $transaction) }}"
                        >
                            @lang('accounting.mark_controlled')
                        </button>
                    @endif
                </div>
            </div>
        </li>

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
    </ul>
