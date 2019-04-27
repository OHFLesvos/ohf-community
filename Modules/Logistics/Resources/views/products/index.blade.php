@extends('logistics::layouts.suppliers-products')

@section('title', __('logistics::products.products'))

@section('wrapped-content')

    @if( ! $products->isEmpty() )
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('app.name')</th>
                        <th>@lang('app.category')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>
                                <a href="{{ route('logistics.products.show', $product) }}">{{ $product->name }}</a>
                            </td>
                            <td>{{ $product->category }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $products->links() }}
    @else
        @component('components.alert.info')
            @lang('logistics::products.no_products_found')
        @endcomponent
	@endif
	
@endsection
