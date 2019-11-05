function hasGetUserMedia() {
	return !!(navigator.mediaDevices &&
		navigator.mediaDevices.getUserMedia);
}

if (hasGetUserMedia()) {
	// Good to go!
	const constraints = {
		video: {
			width: { ideal: 720 },
			height: { ideal: 500}
		}
	};

	const captureVideoButton = document.querySelector('#capture-button');
	const screenshotButton = document.querySelector('#screenshot-button');
	const img = document.querySelector('#main img');
	const video = document.querySelector('#main video');

	const canvas = document.createElement('canvas');

	captureVideoButton.onclick = function() {
		navigator.mediaDevices.getUserMedia(constraints).
		then(handleSuccess).catch(handleError);
	};

	screenshotButton.onclick = video.onclick = function() {
		canvas.width = video.videoWidth;
		canvas.height = video.videoHeight;
		canvas.getContext('2d').drawImage(video, 0, 0);
		// Other browsers will fall back to image/png
		img.src = canvas.toDataURL('image/webp');
	};

	function handleSuccess(stream) {
		screenshotButton.disabled = false;
		video.srcObject = stream;
	}

	function handleError() {
		console.log("error.name : error.message");
	}
}
else {
	alert('getUserMedia() is not supported by your browser');
}