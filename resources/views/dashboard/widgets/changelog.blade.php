<div class="card mb-4">
    <div class="card-header">
        @lang('app.changelog')
        <span class="pull-right">@lang('app.version'): <strong>{{ $app_version }}</strong></span>
    </div>
    <div class="card-body pb-2">
        <p>@lang('app.changelog_link_desc', ['link' => route('changelog')])</p>
    </div>
</div>
