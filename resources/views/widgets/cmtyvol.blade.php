@extends('widgets.base')

@section('widget-title', __('cmtyvol.community_volunteers'))

@section('widget-content')
    <div class="card-body pb-2">
        <p>
            @lang('cmtyvol.we_have_n_community_volunteers', [
                'active' => $active,
            ])<br>
        </p>
    </div>
@endsection
