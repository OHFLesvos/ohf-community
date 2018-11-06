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
                                <button type="button" 
                                    class="btn btn-primary btn-sm checkin-button" 
                                    data-person-id="{{ $person->id }}"
                                    data-person-name="{{ $person->fullName }}">
                                        @icon(check)<span class="d-none d-sm-inline"> Check-in</span>
                                </button>
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
    $(function(){
        $('.checkin-button').on('click', function(){
            var person_name = $(this).data('person-name');
            if (confirm('Please confirm check-in of "' + person_name + '".')) {
                var person_id = $(this).data('person-id');
                var sender_btn = $(this);
                sender_btn.children('i').removeClass('check').addClass('fa-spinner fa-spin');
                sender_btn.removeClass('btn-primary').addClass('btn-secondary');
                sender_btn.prop('disabled', true);
                setTimeout(function(){
                    sender_btn.parent().append('Checked-in');
                    sender_btn.remove();
                }, 1000);
            }
        })
    });  
@endsection
