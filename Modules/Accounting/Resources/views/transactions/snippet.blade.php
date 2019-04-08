    <ul class="list-group list-group-flush">
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
                <div class="col-sm-4"><strong>@lang('app.project')</strong></div>
                <div class="col-sm">
                    {{ $transaction->project }}
                </div>
            </div>
        </li>
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