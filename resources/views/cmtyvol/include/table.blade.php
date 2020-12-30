<div class="table-responsive shadow-sm rounded">
    <table class="table table-hover bg-white">
        <thead>
            <tr>
                @foreach($fields as $field)
                    <th>
                        @isset($field['icon'])
                            <x-icon :icon="$field['icon']" :style="$field['icon_style']" class="d-none d-sm-inline"/>
                        @endisset
                        {{ $field['label'] }}
                    </th>
                @endforeach
            </tr>
        </thead>
        {{ $slot }}
    </table>
</div>
