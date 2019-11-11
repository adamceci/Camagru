function border(data) {
	let current_filter = data["target"];

	if (current_filter.classList.contains("selected_filter")) {
		current_filter.classList.remove("selected_filter");
		current_filter.classList.add("unselected_filter");
	}
	else {
        current_filter.classList.add("selected_filter");
		current_filter.classList.remove("unselected_filter");        
	}
}

let filters = document.querySelectorAll(".filter");

for (let filter of filters) {
	filter.addEventListener("click", border);
}