    <ul class="list-group list-group-flush">
        @isset($transaction->receipt_no)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>@lang('accounting::accounting.receipt') #</strong></div>
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
                    @if($transaction->type == 'income') (@lang('accounting::accounting.income')) @endif
                    @if($transaction->type == 'spending') (@lang('accounting::accounting.spending')) @endif
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
                <div class="col-sm-4"><strong>@lang('accounting::accounting.beneficiary')</strong></div>
                <div class="col-sm">
                    {{ $transaction->beneficiary }}
                </div>
            </div>
        </li>
        @isset($transaction->wallet_owner)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>@lang('accounting::accounting.wallet_owner')</strong></div>
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
        @if($transaction->booked)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4"><strong>@lang('accounting::accounting.booked')</strong></div>
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
                                <p class="mb-0 mt-2">{{ Form::button('<i class="fa fa-undo"></i> '.__('accounting::accounting.undo_booking'), [ 'type' => 'submit', 'class' => 'btn btn-sm btn-outline-danger', 'onclick' => "return confirm('".__('accounting::accounting.really_undo_booking')."')" ] ) }}</p>
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