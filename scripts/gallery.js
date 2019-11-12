function hasGetUserMedia() {
	return !!(navigator.mediaDevices &&
		navigator.mediaDevices.getUserMedia);
}

function displayVid() {
	let toHideElem;
	let camDiv;

	//add elem left arrow
	toHideElem = document.querySelector("#choice");
	toHideElem.classList.add("hidden");
	camDiv = document.querySelector("#cam_div");
	camDiv.classList.remove("hidden");
	if (hasGetUserMedia()) {
		const constraints = {
			video: {
				width: { ideal: 720 },
				height: { ideal: 500}
			}
		};

		const screenshotButton = document.querySelector('#screenshot-button');
		const img = document.querySelector('#screenshot-img');
		const video = document.querySelector('#main video');

		const canvas = document.createElement('canvas');

		navigator.mediaDevices.getUserMedia(constraints).
		then(handleSuccess).catch(handleError);

		screenshotButton.onclick = video.onclick = function() {
			canvas.width = video.videoWidth;
			canvas.height = video.videoHeight;
			canvas.getContext('2d').drawImage(video, 0, 0);
			// Other browsers will fall back to image/png
			img.src = canvas.toDataURL('image/webp');
		};

		function handleSuccess(stream) {
			console.log(stream);
			screenshotButton.disabled = false;
			video.srcObject = stream;
		}

		function handleError() {
			alert("error.name : error.message");
		}
	}
	else {
		alert('getUserMedia() is not supported by your browser');
	}
}

function displayImage(data) {
	console.log(data);
}

function displayFile() {
	let toHideElem;
	let form;
	let fileChosen;

	toHideElem = document.querySelector("#choice");
	toHideElem.classList.add("hidden");
	form = document.querySelector("#form_file");
	form.classList.remove("hidden");
	fileChosen = document.querySelector("input[type=file]");
	fileChosen.addEventListener("change", displayImage);

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
	let buttonToRemove = data["target"];
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

function backToOne(backButton, toDisplay, toHide) {
	backButton.classList.add("hidden");
	toDisplay.classList.remove("hidden");
	toHide.classList.add("hidden");
}

function insertAfter(el, referenceNode) {
	referenceNode.parentNode.insertBefore(el, referenceNode.nextSibling);
}

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

let fileButton = document.querySelector("#file_button");
let camButton = document.querySelector("#cam_button");

fileButton.addEventListener("click", displayFileForm);
// camButton.addEventListener("click", displayCamForm);





// let fileButton;
// let videoButton;
// let removeButtons;
// let postButtons;

// fileButton = document.querySelector("#chose_file");
// videoButton = document.querySelector("#chose_vid");
// removeButtons = document.querySelectorAll(".remove");
// postButtons = document.querySelectorAll(".post");

// videoButton.addEventListener("click", displayVid);
// fileButton.addEventListener("click", displayFile);

// for (let removeButton of removeButtons) {
// 	removeButton.addEventListener("click", removeImage);
// }
// for (let postButton of postButtons) {
// 	postButton.addEventListener("click", publishImage);
// }