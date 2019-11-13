function cloneToDoc(newImage) {
	newImage.classList.add("hidden")
	newImage.classList.add("to_draw");
	canvasContainer.insertBefore(newImage, canvasContainer.firstChild);
}

function drawCanvas() {
	let currentCanvas = document.querySelector("#my_canvas");
	let newCanvas = currentCanvas.cloneNode("true");
	let ctx = newCanvas.getContext("2d");

	canvasContainer.insertBefore(newCanvas, currentCanvas);
	currentCanvas.remove();

	let imagesToDisplay = document.querySelectorAll("#canvas_container img");
	let imagesArray = [...imagesToDisplay].reverse();

	for (let imageToDisplay of imagesArray) {
		ctx.drawImage(imageToDisplay, 0, 0, newCanvas.width, newCanvas.height);
	}
}

function applyOnCanvas(selectedFilter) {
	let newImage = selectedFilter.cloneNode("true");
	cloneToDoc(newImage);
	drawCanvas();
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
	drawCanvas();
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

let baseImage = document.querySelector("#base_img");
let canvasContainer = document.querySelector("#canvas_container");
let filters = document.querySelectorAll(".filter");

for (let filter of filters) {
	filter.addEventListener("click", border);
}