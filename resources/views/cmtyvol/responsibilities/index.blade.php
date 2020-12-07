@extends('layouts.app')

@section('title', __('responsibilities.responsibilities'))

@section('content')
    @if(count($responsibilities) > 0)
        <div class="table-responsive">
            <table class="table table-sm table-bordered table- table-hover">
                <thead>
                    <tr>
                        <th>@lang('app.name')</th>
                        <th class="text-right fit">@lang('app.capacity')</th>
                        <th class="text-center fit">@lang('app.available')</th>
                        <th>@lang('app.description')</th>
                        <th class="fit">@lang('app.assigned')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($responsibilities as $responsibility)
                        <tr class="@if($responsibility->isCapacityExhausted || $responsibility->hasAssignedAltoughNotAvailable) table-danger @elseif(!$responsibility->available) table-secondary @elseif($responsibility->capacity != null && $responsibility->capacity == $responsibility->countActive) table-success @elseif($responsibility->capacity != null && $responsibility->capacity > $responsibility->countActive) table-warning @endif">
                            <td class="fit">
                                <a href="{{ route('cmtyvol.responsibilities.edit', $responsibility) }}">{{ $responsibility->name }}</a>
                            </td>
                            <td class="text-right fit">
                                {{ $responsibility->countActive }} / {{ $responsibility->capacity ?? '∞'}}
                            </td>
                            <td class="text-center fit">
                                <x-icon-status :check="$responsibility->available"/>
                            </td>
                            <td>
                                <div class="text-formatted long-description collapse" id="description{{ $responsibility->id }}" data-toggle="collapse" data-target="#description{{ $responsibility->id }}" aria-expanded="false">{{ $responsibility->description }}</div>
                            </td>
                            <td class="fit">
                                @if($responsibility->communityVolunteers()->workStatus('active')->count() > 0)
                                    @foreach($responsibility->communityVolunteers()->workStatus('active')->get() as $cmtyvol)
                                        <a href="{{ route('cmtyvol.show', $cmtyvol) }}" target="_blank">{{ $cmtyvol->fullName }}</a><br>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <x-alert type="info">
            @lang('responsibilities.no_responsibilities_defined')
        </x-alert>
    @endif
@endsection
