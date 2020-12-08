<div class="row">
    @foreach($data as $item)
        <div class="col-lg-2 col-md-3 col-sm-4 col-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header p-2">
                    <x-icon-gender :gender="$item['model']->gender"/>
                    @isset($item['model']->nickname)
                        {{ $item['model']->nickname }}
                    @else
                        {{ $item['model']->first_name }}
                    @endisset
                </div>
                <div class="card-body p-0">
                    @can('view', $item['model'])
                        <a href="{{ route('cmtyvol.show', $item['model']) }}">
                    @endcan
                    @isset($item['model']->portrait_picture)
                        <img src="{{ Storage::url($item['model']->portrait_picture) }}" class="img-fluid" alt="Portrait">
                    @else
                        <img src="{{ asset('img/portrait_placeholder.png') }}" class="img-fluid" alt="Placeholder">
                    @endisset
                    @can('view', $item['model'])
                        </a>
                    @endcan
                </div>
                <div class="card-body py-1 px-2">
                    <small>
                        {{ $item['model']->first_name }} {{ strtoupper($item['model']->family_name) }}<br>
                        {{ $item['model']->age }}@if($item['model']->age != null && $item['model']->nationality != null),@endif
                        {{ $item['model']->nationality }}<br>
                        @if(is_array($item['model']->responsibilities) && count($item['model']->responsibilities) > 0)
                            {{ implode(', ', $item['model']->responsibilities) }}
                        @endif
                    </small>
                </div>
            </div>
        </div>
    @endforeach
</div>
