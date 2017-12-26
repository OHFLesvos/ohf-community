@extends('layouts.app')

@section('title', 'Logistics')

@section('content')
<table class="table table-sm table-bordered table-striped table-hover">
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