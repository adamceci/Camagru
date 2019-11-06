function hasGetUserMedia() {
	return !!(navigator.mediaDevices &&
		navigator.mediaDevices.getUserMedia);
}

function displayVid() {
	let toHideElem;
	let form;

	//add elem left arrow
	toHideElem = document.querySelector("#choice");
	toHideElem.classList.add("hidden");
	form = document.querySelector("#form_vid");
	form.classList.remove("hidden");
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
			alert("working");
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
}

function displayFile() {
	let toHideElem;
	let form;

	toHideElem = document.querySelector("#choice");
	toHideElem.classList.add("hidden");
	form = document.querySelector("#form_file");
	form.classList.remove("hidden");
}

function removeImage(data) {
	let divToRemove = data["path"][2];
	let imageToRemove = divToRemove.querySelector("img").getAttribute("src");
	let xhttp = new XMLHttpRequest();

	if (confirm("Are you sure you want to delete this image ?")) {
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200 && this.response == "OK")
				divToRemove.remove();
		};
		xhttp.open("GET", "responses?toDelSrc=" + imageToRemove, true);
		xhttp.send();
	}
}

function publishImage(data) {
	let buttonToRemove = data["path"][1];
	let imageToPublish = data["path"][2]["children"][2]["src"];
	let xhttp = new XMLHttpRequest();

	if (confirm("Are you sure you want to post this image ?")) {
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200 && this.response == "OK")
				buttonToRemove.remove();
		};
		xhttp.open("GET", "responses?toPubSrc=" + imageToPublish, true);
		xhttp.send();
	}
}

let fileButton;
let videoButton;
let removeButtons;
let postButtons;

fileButton = document.querySelector("#chose_file");
videoButton = document.querySelector("#chose_vid");
removeButtons = document.querySelectorAll(".remove");
postButtons = document.querySelectorAll(".post");

videoButton.addEventListener("click", displayVid);
fileButton.addEventListener("click", displayFile);

for (let removeButton of removeButtons) {
	removeButton.addEventListener("click", removeImage);
}
for (let postButton of postButtons) {
	postButton.addEventListener("click", publishImage);
}