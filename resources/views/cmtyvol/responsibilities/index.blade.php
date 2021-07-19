@extends('layouts.app')

@section('title', __('Responsibilities'))

@section('content')
    @if(count($responsibilities) > 0)
        <div class="table-responsive">
            <table class="table table-sm table-bordered table- table-hover">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th class="text-center fit">{{ __('Available') }}</th>
                        <th class="text-right fit">{{ __('Capacity') }}</th>
                        <th class="fit">{{ __('Assigned') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($responsibilities as $responsibility)
                        @php
                            if (!$responsibility->available) {
                                $tr_class = 'text-muted table-secondary';
                            } else {
                                $tr_class = '';
                            }
                        @endphp
                        <tr class="{{ $tr_class }}">
                            <td class="fit">
                                <a href="{{ route('cmtyvol.responsibilities.edit', $responsibility) }}">{{ $responsibility->name }}</a>
                            </td>
                            <td>
                                <div class="text-formatted long-description collapse" id="description{{ $responsibility->id }}" data-toggle="collapse" data-target="#description{{ $responsibility->id }}" aria-expanded="false">{{ $responsibility->description }}</div>
                            </td>
                            <td class="text-center fit">
                                <x-icon-status :check="$responsibility->available"/>
                            </td>
                            @php
                                if ($responsibility->isCapacityExhausted) {
                                    $cls = 'text-danger';
                                } elseif ($responsibility->hasAssignedAltoughNotAvailable) {
                                    $cls = 'text-danger';
                                } elseif (!$responsibility->available) {
                                    $cls = '';
                                } elseif ($responsibility->capacity != null && $responsibility->countActive == $responsibility->capacity) {
                                    $cls = 'text-success';
                                } elseif ($responsibility->capacity != null && $responsibility->countActive < $responsibility->capacity) {
                                    $cls = 'text-warning';
                                } else {
                                    $cls = '';
                                }
                            @endphp
                            <td class="text-right fit {{ $cls }}">
                                @unless(empty($cls))
                                    <strong>{{ $responsibility->countActive }} / {{ $responsibility->capacity ?? '∞'}}</strong>
                                @else
                                    {{ $responsibility->countActive }} / {{ $responsibility->capacity ?? '∞'}}
                                @endunless
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
            {{ __('No responsibilities defined.') }}
        </x-alert>
    @endif
@endsection
