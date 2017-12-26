@extends('layouts.app')

@section('title', $project->name . ' Articles')

@section('content')

    <div id="enterAlert" style="display: none;">
        @component('components.alert.info')
            Press ENTER to save <u>all</u> your changes.
        @endcomponent
    </div>

    {!! Form::open(['route' => ['logistics.articles.store', $project]]) !!}
        {{ Form::hidden('date', $date) }}

        <ul class="nav nav-tabs tab-remember" id="articlesTabNav" role="tablist">
            @foreach($types as $type)
            <li class="nav-item">
                <a class="nav-link" id="{{ $type }}-tab" data-toggle="tab" href="#{{ $type }}" role="tab" aria-controls="{{ $type }}" aria-selected="true">{{ ucfirst($type) }}</a>
            </li>
            @endforeach
        </ul>
        <div class="tab-content" id="articlesTabContent">
            @foreach($types as $type)
            <div class="tab-pane fade" id="{{ $type }}" role="tabpanel" aria-labelledby="{{ $type }}-tab">
                @include('logistics.articles.table', [ 'type' => $type, 'articles' => $data[$type] ])
            </div>
            @endforeach
        </div>

    {!! Form::close() !!}

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