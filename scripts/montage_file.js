let canvas = document.querySelector("#my_canvas");
let ctx = canvas.getContext("2d");
let img = document.querySelector("#base_img");

ctx.drawImage(img, 0, 0, canvas.width, canvas.height);