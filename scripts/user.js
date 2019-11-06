function login_status() {
    let xhttp = new XMLHttpRequest();
    let all_inputs = document.querySelectorAll(".sign_in_inputs");
    let login = all_inputs[0].value;
    let password = all_inputs[1].value;
    let errorWrapper = document.querySelector(".error_wrapper");


    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            if (this.response === 'OK') {
                window.location.replace('index');
            } else {
                errorWrapper.innerHTML = this.response;
            }
        }
    };
    xhttp.open("POST", "ajax?method=login&user=1", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("login=" + login + "&password=" + password);
}

function create_user_status() {
    let xhttp = new XMLHttpRequest();
    let all_inputs = document.querySelectorAll(".create_user_inputs");
    let email = all_inputs[0].value;
    let login = all_inputs[1].value;
    let password = all_inputs[2].value;
    let password_confirmation = all_inputs[3].value;
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
    xhttp.open("POST", "ajax?method=create_user&controller=UsersController", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("password=" + password + "&password_verif=" + password_confirmation + "&login=" + login
        + "&email=" + email + "&profile_pic=" + filename);
}

let createUserButton = document.querySelector(".create_user_submit");
let loginButton = document.querySelector(".sign_in_submit");
let update_login = document.querySelector(".submit_change_login");

if (loginButton !== null) {
    loginButton.addEventListener("click", function () {
        login_status();
    });
}

if (createUserButton !== null) {
    createUserButton.addEventListener("click", function () {
        create_user_status();
    });
}
