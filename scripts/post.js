// function removeImage(data) {
// 	let divToRemove = data["path"][2];
// 	let imageToRemove = divToRemove.querySelector("img").getAttribute("src");
// 	let xhttp = new XMLHttpRequest();
	
// 	if (confirm("Are you sure you want to delete this image ?")) {
// 		xhttp.onreadystatechange = function() {
// 			if (this.readyState == 4 && this.status == 200 && this.response == "OK")
// 				divToRemove.remove();
// 		};
// 		xhttp.open("GET", "assets/ajax_responses/responses.php?toDelSrc=" + imageToRemove, true);
// 		xhttp.send();
// 	}
// }

// function publishImage(data) {
// 	let buttonToRemove = data["path"][1];
// 	let imageToPublish = data["path"][2]["children"][2]["src"];
// 	let xhttp = new XMLHttpRequest();

// 	if (confirm("Are you sure you want to post this image ?")) {
// 		xhttp.onreadystatechange = function() {
// 			if (this.readyState == 4 && this.status == 200 && this.response == "OK")
// 				buttonToRemove.remove();
// 		};
// 		console.log(imageToPublish);
// 		xhttp.open("GET", "assets/ajax_responses/responses.php?toPubSrc=" + imageToPublish, true);
// 		xhttp.send();
// 	}
// }

// let removeButtons;
// let postButtons;

// removeButtons = document.querySelectorAll(".remove");
// postButtons = document.querySelectorAll(".post");


// if (removeButtons != null) {
// 	for (let removeButton of removeButtons) {
// 		removeButton.addEventListener("click", removeImage);
// 	}
// }

// if (postButtons != null) {
// 	for (let postButton of postButtons) {
// 		postButton.addEventListener("click", publishImage);
// 	}
// }


function saveImage() {
	let img = document.querySelector("#base_img");
	let imagesToDisplay = document.querySelectorAll("#canvas_container img");
	let imagesToDisplaySrc = [];
	
	imagesToDisplay.forEach((node) => imagesToDisplaySrc.push(node.src));
	imagesToDisplaySrc.reverse();

	let myJSON = JSON.stringify({"imagesArray":imagesToDisplaySrc});
	let xhttp = new XMLHttpRequest();
	
	let resp;
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200)
			resp = JSON.parse(this.response);
			// console.log(resp);
		};
	xhttp.open("POST", "responses", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send("array_images=" + myJSON);
}

let saveBtn = document.querySelector("#save_btn");
let postBtn = document.querySelector("#post_btn");

saveBtn.addEventListener("click", saveImage);