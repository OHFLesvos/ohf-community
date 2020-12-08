@extends('layouts.app', ['wide_layout' => false])

@section('title', __('badges.badges'))

@section('content')
    {!! Form::open(['route' => [ 'badges.selection' ], 'method' => 'post', 'files' => true]) !!}
        <div class="card shadow-sm mb-4">
            <div class="card-header">@lang('app.data_source')</div>
            <div class="card-body pb-2">
                <div class="mb-3">
                    {{ Form::bsRadioList('source', $sources, $source, __('app.source')) }}
                </div>
                <div id="file_upload" class="mb-3">
                    {{ Form::bsFile('file', [ 'accept' => '.xlsx,.xls,.csv' ], __('app.choose_file'), __('people.file_must_be_excel_cvs_containing_columns_name_position')) }}
                </div>
                <template id="new_input_list_row">
                    <div class="form-row">
                        <div class="col">{{ Form::bsText('name[]', null, ['placeholder' => __('app.name')], '') }}</div>
                        <div class="col-4">{{ Form::bsText('position[]', null, ['placeholder' => __('app.position')], '') }}</div>
                        <div class="col-4">{{ Form::bsFile('picture[]', ['accept' => 'image/*'], __('app.picture')) }}</div>
                        <div class="col-auto"><button type="button" class="btn btn-success"><x-icon icon="plus-circle"/></button></div>
                    </div>
                </template>
                <div id="input_list" class="mb-3"></div>
            </div>
            <div class="card-footer text-right">
                <x-form.bs-submit-button :label="__('app.next')"/>
            </div>
        </div>
    {!! Form::close() !!}
@endsection

@push('footer')
    <script>
        function toggleFileUplad() {
            if ($('input[name="source"]:checked').val() == 'file')
                $('#file_upload').show();
            else
                $('#file_upload').hide();
        }
        function toggleInputList() {
            if ($('input[name="source"]:checked').val() == 'list') {
                $('#input_list').show();
                if ($('#input_list').children().empty()) {
                    addNewInputListRow();
                }
            }
            else
                $('#input_list').hide();
        }
        function addNewInputListRow() {
            var elem = $('#new_input_list_row').clone().html();
            $('#input_list').find('button')
                .removeClass('btn-success')
                .addClass('btn-danger')
                .off('click')
                .on('click', removeInputListRow)
                .html('<i class="fa fa-minus-circle"></i>');
            $('#input_list').append(elem);
            $('#input_list').find('button').last().on('click', addNewInputListRow);
            $('#input_list').find('input[name="name[]"]').last().focus();
        }
        function removeInputListRow() {
            $(this).parents('div[class="form-row"]').remove();
        }
        $(function () {
            $('input[name="source"]').on('change', toggleFileUplad);
            toggleFileUplad();
            $('input[name="source"]').on('change', toggleInputList);
            toggleInputList();
        });
    </script>
@endpush
