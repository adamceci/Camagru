function create_comment_status() {
    let xhttp = new XMLHttpRequest();
    let message_box = document.querySelector(".message");
    let message = message_box.value;
    let errorWrapper = document.querySelector(".error_wrapper");


    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            if (this.response === 'OK') {
            }
            errorWrapper.innerHTML = this.response;
        }
    };
    console.log(message);
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

