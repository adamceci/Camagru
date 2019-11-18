function removeImage(data) {
	let divToRemove = data["path"][2];
	let imageToRemove = divToRemove.querySelector("img").getAttribute("src");
	let xhttp = new XMLHttpRequest();
	
	if (confirm("Are you sure you want to delete this image ?")) {
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				if (this.response == "OK")
					divToRemove.remove();
				else
					errorWrapper.innerHTML = this.response.substr(0, this.response.length - 2);
			}
		};
		xhttp.open("GET", "responses?toDelSrc=" + imageToRemove, true);
		xhttp.send();
	}
}

function publishImage(data) {
	let buttonToRemove = data["target"];
	let imageToPublish = data.target.parentNode.parentNode.lastElementChild.src;
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

let removeButtons = document.querySelectorAll(".remove");
let postButtons = document.querySelectorAll(".post");
let errorWrapper = document.querySelector(".error_wrapper");

if (removeButtons != null) {
	for (let removeButton of removeButtons) {
		removeButton.addEventListener("click", removeImage);
	}
}

if (postButtons != null) {
	for (let postButton of postButtons) {
		postButton.addEventListener("click", publishImage);
	}
}