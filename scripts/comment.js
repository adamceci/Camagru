function create_comment_status() {
    let xhttp = new XMLHttpRequest();
    let message_box = document.querySelector(".message");
    let message = message_box.value;
    let errorWrapper = document.querySelector(".error_wrapper");
    let commentWrapper = document.querySelector('.comments_wrapper');
    let loginWrapper = document.querySelector(".current_user_header");
    let textarea = document.querySelector('.message');
    let noComments = document.querySelector('.no_comments');

    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            if (this.response.substr(0,2) === "OK" && loginWrapper !== null) {
                let login = loginWrapper.innerHTML;
                let loginNode = document.createElement("p");
                let loginTextNode = document.createTextNode(login);
                let commentNode = document.createElement("p");
                let commentTextNode = document.createTextNode(this.response.substr(2));
                let imgNode = document.createElement("img");

                loginNode.className = 'poster';
                commentNode.className = 'comment';
                imgNode.className = 'comment_profile_pic';
                imgNode.src = document.querySelector('.profile_pic_header').src;
                if (noComments !== null)
                    noComments.remove();
                commentNode.prepend(commentTextNode);
                loginNode.prepend(loginTextNode);
                commentWrapper.prepend(commentNode);
                commentWrapper.prepend(loginNode);
                commentWrapper.prepend(imgNode);
                textarea.value = '';
            } else {
                errorWrapper.innerHTML = this.response.substr(5);
            }
        }
    };
    xhttp.open("POST", "ajax?method=create_comment&posts=1", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("message=" + message);
}

let createCommentButton = document.querySelector(".create_comment_submit");

if (createCommentButton !== null) {
    createCommentButton.addEventListener("click", function () {
        create_comment_status();
    });
}

