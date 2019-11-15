let canvas = document.querySelector("#my_canvas");
let ctx = canvas.getContext("2d");
let img = document.querySelector("#base_img");

if (img.complete)
    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
else {
    img.onload = function () {
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
    }
}
