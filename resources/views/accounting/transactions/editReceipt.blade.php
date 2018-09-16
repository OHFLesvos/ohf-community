@extends('layouts.app')

@section('title', __('accounting.receipt'))

@section('head-meta')
    <link href="{{ asset('css/cropper.min.css') }}?v={{ $app_version }}" rel="stylesheet" type="text/css">
    <style>
        .label {
            cursor: pointer;
        }

        .progress {
            display: none;
            margin-bottom: 1rem;
        }

        .alert {
            display: none;
        }

        .img-container img {
            max-width: 100%;
        }
    </style>
@endsection

@section('content')

    <p class="text-center">
        <button class="btn btn-primary" id="upload">@icon(upload) @lang('app.upload')</button>
        <button class="btn btn-danger" id="delete" @if($transaction->receipt_picture == null) hidden @endif >@icon(trash) @lang('app.delete')</button>
    </p>

    <div class="progress">
        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
    </div>

    <div class="alert" role="alert"></div>

    <p class="text-center">
        <img class="img-fluid" id="preview" @if($transaction->receipt_picture != null) src="{{ Storage::url($transaction->receipt_picture) }}" @else hidden @endif alt="Preview">
        <input type="file" class="sr-only" id="input" name="image" accept="image/*">
    </p>

{{-- 
    {!! Form::model($transaction, ['route' => ['accounting.transactions.updateReceipt', $transaction], 'method' => 'put', 'files' => true]) !!}
    {{ Form::hidden('img', null, ['id' => 'imageValue']) }}
    <div>
        <video id="player" autoplay style="border: 1px solid black; max-width: 100%;"></video>
        <canvas id="canvas" hidden></canvas>
    </div>
    <div>
        <button id="imageCapture" class="btn btn-primary" type="button">@icon(camera) Capture</button>
        <button id="imageRecapture" class="btn btn-secondary" type="button" hidden>@icon(undo) Recapture</button>
        <button id="imageSubmit" class="btn btn-primary" type="submit" hidden>@icon(save) Save</button>
    </div>
    {!! Form::close() !!} 
--}}

@endsection

@section('footer')
    <script src="{{ asset('js/imageupload.js') }}?v={{ $app_version }}"></script>
    <script>
        var csrfToken = '{{ csrf_token() }}';
        var imageUploadUrl = '{{ route('accounting.transactions.updateReceipt', $transaction) }}';
        var imageDeleteUrl = '{{ route('accounting.transactions.deleteReceipt', $transaction) }}';
    </script>

    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">@lang('app.crop_the_image')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <img id="image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">@icon(times) @lang('app.cancel')</button>
                    <button type="button" class="btn btn-primary" id="crop">@icon(crop) @lang('app.crop')</button>
                </div>
            </div>
        </div>
    </div>
@endsection
