<div class="modal" id="{{ $name }}Modal" tabindex="-1" role="dialog" aria-labelledby="{{ $name }}ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="javascript:;" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $name }}ModalLabel">{{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0">
                   {{ $slot }}
                </div>
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            </form>
        </div>
    </div>
</div>
