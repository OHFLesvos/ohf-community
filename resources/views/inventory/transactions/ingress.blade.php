@extends('layouts.app')

@section('title', __('inventory.store_items'))

@section('content')
    {!! Form::open(['route' => ['inventory.transactions.storeIngress', $storage ]]) !!}
        <p>@lang('inventory.store_following_items_in_storage', ['storage' => $storage->name]):</p>
        <div id="item-container">
            <div class="form-row">
                <div class="col-4 col-sm-3 col-md-2 col-xl-1">
                    {{ Form::bsNumber('quantity[]', null, [ 'autofocus', 'required', 'min' => 1], __('inventory.quantity')) }}
                </div>
                <div class="col">
                    {{ Form::bsText('item[]', request()->item, [ 'required', 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($items)) ], __('inventory.item')) }}
                </div>
            </div>
        </div>
        <template id="new-item-template">
            <div class="form-row">
                <div class="col-4 col-sm-3 col-md-2 col-xl-1">
                    {{ Form::bsNumber('quantity[]', null, [ 'required', 'min' => 1], __('inventory.quantity')) }}
                </div>
                <div class="col">
                    {{ Form::bsText('item[]', null, [ 'required', 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($items)) ], __('inventory.item')) }}
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-secondary form-control-plaintext remove-new-item">@icon(minus-circle)</button>
                </div>
            </div>
        </template>
        <p><button type="button" class="btn btn-secondary add-new-item">@icon(plus-circle) @lang('inventory.add_another_item')</button></p>
        <div class="form-row">
            <div class="col-sm">
                {{ Form::bsText('origin', null, [ 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($origins)) ], __('inventory.origin')) }}
            </div>
            <div class="col-sm">
                {{ Form::bsText('sponsor', null, [ 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($payers)) ], __('inventory.sponsor')) }}
            </div>
        </div>
        <p>
            {{ Form::bsSubmitButton(__('app.register')) }}
        </p>
    {!! Form::close() !!}
@endsection

@section('script')
    var childIndex = 0;
    $(function(){
        // Add new item
        $('.add-new-item').on('click', function(){
            var content = $($('#new-item-template').html());
            content.find('label').remove();
            content.find('.remove-new-item').on('click', function(){
                $(this).parents('div[class="form-row"]').remove();
            });

            // Add row
            $('#item-container').append(content);

            // Focus
            content.find('input[name^="quantity"]').focus();
        });
    });
@endsection
