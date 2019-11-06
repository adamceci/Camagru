function create_comment_status() {
    let xhttp = new XMLHttpRequest();
    let all_inputs = document.querySelectorAll(".create_user_inputs");
    let email = all_inputs[0].value;
    let login = all_inputs[1].value;
    let picPath = document.querySelector('.create_user_file_input').value;
    let filename = picPath.replace(/^.*\\/, "");
    let errorWrapper = document.querySelector(".error_wrapper");


    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            if (this.response === 'OK') {
                window.location.replace('index');
            }
            errorWrapper.innerHTML = this.response;
        }
    };
    xhttp.open("POST", "ajax?method=create_comment&posts=1", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("password=" + password + "&password_verif=" + password_confirmation + "&login=" + login
        + "&email=" + email + "&profile_pic=" + filename);
}

let createCommentButton = document.querySelector(".sign_in_submit");

if (createCommentButton !== null) {
    createCommentButton.addEventListener("click", function () {
        create_comment_status();
    });
}

