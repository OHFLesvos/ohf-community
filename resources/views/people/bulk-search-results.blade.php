@extends('layouts.app')

@section('title', __('app.bulk_search'))

@section('content')

    @if(count($persons) > 0)
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('app.name')</th>
                        <th>@lang('people.nationality')</th>
                        <th class="">@lang('people.date_of_birth')</th>
                        <th class=" text-right">@lang('people.age')</th>
                        <th class=" text-center">@lang('people.gender')</th>
                        <th>@lang('app.remarks')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($persons as $person)
                        <tr>
                            <td><a href="{{ route('people.show', $person) }}" target="_blank">{{ $person->fullName}}</a></td>
                            <td>{{ $person->nationality}}</td>
                            <td class="">{{ $person->date_of_birth}}</td>
                            <td class=" text-right">{{ $person->age}}</td>
                            <td class=" text-center">
                                <x-icon-gender :gender="$person->gender"/>
                            </td>
                            <td>{{ $person->remarks}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p><small>@lang('app.n_results_found', [ 'num' => $persons->count() ])</small></p>
    @else
        <x-alert type="info">
            @lang('app.not_found')
        </x-alert>
    @endif
    <p><a href="{{ route('people.bulkSearch') }}" class="btn btn-primary">@lang('app.new_query')</a></p>

@endsection

