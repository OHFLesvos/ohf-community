<div class="row mb-0 pb-0">
    @allowed('do-bank-withdrawals')
        <div class="col text-center p-0">
            <a href="{{ route('bank.withdrawal') }}" class="btn btn-primary btn-block">
                @icon(id-card)<br><small>Withdrawal</small>
            </a>
        </div>
    @endallowed
    @allowed('do-bank-deposits')
        <div class="col text-center p-0">
            <a href="{{ route('bank.deposit') }}" class="btn btn-secondary btn-block">
                @icon(money)<br><small>Deposit</small>
            </a>
        </div>
    @endallowed
    @allowed('do-bank-withdrawals')
        <div class="col text-center p-0">
            <a href="{{ route('bank.prepareCodeCard') }}" class="btn btn-secondary btn-block">
                @icon(qrcode)<br><small>Code Card</small>
            </a>
        </div>
    @endallowed
</div>