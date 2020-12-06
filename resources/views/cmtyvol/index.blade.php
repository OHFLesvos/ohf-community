@extends('layouts.app')

@section('title', __('cmtyvol.community_volunteers'))

@section('content')

    <div class="row">

        {{-- Work status --}}
        <div class="col-md-auto text-nowrap" style="overflow-x: auto">
            <div class="btn-group btn-group-sm mb-3" role="group" aria-label="Work status">
                @foreach($work_statuses as $work_status)
                    <a href="{{ $work_status['url'] }}" class="btn @if($work_status['active']) btn-dark @else btn-secondary @endif">
                        {{ $work_status['label'] }}
                    </a>
                @endforeach
            </div>
        </div>


        {{-- Groupings --}}
        <div class="col-md-auto text-right text-nowrap" style="overflow-x: auto">
            <div class="btn-group btn-group-sm mb-3" role="group" aria-label="Groupings">
                @foreach($groupings as $grouping)
                    <a href="{{ $grouping['url'] }}" class="btn @if($grouping['active']) btn-dark @else btn-secondary @endif">{{ $grouping['label'] }}</a>
                @endforeach
                @if($groupings->where('active', true)->count() > 0)
                    <a href="{{ route('cmtyvol.index') }}?grouping=" class="btn btn-secondary"><x-icon icon="times"/></a>
                @endif
            </div>
        </div>

        {{-- Displays --}}
        <div class="col-md-auto">
            <div class="btn-group btn-group-sm mb-3" role="group" aria-label="Displays">
                @foreach($displays as $display)
                    <a href="{{ $display['url'] }}" class="btn @if($display['active']) btn-dark @else btn-secondary @endif" title="{{ $display['label'] }}"><x-icon :icon="$display['icon']"/></a>
                @endforeach
            </div>
        </div>
    </div>

    @if(isset($groups) && $data->filter(fn ($d) => count($d) > 0)->count() > 0)
        @if($selected_display == 'list')
            @component('cmtyvol.table', ['fields' => $fields])
                @foreach($groups as $group)
                    @if($data[$loop->index]->count() > 0)
                        <tr class="table-secondary">
                            <th colspan="{{ $fields->count() }}">
                                {{ $group ?? __('app.unspecified') }}
                                <small>({{ $data[$loop->index]->count() }})</small>
                            </th>
                        </tr>
                        @include('cmtyvol.tablebody', ['data' => $data[$loop->index]])
                    @endif
                @endforeach
            @endcomponent
        @elseif($selected_display == 'grid')
            @foreach($groups as $group)
                @if($data[$loop->index]->count() > 0)
                <h4 class="mb-3">{{ $group }} <small>({{ $data[$loop->index]->count() }})</small></h4>
                @include('cmtyvol.grid', ['data' => $data[$loop->index]])
                @endif
            @endforeach
        @endif
    @elseif(! isset($groups) && !$data->isEmpty())
        @if($selected_display == 'list')
            @component('cmtyvol.table', ['fields' => $fields])
                @include('cmtyvol.tablebody')
            @endcomponent
        @elseif($selected_display == 'grid')
            @include('cmtyvol.grid')
        @endif
        <p><small>@lang('app.n_results_found', [ 'num' => $data->count() ])</small></p>
    @else
        <x-alert type="info">
            @lang('cmtyvol.none_found')
        </x-alert>
    @endif

@endsection

@push('footer')
    <script>
        $(function () {
            // Make popovers work
            $('[data-toggle="popover"]').popover();
        });
    </script>
@endpush
