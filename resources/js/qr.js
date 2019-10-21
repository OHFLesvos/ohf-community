
/*
 * Instascan QR code camera 
 */
import jsQR from 'jsqr'

$(document.body).append('<div class="modal" id="videoPreviewModal" tabindex="-1" role="dialog" aria-labelledby="videoPreviewModalLabel" aria-hidden="true">' +
'<div class="modal-dialog" role="document">' +
	'<div class="modal-content">' +
		'<div class="modal-header">' +
			'<h5 class="modal-title" id="videoPreviewModalLabel">' + scannerDialogTitle + '</h5>' +
			'<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
			   ' <span aria-hidden="true">&times;</span>' +
			'</button>' +
		'</div>' +
		'<div class="modal-body">' +
		 '   <canvas id="preview" hidden style="width: 100%; height: 100%"></canvas>' +
		 '   <span id="videoPreviewMessage">' + scannerDialogWaitMessage + '...</span>' +
		'</div>' +
	'</div>' +
'</div>' +
'</div>');

var video = document.createElement("video");
var canvasElement = document.getElementById("preview");
var canvas = canvasElement.getContext("2d");
var videoPreviewMessage = $('#videoPreviewMessage');

var localStream;
var qrCallback;

function drawLine(begin, end, color) {
	canvas.beginPath();
	canvas.moveTo(begin.x, begin.y);
	canvas.lineTo(end.x, end.y);
	canvas.lineWidth = 4;
	canvas.strokeStyle = color;
	canvas.stroke();
}

function tick() {
	if (video.readyState === video.HAVE_ENOUGH_DATA) {
		videoPreviewMessage.hide();
		canvasElement.hidden = false;
		canvasElement.height = video.videoHeight;
		canvasElement.width = video.videoWidth;
		canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
		var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
		var code = jsQR(imageData.data, imageData.width, imageData.height, {
			inversionAttempts: "dontInvert",
		});
		if (code) {
			drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#FF3B58");
			drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#FF3B58");
			drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#FF3B58");
			drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#FF3B58");
			
			video.pause();
			localStream.getTracks().forEach((track) => {
				track.stop();
			});

			$('#videoPreviewModal').modal('hide');
			qrCallback(code.data);
			return;
		}
	}
	requestAnimationFrame(tick);
}

export default function (callback) {
	navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
		.then((stream) => {
			if (stream.getVideoTracks().length > 0) {
				$('#videoPreviewModal').modal('show');
				// Use facingMode: environment to attemt to get the front camera on phones
				video.srcObject = stream;
				localStream = stream;
				video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
				video.play();
				qrCallback = callback;
				requestAnimationFrame(tick);
				$('#videoPreviewModal').on('hide.bs.modal', (e) => {
					video.pause();
					localStream.getTracks().forEach((track) => {
						track.stop();
					});
					canvas.clearRect(0, 0, canvasElement.width, canvasElement.height);
				})			
			} else {
				alert('No camera stream available!');
			}
		})
		.catch((err) => {
			alert('No camera available: ' + err);
		});
}
