@extends('layouts.centered', ['width' => 650])

@section('centered-content')
<div class="card">
    <div class="card-body">
@markdown
{{ $content }}
@endmarkdown
    </div>
</div>

@endsection
