    <ul class="list-group list-group-flush">
        @isset($transaction->receipt_no)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>@lang('accounting.receipt') #</strong></div>
                    <div class="col-sm">
                        @if(empty($transaction->receipt_no_correction))
                        {{ $transaction->receipt_no }}
                        @else
                        <strike>{{ $transaction->receipt_no }}</strike> &rarr;
                        <span>{{ $transaction->receipt_no_correction }}</span>
                        @endif
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
                    <div class="col-sm-4"><strong>@lang('app.cost_center')</strong></div>
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
                            <a href="{{ Storage::url($picture) }}" data-lity>
                                @component('components.thumbnail', ['size' => 150])
                                    {{ Storage::url($picture) }}
                                @endcomponent
                            </a>
                        @endforeach
                    </div>
                </div>
            </li>
        @endunless
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
