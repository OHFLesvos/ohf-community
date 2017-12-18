@extends('layouts.app')

@section('title', 'Kitchen')

@section('content')

    <ul class="nav nav-tabs tab-remember" id="kitchenArticlesTab" role="tablist">
        @foreach(['incomming' => 'Incomming', 'outgoing' => 'Outgoing'] as $k => $v)
        <li class="nav-item">
            <a class="nav-link" id="{{ $k }}-tab" data-toggle="tab" href="#{{ $k }}" role="tab" aria-controls="{{ $k }}" aria-selected="true">{{ $v }}</a>
        </li>
        @endforeach
    </ul>
    <div class="tab-content" id="myTabContent">
        @foreach(['incomming' => 'Incomming', 'outgoing' => 'Outgoing'] as $k => $v)
        <div class="tab-pane fade" id="{{ $k }}" role="tabpanel" aria-labelledby="{{ $k }}-tab">
            @include('kitchen.table', [ 'type' => $k, 'articles' => $data[$k] ])
        </div>
        @endforeach
    </div>

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