@extends('layouts.app', ['wide_layout' => false])

@section('title', __('Badges'))

@section('content')
    {!! Form::open(['route' => [ 'badges.make' ], 'method' => 'post', 'files' => true]) !!}
        <div class="card shadow-sm mb-4">
            <div class="card-header">{{ __('Options') }}</div>
            <div class="card-body pb-2">
                <p>Select entries (<a href="javascript:;" id="select_all">all</a> / <a href="javascript:;" id="select_none">none</a>):</p>
                <div class="mb-3">
                    {{ Form::bsCheckboxList('persons[]', $persons, collect($persons)->keys()->toArray()) }}
                </div>
                <div class="mb-3 mt-4">
                    {{ Form::bsFile('alt_logo', [ 'accept' => 'image/*' ], __('Choose alternative logo'), 'Optional: Upload an alternative logo file.') }}
                </div>
            </div>
            <div class="card-footer text-right">
                <x-form.bs-submit-button :label="__('Create')"/>
            </div>
        </div>
    {!! Form::close() !!}
@endsection

@push('footer')
    <script>
        $(function () {
            $('#select_all').on('click', function () {
                $('input[type="checkbox"]').prop('checked', true);
            });
            $('#select_none').on('click', function () {
                $('input[type="checkbox"]').prop('checked', false);
            });
        });
    </script>
@endpush
