@extends('layouts.app')

@section('title', 'Reporting: ' . $projectName)

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
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Article</th>
                                        <th>Unit</th>
                                        <th>Today</th>
                                        <th>Yesterday</th>
                                        <th>This week</th>
                                        <th>Last week</th>
                                        <th>This month</th>
                                        <th>Last month</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data[$type] as $item)
                                        <tr>
                                            <td><a href="{{ route('reporting.article', $item['article']) }}">{{ $item['article']->name }}</a></td>
                                            <td>{{ $item['article']->unit }}</td>
                                            <td>{{ $item['today'] }}</td>
                                            <td>{{ $item['yesterday'] }}</td>
                                            <td>{{ $item['this_week'] }}</td>
                                            <td>{{ $item['last_week'] }}</td>
                                            <td>{{ $item['this_month'] }}</td>
                                            <td>{{ $item['last_month'] }}</td>
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
