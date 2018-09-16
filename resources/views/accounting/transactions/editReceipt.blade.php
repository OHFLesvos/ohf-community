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
        <button class="btn btn-primary" id="upload">@icon(upload)<span class="d-none d-sm-inline"> @lang('app.upload')</span></button>
        <button class="btn btn-primary" id="startCapture">@icon(camera)<span class="d-none d-sm-inline"> @lang('app.capture')</span></button>
        <button class="btn btn-danger" id="delete" @if($transaction->receipt_picture == null) hidden @endif >@icon(trash)<span class="d-none d-sm-inline"> @lang('app.delete')</span></button>
    </p>

    <div class="progress">
        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
    </div>

    <div class="alert" role="alert"></div>

    <p class="text-center">
        <img class="img-fluid" id="preview" @if($transaction->receipt_picture != null) src="{{ Storage::url($transaction->receipt_picture) }}" @else hidden @endif alt="Preview">
        <input type="file" class="sr-only" id="input" name="image" accept="image/*">
    </p>
@endsection

@section('footer')
    <script src="{{ asset('js/imageupload.js') }}?v={{ $app_version }}"></script>
    <script>
        var csrfToken = '{{ csrf_token() }}';
        var imageUploadUrl = '{{ route('accounting.transactions.updateReceipt', $transaction) }}';
        var imageDeleteUrl = '{{ route('accounting.transactions.deleteReceipt', $transaction) }}';
        var imageDeleteConfirmation = '@lang('accounting.confirm_delete_receipt')';
    </script>

    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">
                        <span id="cropTitle">@lang('app.crop_the_image')</span>
                        <span id="captureTitle">@lang('app.capture_image')</span>
                    </h5>
                    <span>
                        <button type="button" class="close" id="capture">
                            <span class="text-">@icon(camera)</span>
                        </button>
                        <button type="button" class="close" id="crop">
                            <span class="text-">@icon(crop)</span>
                        </button>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            @icon(times)
                        </button>
                    </span>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <img id="image" hidden>
                        <video id="player" autoplay style="max-width: 100%;" hidden></video>
                    </div>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">@icon(times) @lang('app.cancel')</button>
                    <button type="button" class="btn btn-primary" id="crop">@icon(crop) @lang('app.crop')</button>
                </div> --}}
            </div>
        </div>
    </div>

    <canvas id="captureCanvas" hidden></canvas>

@endsection
