@extends('layouts.app')

@section('title', __('app.report') . ': '. $projectName)

@section('content')
    <div id="app" class="mb-3">
        <ul class="nav nav-tabs tab-remember" id="articlesTabNav" role="tablist">
            @foreach($types as $type)
            <li class="nav-item">
                <a class="nav-link" id="{{ $type }}-tab" data-toggle="tab" href="#{{ $type }}" role="tab" aria-controls="{{ $type }}" aria-selected="true">{{ ucfirst($type) }}</a>
            </li>
            @endforeach
        </ul>
        <div class="tab-content pt-4" id="articlesTabContent">
            @foreach($types as $type)
                <div class="tab-pane fade" id="{{ $type }}" role="tabpanel" aria-labelledby="{{ $type }}-tab">
                    @if( ! $data[$type]->isEmpty() )
                        <div class="table-responsive table-striped table-sm">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Article</th>
                                        <th class="text-right">Today</th>
                                        <th class="text-right">Yesterday</th>
                                        <th class="text-right">This week</th>
                                        <th class="text-right">Last week</th>
                                        <th class="text-right">This month</th>
                                        <th class="text-right">Last month</th>
                                        <th class="text-right">Daily average</th>
                                        <th class="text-right">Peak</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data[$type] as $item)
                                        <tr>
                                            <td><a href="{{ route('reporting.article', $item['article']) }}">{{ $item['article']->name }}</a></td>
                                            <td class="text-right">{{ $item['today'] }} @if(isset($item['today']))<small class="text-muted">{{ $item['article']->unit }}</small>@endif</td>
                                            <td class="text-right">{{ $item['yesterday'] }} @if(isset($item['yesterday']))<small class="text-muted">{{ $item['article']->unit }}</small>@endif</td>
                                            <td class="text-right">{{ $item['this_week'] }} @if(isset($item['this_week']))<small class="text-muted">{{ $item['article']->unit }}</small>@endif</td>
                                            <td class="text-right">{{ $item['last_week'] }} @if(isset($item['last_week']))<small class="text-muted">{{ $item['article']->unit }}</small>@endif</td>
                                            <td class="text-right">{{ $item['this_month'] }} @if(isset($item['this_month']))<small class="text-muted">{{ $item['article']->unit }}</small>@endif</td>
                                            <td class="text-right">{{ $item['last_month'] }} @if(isset($item['last_month']))<small class="text-muted">{{ $item['article']->unit }}</small>@endif</td>
                                            <td class="text-right">{{ round($item['avg']) }} @if(isset($item['avg']))<small class="text-muted">{{ $item['article']->unit }}</small>@endif</td>
                                            <td class="text-right">
                                                @if(isset($item['peak']))
                                                    {{ $item['peak']->value }} <small class="text-muted">{{ $item['article']->unit }} ({{ $item['peak']->date }})</small>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        @component('components.alert.info')
                            No articels found.
                        @endcomponent
                    @endif                    
                </div>
            @endforeach
        </div>
    </div>
@endsection
