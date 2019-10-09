<div class="card mb-4">
    <div class="card-header">Card</div>
    <div class="card-body">
        <strong>{{ substr($person->card_no, 0, 7) }}</strong>{{ substr($person->card_no, 7) }} issued on <strong>{{ $person->card_issued }}</strong>
        @if(count($person->revokedCards) > 0)
            <br><br><p>Revoked cards:</p>
            <table class="table table-sm table-hover m-0">
                <thead>
                    <tr>
                        <th style="width: 200px">Date</th>
                        <th>Code</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($person->revokedCards as $card)
                        <tr>
                            <td>{{ $card->created_at }}</td>
                            <td><strong>{{ substr($card->card_no, 0, 7) }}</strong>{{ substr($card->card_no, 7) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
