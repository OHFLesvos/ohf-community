<table id="{{ form_id_string($name) }}" class="table">
    @if ($label !== null || ! empty($label))
        <thead>
            <th>{{ $label }}</th>
            <th>@lang('app.from')</th>
            <th>@lang('app.to')</th>
            <th class="fit text-center"><x-icon icon="trash-alt"/></th>
        </thead>
    @endif
    <tbody>
        @foreach($value as $entry)
            <tr>
                @php
                $all_values = $entries->pluck('text', 'text');
                if (! isset($all_values[$entry['value']])) {
                    $all_values[$entry['value']] = $entry['value'] . ' (' .  __('app.not_available') . ')';
                }
                @endphp
                <td>{{ Form::select($name . '[' . $loop->index . '][name]', $all_values, $entry['value'], [ 'class' => 'custom-select'.($errors->has($name) ? ' is-invalid' : '') ]) }}</td>
                <td>{{ Form::date($name . '[' . $loop->index . '][from]', $entry['from'], [ 'class' => 'form-control'.($errors->has($name) ? ' is-invalid' : ''), 'pattern' => '[0-9]{4}-[0-9]{2}-[0-9]{2}' ]) }}</td>
                <td>{{ Form::date($name . '[' . $loop->index . '][to]', $entry['to'], [ 'class' => 'form-control'.($errors->has($name) ? ' is-invalid' : ''), 'pattern' => '[0-9]{4}-[0-9]{2}-[0-9]{2}' ]) }}</td>
                <td class="fit text-center">
                    <button type="button" class="btn btn-danger input-list-delete-button">
                        <x-icon icon="minus-circle"/>
                    </button>
                </td>
            </tr>
        @endforeach
        {{-- Template row that is not displayed, but copied when the add button is clicked --}}
        <tr class="template" style="display: none" data-index="{{ count($value) }}">
            <td>{{ Form::select(null, $entries->pluck('text', 'text'), '', [ 'data-name' => $name . '[$index$][name]', 'class' => 'custom-select'.($errors->has($name) ? ' is-invalid' : '') ]) }}</td>
            <td>{{ Form::date(null, '', [ 'data-name' => $name . '[$index$][from]', 'class' => 'form-control'.($errors->has($name) ? ' is-invalid' : ''), 'pattern' => '[0-9]{4}-[0-9]{2}-[0-9]{2}' ]) }}</td>
            <td>{{ Form::date(null, '', [ 'data-name' => $name . '[$index$][to]', 'class' => 'form-control'.($errors->has($name) ? ' is-invalid' : ''), 'pattern' => '[0-9]{4}-[0-9]{2}-[0-9]{2}' ]) }}</td>
            <td class="fit text-center">
                <button type="button" class="btn btn-danger input-list-delete-button">
                    <x-icon icon="minus-circle"/>
                </button>
            </td>
        </tr>
        <tr>
            <td class="border-top-0">
                {{-- Put this inside a table row, as Firefox meight page-break after the table an render the button in the next column --}}
                <button type="button" class="btn btn-success input-list-add-button" data-table="#{{ form_id_string($name) }}">
                    <x-icon icon="plus-circle"/>
                    @lang('app.add')
                </button>
            </td>
        </tr>
    </tbody>
</table>
@if ($errors->has($name))
    <div><small class="text-danger">{{ $errors->first($name) }}</small></div>
@endif
@if (isset($help))
    <small id="{{ $name }}HelpBlock" class="form-text text-muted">
        {{ $help }}
    </small>
@endif
