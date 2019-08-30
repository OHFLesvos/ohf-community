@extends('fundraising::layouts.donors-donations')

@section('title', __('fundraising::fundraising.donation_management'))

@section('wrapped-content')

    <div id="app">
        @php
            $fields = [
                'first_name' => [
                    'label' => __('app.first_name'),
                    'class' => 'd-none d-sm-table-cell',
                    'sortable' => true,
                ],
                'last_name' => [
                    'label' => __('app.last_name'),
                    'class' => 'd-none d-sm-table-cell',
                    'sortable' => true,
                ],
                'company' => [
                    'label' => __('app.company'),
                    'class' => 'd-none d-sm-table-cell',
                    'sortable' => true,
                ],
                'street' =>  [
                    'label' => __('app.street'),
                    'class' => 'd-none d-sm-table-cell',
                ],
                'zip' =>  [
                    'label' => __('app.zip'),
                    'class' => 'd-none d-sm-table-cell',
                ],
                'city' => [
                    'label' => __('app.city'),
                    'class' => 'd-none d-sm-table-cell',
                    'sortable' => true,
                ],
                'country' => [
                    'label' => __('app.country'),
                    'class' => 'd-none d-sm-table-cell',
                    'sortable' => true,
                ],
                'email' => [
                    'label' => __('app.email'),
                    'class' => 'd-none d-sm-table-cell',
                ],
                'phone' => [
                    'label' => __('app.phone'),
                    'class' => 'd-none d-sm-table-cell',
                ],
                'language' => [
                    'label' => __('app.correspondence_language'),
                    'class' => 'd-none d-sm-table-cell',
                    'sortable' => true,
                ],
            ];
            $items = $donors->map(function ($donor) {
                $donor['url'] = route('fundraising.donors.show', $donor);
                $donor['country'] = $donor->country_name;
                return $donor;
            });
        @endphp
        <test-table :items='@json($items)' :fields='@json($fields)'></test-table>
    </div>

    {!! Form::open(['route' => ['fundraising.donors.index'], 'method' => 'get']) !!}
        <div class="input-group mb-3">
            {{ Form::search('filter', isset($filter) ? $filter : null, [ 'class' => 'form-control focus-tail', 'autofocus', 'placeholder' => __('fundraising::fundraising.search_for_name_address_email_phone') . '...' ]) }}
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">@icon(search)</button> 
                @if(isset($filter))
                    <a class="btn btn-secondary" href="{{ route('fundraising.donors.index', ['reset_filter']) }}">@icon(eraser)</a> 
                @endif
            </div>
        </div>
    {!! Form::close() !!}

    @isset($tag)
        <p>
            @lang('app.tag'): 
            <span class="badge badge-primary">{{ $tag->name }} 
                <a href="{{ route('fundraising.donors.index', ['reset_tag']) }}" class="text-light">@icon(times)</a>
            </span>
        </p>
    @elseif(count($tags) > 0)
        <p>
            @lang('app.tags'): 
            @foreach($tags as $tag)
                <a href="{{ route('fundraising.donors.index', ['tag' => $tag]) }}">{{ $tag->name }}</a>@if(!$loop->last), @endif
            @endforeach
        </p>
    @endisset

    @if( ! $donors->isEmpty() )

        {{-- <div class="float-right"><small>@lang('app.total'): {{ $donors->total() }}</small></div>
        {{ $donors->links() }} --}}
    @else
        @component('components.alert.info')
            @lang('fundraising::fundraising.no_donors_found')
        @endcomponent
	@endif
	
@endsection
