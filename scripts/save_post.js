function saveImage(data) {
	let toPub;

	if (data.target.id == "post_btn")
		toPub = 1;
	else
		toPub = 0;

	let imagesToDisplay = document.querySelectorAll("#canvas_container img");
	let imagesToDisplaySrc = [];

	imagesToDisplay.forEach((node) => imagesToDisplaySrc.push(node.src));
	imagesToDisplaySrc.reverse();

	let myJSON = JSON.stringify({"imagesArray":imagesToDisplaySrc});
	let xhttp = new XMLHttpRequest();

	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200 && this.response.substr(-2) == "OK")
			window.location.replace("montage");
		};
	xhttp.open("POST", "responses", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send("array_images=" + myJSON + "&to_pub=" + toPub);
}

let saveBtn = document.querySelector("#save_btn");
let postBtn = document.querySelector("#post_btn");

saveBtn.addEventListener("click", saveImage);
postBtn.addEventListener("click", saveImage);