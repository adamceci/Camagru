function displayButtons(data) {
	// let str = i;
	// let concat = "img".concat(str);
	// console.log(concat);
	// let img = document.querySelector("img");

	// let remove = document.createElement('div');
	// let post = document.createElement('div');
	// remove.className = "remove_button";
	// post.className = "post_button";
	// remove.innerHTML = "";
	// console.log(data["path"][0]);
	// data["path"][0].appendChild(remove);
	// console.log(data["path"][0]);
	
	// data["path"][0].appendChild();
	// console.log(data["path"][0]);
	// img.style.border = "thick solid #0000FF";
    // document.querySelectorAll("img").style.border = "thick solid #0000FF";
    // document.getElementById("").style.border = "thick solid #0000FF";
}

function hideButtons() {
	// let img = document.querySelector("img");
	// console.log(img);
	// img.style.border = "none";
    // document.getElementById("img"i).style.border = "none";
}

let images = document.querySelectorAll("img");
for (let image of images) {
    image.addEventListener("mouseover", displayButtons);
    image.addEventListener("mouseout", hideButtons);
}