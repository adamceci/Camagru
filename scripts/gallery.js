// Utils fct

function backToOne(backButton, toDisplay, toHide) {
	backButton.classList.add("hidden");
	toDisplay.classList.remove("hidden");
	toHide.classList.add("hidden");
}

function insertAfter(el, referenceNode) {
	referenceNode.parentNode.insertBefore(el, referenceNode.nextSibling);
}

// FILE

function displayFileForm() {
	let toDisplay = document.querySelector("#form_file");
	let toHide = document.querySelector("#choice");
	let backButton = document.createElement("button");

	backButton.innerHTML = "Back";
	backButton.addEventListener("click", function() {
		backToOne(backButton, toHide, toDisplay);
	});
	toDisplay.classList.remove("hidden");
	toHide.classList.add("hidden");
	insertAfter(backButton, toHide);
}

// CAMERA

function hasGetUserMedia() {
	return !!(navigator.mediaDevices &&
		navigator.mediaDevices.getUserMedia);
}

function displayCam() {
	let toDisplay = document.querySelector("#cam_div");
	let toHide = document.querySelector("#choice");
	let backButton = document.createElement("button");
	
	backButton.innerHTML = "Back";
	backButton.addEventListener("click", function() {
		backToOne(backButton, toHide, toDisplay);
	});
	toDisplay.classList.remove("hidden");
	toHide.classList.add("hidden");
	insertAfter(backButton, toHide);
	if (hasGetUserMedia()) {
		const constraints = {
			video: {
				width: { ideal: 720 },
				height: { ideal: 500 }
			}
		};

		const screenshotButton = document.querySelector('#screenshot-button');
		const img = document.querySelector('#screenshot-img');
		const video = document.querySelector('#main video');

		const canvas = document.createElement('canvas');

		let okBtn = document.querySelector("input[name='upload_cam_image']");

		navigator.mediaDevices.getUserMedia(constraints).
		then(handleSuccess).catch(handleError);

		screenshotButton.onclick = function() {
			canvas.width = video.videoWidth;
			canvas.height = video.videoHeight;
			canvas.getContext('2d').drawImage(video, 0, 0);
			// Other browsers will fall back to image/png
			img.src = canvas.toDataURL();
			okBtn.classList.remove("hidden");
		};

		function handleSuccess(stream) {
			streamReference = stream;
			backButton.addEventListener("click", function() {
				window.streamReference.getVideoTracks().forEach(function(track) {
					track.stop();
				});
				window.streamReference = null;
			});
			screenshotButton.disabled = false;
			video.srcObject = stream;
			screenshotButton.classList.remove("hidden");
		}

		function handleError() {
			alert("error.name : error.message");
		}

		function sendCamPic() {
			if (img.src != "data:,") {
				let xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						if (this.response.substr(-2) == "OK")
							window.location.replace("montage_two&webcam=1");
						else
							errorWrapper.innerHTML = this.response.substr(0, this.response.length - 2);
					}
				};
				xhttp.open("POST", "responses", true);
				xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhttp.send("src_cam_img=" + img.src);
			}
		}

		okBtn.addEventListener("click", sendCamPic);

	}
	else {
		alert('getUserMedia() is not supported by your browser');
	}
}

let fileButton = document.querySelector("#file_button");
let camButton = document.querySelector("#cam_button");

fileButton.addEventListener("click", displayFileForm);
camButton.addEventListener("click", displayCam);