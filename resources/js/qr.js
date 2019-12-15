
/*
 * Instascan QR code camera
 */
import jsQR from 'jsqr'

$(document.body).append('<div class="modal" id="videoPreviewModal" tabindex="-1" role="dialog" aria-labelledby="videoPreviewModalLabel" aria-hidden="true">' +
'<div class="modal-dialog" role="document">' +
	'<div class="modal-content">' +
		'<div class="modal-header">' +
			'<h5 class="modal-title" id="videoPreviewModalLabel">' + scannerDialogTitle + '</h5>' +
			'<span>' +
				'<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
				' <span aria-hidden="true">&times;</span>' +
				'</button>' +
				'<button type="button" class="close" id="keyboard-input">' +
				' <i class="fa fa-keyboard"></i>' +
				'</button>' +
				'<button type="button" class="close" id="qrcode-input">' +
				' <i class="fa fa-qrcode"></i>' +
				'</button>' +
			'</span>' +
		'</div>' +
		'<div class="modal-body">' +
		 '   <canvas id="preview" hidden style="width: 100%; height: 100%"></canvas>' +
		 '   <span id="videoPreviewMessage">' + scannerDialogWaitMessage + '...</span>' +
		 '   <div class="input-group" id="codeInput">' +
		 '     <input type="text" class="form-control">' +
		 '     <div class="input-group-append">' +
	     '       <button class="btn btn-outline-secondary" type="button"><i class="fa fa-check"></i></button>' +
		 '     </div>' +
	     '   </div>' +
		'</div>' +
	'</div>' +
'</div>' +
'</div>');

var video = document.createElement("video");
var canvasElement = document.getElementById("preview");
var canvas = canvasElement.getContext("2d");
var videoPreviewMessage = $('#videoPreviewMessage');
var codeInput = $('#codeInput')

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
		if (code && code.data.trim().length > 0) {
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

function stopCamera() {
	video.pause();
	if (localStream) {
		console.log('stop local stream')
		localStream.getTracks().forEach((track) => {
			track.stop();
		});
	}
	canvas.clearRect(0, 0, canvasElement.width, canvasElement.height);
}

function enableCamera() {
	codeInput.hide();
	navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
		.then((stream) => {
			if (stream.getVideoTracks().length > 0) {
				$('#videoPreviewModal').modal('show');
				// Use facingMode: environment to attemt to get the front camera on phones
				video.srcObject = stream;
				localStream = stream;
				video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
				video.play();
				requestAnimationFrame(tick);
			} else {
				alert('No camera stream available!');
			}
		})
		.catch((err) => {
			alert('No camera available: ' + err);
		});
}

function buttonClick() {
	var value = codeInput.find('input').val()
	if (value.trim().length > 0) {
		$('#videoPreviewModal').modal('hide');
		qrCallback(value);
	} else {
		codeInput.find('input').focus()
	}
}

function useKeyboard() {
	$('#keyboard-input').hide();
	videoPreviewMessage.hide();
	codeInput.show();
	$('#qrcode-input').show();
	stopCamera();
	$('#videoPreviewModal').modal('show');
	canvasElement.hidden = true;
	codeInput.find('input').focus()
	sessionStorage.setItem('qr.inputmethod', 'keyboard');
}

function useCamera() {
	if (typeof navigator.mediaDevices === 'undefined') {
		alert('Cannot access media devices. Make sure you are using HTTPS.')
		return;
	}
	$('#qrcode-input').hide();
	$('#keyboard-input').show();
	videoPreviewMessage.show();
	enableCamera();
	sessionStorage.setItem('qr.inputmethod', 'camera');
}

export default function (callback) {
	qrCallback = callback;

	let inputmethod = sessionStorage.getItem('qr.inputmethod');
	if (inputmethod && inputmethod == 'keyboard') {
		useKeyboard()
	} else {
		useCamera()
	}

	$('#videoPreviewModal').on('hide.bs.modal', (e) => {
		stopCamera();
	})

	$('#keyboard-input').off('click').on('click', useKeyboard);
	$('#qrcode-input').off('click').on('click', useCamera);

	codeInput.find('button').off('click').on('click', buttonClick)
	codeInput.find('input').off('keypress').on('keypress', function(e) {
		if(e.which == 13) {
			buttonClick()
		}
	});

}
