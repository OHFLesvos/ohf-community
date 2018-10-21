<div class="table-responsive">
    <table class="table table-sm table-striped table-bordered table-hover">
        <thead>
            <tr>
                @foreach($fields as $field)
                    <th>
                        @isset($field['icon'])<span class="d-none d-sm-inline">@icon({{$field['icon']}}) </span>@endisset
                        {{ $field['label'] }}
                    </th>
                @endforeach
            </tr>
        </thead>