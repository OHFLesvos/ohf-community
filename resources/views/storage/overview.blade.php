@extends('layouts.app')

@section('title', __('storage.storage_management'))

@section('content')

    @if( ! $containers->isEmpty() )
        @foreach($containers as $container)
            <h2>{{ $container->name }}</h2>
            @isset($container->description)
                <p><em>{{ $container->description }}</em></p>
            @endif
            @if (! $container->transactions->isEmpty())
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="fit text-right">@lang('storage.quantity')</th>
                                <th>@lang('storage.item')</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($container->transactions()->groupBy('item')->select(DB::raw('SUM(amount) as sum'), 'item')->orderBy('item')->get() as $transaction)
                                <tr>
                                    <td class="fit text-right">{{ $transaction->sum }}</td>
                                    <td>{{ $transaction->item }}</td>
                                    <td class="fit"><a href="">@lang('storage.take_out')</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                @component('components.alert.info')
                    @lang('storage.no_items_registered')
                @endcomponent
            @endif
            <p><a href="" class="btn btn-primary">Add item(s)</a></p>
        @endforeach
    @else
        @component('components.alert.info')
            @lang('storage.no_containers_defined')
        @endcomponent
	@endif
	
@endsection
