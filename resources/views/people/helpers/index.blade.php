@extends('layouts.app')

@section('title', __('people.helpers'))

@section('content')

    <div class="table-responsive">
        <table class="table table-sm table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Name</th>
                    <th>Family Name</th>
                    <th>Nationality</th>
                </tr>
            </thead>
            <tbody>
                @foreach($persons as $person)
                    <tr>
                        <td>-</td>
                        <td>{{ $person->name }}</td>
                        <td>{{ $person->family_name }}</td>
                        <td>{{ $person->nationality }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
