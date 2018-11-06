@extends('layouts.app')

@section('title', __('shop.barber_shop'))

@section('content')
    @if(count($list) > 0)
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="fit text-right">#</th>
                        <th>@lang('people.person')</th>
                        <th class="fit"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $person)
                        <tr>
                            <td class="fit text-right align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle">
                                @can('view', $person)
                                    <a href="{{ route('people.show', $person) }}" target="_blank">
                                @endcan
                                    @include('people.person-label', [ 'person' => $person ])
                                @can('view', $person)
                                    </a>
                                @endcan
                            </td>
                            <td class="fit align-middle">
                                <button type="button" class="btn btn-primary btn-sm">@icon(check)<span class="d-none d-sm-inline"> Check-in</span></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        @component('components.alert.warning')
            @lang('shop.no_persons_registered_today')
        @endcomponent
    @endif    
@endsection

@section('script')
    var csrfToken = '{{ csrf_token() }}';
@endsection
