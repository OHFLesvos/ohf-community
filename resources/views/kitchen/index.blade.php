@extends('layouts.app')

@section('title', 'Kitchen')

@section('content')

    {!! Form::open(['route' => ['kitchen.storeIncomming']]) !!}
        {{ Form::hidden('date', $date) }}
        <table class="table table-sm table-bordered table-striped table-hover" id="articlesTable">
            <thead>
                <tr>
                    <th>Article</th>
                    @foreach(range(6, 0) as $i)
                        @if ($date->toDateString() != Carbon\Carbon::today()->subDays($i)->toDateString())
                            <th style="width: 7em" class="d-none d-sm-table-cell">
                                <a href="{{ route('kitchen.index') }}?date={{  Carbon\Carbon::today()->subDays($i)->toDateString() }}">{{ Carbon\Carbon::today()->subDays($i)->toDateString() }}</a>
                            </th>
                        @else
                            <th style="width: 7em">
                                {{ Carbon\Carbon::today()->subDays($i)->toDateString() }}
                            </th>
                        @endif
                    @endforeach
                </tr>
            </thead>
            <tbody>
            @foreach ($articles as $article)
                <tr>
                    <td>{{ $article->name }}</td>
                    @foreach(range(6, 0) as $i)
                        @if ($date->toDateString() != Carbon\Carbon::today()->subDays($i)->toDateString())
                            <td class="d-none d-sm-table-cell">
                                {{ $article->dayTransactions(Carbon\Carbon::today()->subDays($i)) }}
                            </td>
                        @else
                            <td>
                                <input type="number" name="value[{{$article->id}}]" class="form-control form-control-sm" placeholder="Amount" min="0" value="{{ $article->dayTransactions($date) }}">
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
            <tr>
                <td>
                    <input type="text" name="new_name" class="form-control form-control-sm" placeholder="New article name">
                </td>
                @foreach(range(6, 0) as $i)
                    @if ($date->toDateString() != Carbon\Carbon::today()->subDays($i)->toDateString())
                        <td class="d-none d-sm-table-cell"></td>
                    @else
                        <td>
                            <input type="number" name="new_value" class="form-control form-control-sm" placeholder="Amount" min="0">
                        </td>
                    @endif
                @endforeach
            </tr>
            </tbody>
        </table>
    {!! Form::close() !!}

    <div id="enterAlert" style="display: none;">
        @component('components.alert.info')
            Press ENTER to save your changes.
        @endcomponent
    </div>

@endsection

@section('script')
    function getInputsHashCode(selector) {
        return $(selector)
                .map(function() { return this.value; })
                .get()
                .join(',');
    }
    var inputsHash;

    $(function(){
        $('#articlesTable input').on('keydown', function(evt){
            var isEnter = false;
            if ("key" in evt) {
                isEnter = (evt.key == "Enter");
            } else {
                isEnter = (evt.keyCode == 13);
            }
            if (isEnter) {
                $(this).parents('form').submit();
            }
        });

        // Show message if input values have been changed
        inputsHash = getInputsHashCode('#articlesTable input');
        $('#articlesTable input').on('change paste propertychange input', function(evt){
            if (inputsHash != getInputsHashCode('#articlesTable input')) {
                $('#enterAlert').fadeIn('fast');
            } else {
                $('#enterAlert').fadeOut('fast');
            }
        });
    });
@endsection