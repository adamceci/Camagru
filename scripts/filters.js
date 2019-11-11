function cloneToDoc(newImage) {
	newImage.classList.add("hidden")
	newImage.classList.add("to_draw");
	canvasContainer.insertBefore(newImage, canvasContainer.firstChild);
}

function applyOnCanvas(selectedFilter) {
	let newImage = selectedFilter.cloneNode("true");
	cloneToDoc(newImage);
	
	let currentCanvas = document.querySelector("#my_canvas");
	let newCanvas = currentCanvas.cloneNode("true");
	canvasContainer.insertBefore(newCanvas, currentCanvas);
	currentCanvas.remove();
	let ctx = newCanvas.getContext("2d");

	let imagesToDisplay = document.querySelectorAll("#canvas_container img");
	let imagesArray = Array.from(imagesToDisplay);
	console.log(imagesArray.reverse());
	for (let imageToDisplay of imagesArray) {
		// console.log(imageToDisplay);
		ctx.drawImage(imageToDisplay, 0, 0, newCanvas.width, newCanvas.height);
	}
}

function removeFromDoc(srcFilter) {
	let arrayOfImages = document.querySelectorAll("#canvas_container img.to_draw");
	for (let toDelImage of arrayOfImages) {
		if (toDelImage.src == srcFilter) {
			toDelImage.remove();
		}
	}
}

function removeFromCanvas(srcFilter) {
	removeFromDoc(srcFilter);
	// let imgToRemove = document.querySelector("#main img").src = srcFilter;
	// console.log(imgToRemove.src);
}

function border(data) {
	let selectedFilter = data.target;

	if (selectedFilter.classList.contains("selected_filter")) {
		selectedFilter.classList.remove("selected_filter");
		selectedFilter.classList.add("unselected_filter");
		removeFromCanvas(data.target.src);
	}
	else {
        selectedFilter.classList.add("selected_filter");
		selectedFilter.classList.remove("unselected_filter");        
		applyOnCanvas(selectedFilter);
	}
}

// function applyOnCanvas(data) {
// 	let selectedFilter = data["target"];



	// let currentCanvas = document.querySelector("canvas");
	// let selectedFilter = data["target"];

	// if (selectedFilter.classList.contains("selected_filter")) {
	// 	let newCanvas = currentCanvas.cloneNode("true");
	// 	canvasContainer.insertBefore(newCanvas, currentCanvas);
	// 	currentCanvas.classList.add("hidden");
	// 	let ctx = newCanvas.getContext("2d");
	// 	ctx.drawImage(selectedFilter, 0, 0, newCanvas.width, newCanvas.height);
	// }
	// else {

	// }

// }

// let canvas = document.querySelector("#my_canvas");
let baseImage = document.querySelector("#base_img");

let canvasContainer = document.querySelector("#canvas_container");

let filters = document.querySelectorAll(".filter");

for (let filter of filters) {
	filter.addEventListener("click", border);
	// filter.addEventListener("click", applyOnCanvas);
}