function drawOnVid(selectedFilter) {
	let img = document.createElement("img");
	img.src = selectedFilter.src;
	document.querySelector(".filters_to_display").appendChild(img);
}

function removeFromVid(selectedFilter) {
	let imgsToRemove = document.querySelectorAll(".filters_to_display img");
	
	imgsToRemove.forEach(imgToRemove => {
		if (imgToRemove.src === selectedFilter.src)
			imgToRemove.remove();
	});
}

function border(data) {
	let selectedFilter = data.target;

	if (selectedFilter.classList.contains("unselected_filter")) {
		selectedFilter.classList.add("selected_filter");
		selectedFilter.classList.remove("unselected_filter");
		drawOnVid(selectedFilter);
	}

	else {
		selectedFilter.classList.remove("selected_filter");
		selectedFilter.classList.add("unselected_filter");
		removeFromVid(selectedFilter);
	}

	let screenshotButton = document.querySelector("#screenshot-button");
	let displayScreenshotButton = 0;

	allFilters.forEach(filter => {
		if (filter.classList.contains("selected_filter")) {
			displayScreenshotButton = 1;
			return;
		}
	});
	if (displayScreenshotButton == 1)
		screenshotButton.classList.remove("hidden");
	else
		screenshotButton.classList.add("hidden");
}

let allFilters = document.querySelectorAll(".filter");

if (allFilters.length != 0) {
	for (let filter of allFilters) {
		filter.addEventListener("click", border);
	}
}