        <table class="table table-sm table-bordered table-striped table-hover" id="articlesTable">
            <thead>
                <tr>
                    <th>Article</th>
                    <th>Unit</th>
                    <th style="width: 7em">Amount</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($articles as $article)
                <tr>
                    <td>
                        <a href="{{ route('logistics.articles.edit', $article) }}">{{ $article->name }}</a>
                    </td>
                    <td>
                        {{ $article->unit }}
                    </td>
                    <td>
                        <input type="number" name="value[{{$article->id}}]" class="form-control form-control-sm" placeholder="Amount" min="0" value="{{ $article->dayTransactions($date) }}">
                    </td>
                </tr>
            @endforeach
            <tr>
                <td>
                    <input type="text" name="new_name[{{ $type }}]" class="form-control form-control-sm" placeholder="New article">
                </td>
                <td>
                    <input type="text" name="new_unit[{{ $type }}]" class="form-control form-control-sm" placeholder="Unit" value="KG">
                </td>
                <td>
                    <input type="number" name="new_value[{{ $type }}]" class="form-control form-control-sm" placeholder="Amount" min="0">
                </td>
            </tr>
            </tbody>
        </table>
