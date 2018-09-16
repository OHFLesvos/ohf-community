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

/*
const player = document.getElementById('player');
const canvas = document.getElementById('canvas');
const context = canvas.getContext('2d');
const captureButton = document.getElementById('imageCapture');
const recaptureButton = document.getElementById('imageRecapture');
const submitButton = document.getElementById('imageSubmit');
const imageValue = document.getElementById('imageValue');

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

  function startCapture() {
      navigator.mediaDevices.getUserMedia(constraints)
          .then((stream) => {
              // Attach the video stream to the video element and autoplay.
              player.srcObject = stream;
          });
  }

  recaptureButton.addEventListener('click', () => {
      startCapture();
      recaptureButton.hidden = true;
      captureButton.hidden = false;
      submitButton.hidden = true;
  });

  captureButton.addEventListener('click', () => {
      canvas.height = player.videoHeight;
      canvas.width = player.videoWidth;

      context.drawImage(player, 0, 0, canvas.width, canvas.height);
      recaptureButton.hidden = false;
      captureButton.hidden = true;
      submitButton.hidden = false;

      player.hidden = true;


      // Stop all video streams.
      player.srcObject.getVideoTracks().forEach(track => track.stop());
  });

  submitButton.addEventListener('click', () => {
      var dataURL = canvas.toDataURL("image/png");
      imageValue.value = dataURL;
  });

  startCapture();
*/

window.addEventListener('DOMContentLoaded', function () {
    var upload = document.getElementById('upload');
    var remove = document.getElementById('delete');
    var preview = document.getElementById('preview');
    var image = document.getElementById('image');
    var input = document.getElementById('input');
    var $progress = $('.progress');
    var $progressBar = $('.progress-bar');
    var $alert = $('.alert');
    var $modal = $('#modal');
    var cropper;

    upload.addEventListener('click', function (e) {
        input.click();
    });

    if (remove) {
        remove.addEventListener('click', function (e) {
            $.ajax(imageDeleteUrl, {
                method: 'DELETE',
                data: {
                    _token: csrfToken
                },
    
                // Success
                success: function () {
                    showAlert('Delete success');
                    remove.hidden = true;
                    preview.hidden = true;
                },
    
                // Error
                error: function () {
                    showAlert('Delete error');
                },
            });
        });
    }

    input.addEventListener('change', function (e) {
        var files = e.target.files;
        var done = function (url) {
            input.value = '';
            image.src = url;
            $alert.hide();
            $modal.modal('show');
        };
        var reader;
        var file;
        var url;

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

    $modal.on('shown.bs.modal', function () {
        cropper = new Cropper(image, {
            viewMode: 3,
        });
    }).on('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
    });

    document.getElementById('crop').addEventListener('click', function () {
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

                    xhr: function () {
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
                    success: function () {
                        showAlert('Upload success');
                        remove.hidden = false;
                    },

                    // Error
                    error: function () {
                        preview.src = initialPreviewURL;
                        showAlert('Upload error');
                    },

                    // Complete
                    complete: function () {
                        $progress.hide();
                    },
                });
            });
        }
    });
});
