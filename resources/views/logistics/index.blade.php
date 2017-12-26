@extends('layouts.app')

@section('title', 'Logistics')

@section('content')

<div class="d-block d-sm-none">
    @foreach (($projects)->filter(function($p){ return $p->has_article_mgmt; }) as $project)
        <h3 class="display-4 mb-3">{{ $project->name }}</h3>
        <p><a href="{{ route('logistics.articles.index', $project) }}" class="btn btn-secondary btn-block">
            Manage articles
        </a></p>
    @endforeach
</div>

<table class="table table-sm table-bordered table-striped table-hover d-none d-sm-table">
    <thead>
        <tr>
            <th>Project</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($projects as $project)
        <tr>
            <td>{{ $project->name }}</td>
            <td>
                @if ($project->has_article_mgmt)
                    <a href="{{ route('logistics.articles.index', $project) }}">Manage articles</a>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@endsection

@section('script')
@endsection