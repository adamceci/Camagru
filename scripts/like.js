function create_comment_status() {
    let xhttp = new XMLHttpRequest();
    let post_img = document.querySelector(".like_post").className;

    console.log(post_img);

    // xhttp.onreadystatechange = function() {
    //     if (this.readyState === 4 && this.status === 200) {
    //         if (this.response.substr(0,2) === "OK") {
    //             let loginNode = document.createElement("p");
    //             let loginTextNode = document.createTextNode(login);
    //             let commentNode = document.createElement("p");
    //             let commentTextNode = document.createTextNode(this.response.substr(2));
    //             loginNode.className = 'poster';
    //             commentNode.className = 'comment';
    //             loginNode.appendChild(loginTextNode);
    //             commentNode.appendChild(commentTextNode);
    //             commentWrapper.appendChild(loginNode);
    //             commentWrapper.appendChild(commentNode);
    //             textarea.value = '';
    //         } else {
    //             errorWrapper.innerHTML = this.response.substr(5);
    //         }
    //     }
    // };
    // xhttp.open("POST", "ajax?method=create_like&posts=1", true);
    // xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // xhttp.send("post_img=" + post_img);
}

let createLikeButton = document.querySelector(".like_post");

if (createLikeButton !== null) {
    createLikeButton.addEventListener("click", function () {
        create_comment_status();
    });
}

