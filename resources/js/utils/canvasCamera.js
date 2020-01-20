export default class {
    constructor(canvasProvider) {
        this.canvasProvider = canvasProvider
        // Use facingMode: environment to attemt to get the front camera on phones
        this.facingMode = "environment"
    }

    setImageDataCallback(imageDataCallback) {
        this.imageDataCallback = imageDataCallback
    }

    setVideoLoadedCallback(videoLoadedCallback) {
        this.videoLoadedCallback = videoLoadedCallback
    }

    setVideoStoppedCallback(videoStoppedCallback) {
        this.videoStoppedCallback = videoStoppedCallback
    }

    startCamera() {
        this.video = document.createElement("video")
        navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: this.facingMode
                }
            })
            .then(stream => {
                if (stream.getVideoTracks().length > 0) {
                    this.video.srcObject = stream;
                    this.stream = stream;
                    this.video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
                    this.video.play();
                    this.video.addEventListener('loadedmetadata', () => {
                        if (this.videoLoadedCallback) {
                            this.videoLoadedCallback()
                        }
                    })
                    const canvas = this.canvasProvider()
                    const frameRate = stream.getVideoTracks()[0].getSettings().frameRate
                    requestAnimationFrame(() => this.videoFrameTick(canvas, frameRate))
                } else {
                    alert('No camera stream available!');
                }
            })
            .catch(err => {
                alert('No camera available: ' + err);
            });
    }

    videoFrameTick(canvas, frameRate) {
        const video = this.video
        if (!video.paused && !video.ended) {
            if (canvas && video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.hidden = false
                canvas.height = video.videoHeight
                canvas.width = video.videoWidth
                const ctx = canvas.getContext("2d");
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height)
                if (this.imageDataCallback != null) {
                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height)
                    this.imageDataCallback(imageData)
                }
            }
            setTimeout(() => requestAnimationFrame(() => this.videoFrameTick(canvas, frameRate)), 1000 / frameRate)
        }
    }

    stopCamera() {
        if (this.video) {
            this.video.pause()
        }
        if (this.stream) {
            this.stream.getTracks().forEach(track => {
                track.stop()
            });
        }
        const canvas = this.canvasProvider()
        if (canvas) {
            const ctx = canvas.getContext("2d");
            ctx.clearRect(0, 0, canvas.width, canvas.height)
            canvas.hidden = true
        }
        if (this.videoStoppedCallback) {
            this.videoStoppedCallback()
        }
    }
}