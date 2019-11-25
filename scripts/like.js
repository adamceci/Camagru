function click_like(likeImg) {
    if (likeImg.offsetWidth < 30) {
        likeImg.style.width = (likeImg.offsetWidth + 1) + "px";
    }
}



function create_like_status(post_info_div) {
    let xhttp = new XMLHttpRequest();
    let post_info = post_info_div.querySelector(".like_post");
    let post_img = post_info.className;
    let textLike = post_info.innerHTML;
    let post_img_id = post_img.substr(post_img.indexOf("post_id_")).substr(8);
    let nbLikes = textLike;
    let errorWrapper = document.querySelector('.error_wrapper');
    let likeImg = post_info_div.querySelector(".post_index_heart");

    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            if (this.response.substr(0,2) === "OK") {
                if (this.response.substr(2, 4) === '+1') {
                    nbLikesInt = parseInt(nbLikes);
                    nbLikesInt += 1;
                    likeImg.classList.add('animate');
                    likeImg.src = "./assets/imgs/heart_fill.png";
                    if (nbLikesInt > 1)
                        post_info.innerHTML = nbLikesInt;
                    else
                        post_info.innerHTML = nbLikesInt;
                } else {
                    nbLikesInt = parseInt(nbLikes);
                    nbLikesInt -= 1;
                    if (likeImg.classList.contains('animate')) {
                        likeImg.classList.remove('animate');
                    }
                    likeImg.src = "./assets/imgs/heart.png";
                    if (nbLikesInt > 1)
                        post_info.innerHTML = nbLikesInt;
                    else
                        post_info.innerHTML = nbLikesInt;
                }
            } else {
                errorWrapper.innerHTML = this.response.substr(5);
            }
        }
    };
    xhttp.open("POST", "ajax?method=create_like&posts=1", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("post_img=" + post_img_id);
}

let createLikeButtons = document.querySelectorAll(".like_wrapper");

if (createLikeButtons !== null) {
    createLikeButtons.forEach(function (createLikeButton) {
        createLikeButton.addEventListener("click", function () {
            create_like_status(this);

        });
    });
}

