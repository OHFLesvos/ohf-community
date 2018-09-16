var Cropper = require('cropperjs').default;
var Snackbar = require('node-snackbar');

function showAlert(message) {
    Snackbar.show({
        text: message,
        duration: 2500,
        pos: 'bottom-center',
        actionText: null,
    });
}

// Video constraints
const constraints = {
    video: {
        width: {
            min: 1280
        },
        height: {
            min: 720
        }
    }
};

window.addEventListener('DOMContentLoaded', () => {
    var upload = document.getElementById('upload');
    var remove = document.getElementById('delete');
    var preview = document.getElementById('preview');
    var image = document.getElementById('image');
    var input = document.getElementById('input');
    var $progress = $('.progress');
    var $progressBar = $('.progress-bar');
    var $alert = $('.alert');
    var $modal = $('#modal');
    const cropButton = document.getElementById('crop');
    const cropTitle = document.getElementById('cropTitle');
    const captureTitle = document.getElementById('captureTitle');

    const startCaptureButton = document.getElementById('startCapture');
    const captureButton = document.getElementById('capture');
    const player = document.getElementById('player');
    const captureCanvas = document.getElementById('captureCanvas');

    var cropper;

    // Click on "Upload" button
    upload.addEventListener('click', () => {
        input.click();
    });

    // Click on "Capture" button
    startCaptureButton.addEventListener('click', () => {
        image.hidden = true;
        player.hidden = false;
        cropTitle.hidden = true;
        captureTitle.hidden = false;
        image.src = null;

        navigator.mediaDevices.getUserMedia(constraints)
            .then((stream) => {
               
                captureButton.hidden = false;
                cropButton.hidden = true;

                $modal.modal('show');
                // Attach the video stream to the video element and autoplay.
                player.srcObject = stream;
            });
        // TODO exception handling
    });

     // Capture recorded image
    captureButton.addEventListener('click', () => {
        player.srcObject.getVideoTracks().forEach(track => track.stop());
  
        captureCanvas.height = player.videoHeight;
        captureCanvas.width = player.videoWidth;
        var context = captureCanvas.getContext('2d');
        context.drawImage(player, 0, 0, captureCanvas.width, captureCanvas.height);

        image.src = captureCanvas.toDataURL("image/png");

        image.hidden = false;
        player.hidden = true;
        captureButton.hidden = true;
        cropButton.hidden = false;

        cropTitle.hidden = false;
        captureTitle.hidden = true;
        
        if (cropper) {
            cropper.destroy();
        }
        cropper = new Cropper(image, {
            viewMode: 3,
        });
    });

    // Upload file
    input.addEventListener('change', (e) => {
        var files = e.target.files;
        var done = function (url) {
            input.value = '';
            image.src = url;

            image.hidden = false;
            player.hidden = true;
            captureButton.hidden = true;
            cropButton.hidden = false;
            cropTitle.hidden = false;
            captureTitle.hidden = true;  

            $alert.hide();
            $modal.modal('show');
        };
        var reader;
        var file;

        if (files && files.length > 0) {
            file = files[0];
            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    $modal.on('shown.bs.modal', () => {
        cropper = new Cropper(image, {
            viewMode: 3,
        });
    }).on('hidden.bs.modal', () => {
        cropper.destroy();
        cropper = null;

        // Stop all video streams.
        if (player.srcObject != null) {
            player.srcObject.getVideoTracks().forEach(track => track.stop());
        }
    });

    cropButton.addEventListener('click', () => {
        var initialPreviewURL;
        var canvas;

        $modal.modal('hide');

        if (cropper) {
            canvas = cropper.getCroppedCanvas({
                maxWidth: 800,
                maxHeight: 800,
             });
            initialPreviewURL = preview.src;
            preview.src = canvas.toDataURL();
            preview.hidden = false;
            $progressBar.width('0%').attr('aria-valuenow', 0).text('0%');
            $progress.show();
            $alert.removeClass('alert-success alert-warning');
            canvas.toBlob(function (blob) {
          
                var formData = new FormData();
                formData.append('img', blob);
                formData.append("_token", csrfToken);
          
                // AJAX request
                $.ajax(imageUploadUrl, {
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,

                    xhr: () => {
                        var xhr = new XMLHttpRequest();

                        xhr.upload.onprogress = function (e) {
                            var percent = '0';
                            var percentage = '0%';

                            if (e.lengthComputable) {
                            percent = Math.round((e.loaded / e.total) * 100);
                            percentage = percent + '%';
                            $progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
                            }
                        };

                        return xhr;
                    },

                    // Success
                    success: () => {
                        showAlert('Upload success');
                        remove.hidden = false;
                    },

                    // Error
                    error: () => {
                        preview.src = initialPreviewURL;
                        showAlert('Upload error');
                    },

                    // Complete
                    complete: () => {
                        $progress.hide();
                    },
                });
            });
        }
    });

    // Remove image
    if (remove) {
        remove.addEventListener('click', () => {
            if (confirm(imageDeleteConfirmation)) {
                $.ajax(imageDeleteUrl, {
                    method: 'DELETE',
                    data: {
                        _token: csrfToken
                    },
        
                    // Success
                    success: () => {
                        showAlert('Delete success');
                        remove.hidden = true;
                        preview.hidden = true;
                    },
        
                    // Error
                    error: () => {
                        showAlert('Delete error');
                    },
                });
            }
        });
    }
});
