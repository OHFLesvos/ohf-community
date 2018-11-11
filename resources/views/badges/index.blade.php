@extends('layouts.app')

@section('title', __('people.badges'))

@section('content')
    {!! Form::open(['route' => [ 'badges.selection' ], 'method' => 'post', 'files' => true]) !!}
        <div class="mb-3">
            {{ Form::bsRadioList('source', $sources, $source, __('app.source')) }}
        </div>
        <div id="file_upload" class="mb-3">
            {{ Form::bsFile('file', [ 'accept' => '.xlsx,.xls,.csv' ], __('app.choose_file'), __('people.file_must_be_excel_cvs_containing_columns_name_position')) }}
        </div>
		<p>
			{{ Form::bsSubmitButton(__('app.next')) }}
		</p>
    {!! Form::close() !!}
@endsection

@section('script')
    function toggleFileUplad() {
        if ($('input[name="source"]:checked').val() == 'file')
            $('#file_upload').show();
        else
            $('#file_upload').hide();
    }
    
    $(function(){
        $('input[name="source"]').on('change', toggleFileUplad);
        toggleFileUplad();
    });
@endsection