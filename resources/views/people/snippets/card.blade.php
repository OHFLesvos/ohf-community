<h4>@lang('app.cards')</h4>
@isset($person->card_no)
    <div class="table-responsive">
        <table class="table table-sm table-hover">
            <thead>
                <tr>
                    <th class="fit">@lang('app.date')</th>
                    <th>@lang('app.card_number')</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="fit">@isset($person->card_issued) {{ $person->card_issued }} <small>{{ $person->card_issued->diffForHumans() }}@endisset</small></td>
                    <td><strong>{{ substr($person->card_no, 0, 7) }}</strong>{{ substr($person->card_no, 7) }}</td>
                </tr>
                @foreach ($person->revokedCards as $card)
                    <tr>
                        <td class="fit"><span class="text-danger">@lang('app.revoked')</span> {{ $card->created_at }} <small>{{  $card->created_at->diffForHumans() }}</small></td>
                        <td><del><strong>{{ substr($card->card_no, 0, 7) }}</strong>{{ substr($card->card_no, 7) }}</del></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    @component('components.alert.info')
        @lang('app.no_cards_registered')
    @endcomponent
@endisset
