function login_status() {
    let xhttp = new XMLHttpRequest();
    let all_inputs = document.querySelectorAll(".sign_in_inputs");
    let login = all_inputs[0].value;
    let password = all_inputs[1].value;
    let errorWrapper = document.querySelector(".error_wrapper");


    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            if (this.response.substr(0, 2) !== 'OK') {
                errorWrapper.innerHTML = this.response.substr(5);
            } else {
                setTimeout(function () {
                    window.location.replace('index');
                }, 5);
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
                setTimeout(function () {
                    window.location.replace('index');
                }, 5);
            } else {
                errorWrapper.innerHTML = this.response;
            }
        }
    };
    xhttp.open("POST", "ajax?method=create_user&controller=UsersController&user=1", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("password=" + password + "&password_verif=" + password_confirmation + "&login=" + login
        + "&email=" + email + "&profile_pic=" + filename);
}

function password_rec_status() {
    let xhttp = new XMLHttpRequest();
    let emailOrLoginInput = document.querySelector(".pass_rec_input");
    let emailOrLogin = emailOrLoginInput.value;
    let errorWrapper = document.querySelector(".error_wrapper");

    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            if (this.response.substr(0, 2) !== 'OK') {
                errorWrapper.innerHTML = this.response.substr(5);
            } else {
                setTimeout(function () {
                    window.location.replace('index');
                }, 5);
            }
        }
    };
    xhttp.open("POST", "ajax?method=password_recovery&user=1", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("login=" + emailOrLogin);
}

function change_password_rec_status() {
    let xhttp = new XMLHttpRequest();
    let passwordsInputs = document.querySelectorAll(".pass_rec_inputs");
    let password = passwordsInputs[0].value;
    let passwordVerif = passwordsInputs[1].value;
    let errorWrapper = document.querySelector(".error_wrapper");
    let url = window.location.toString();
    let toLogin = url.substr(url.indexOf("email="));
    let login = toLogin.substr(toLogin.indexOf('=') + 1,
        toLogin.indexOf('&') - toLogin.indexOf('=') - 1);
    let toHash = url.substr(url.indexOf("hash="));
    let hash = toHash.substr(toHash.indexOf('=') + 1);

    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            if (this.response.substr(0, 2) !== 'OK') {
                errorWrapper.innerHTML = this.response.substr(5);
            } else {
                setTimeout(function () {
                    window.location.replace('index');
                }, 5);
            }
        }
    };
    xhttp.open("POST", "ajax?method=password_recovery_update&user=1&hash=" + hash + "&login=" + login, true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("password=" + password + "&password_verif=" + passwordVerif);
}

let createUserButton = document.querySelector(".create_user_submit");
let loginButton = document.querySelector(".sign_in_submit");
let passwordRecButton = document.querySelector(".pass_rec_submit");
let passwordChangeRecButton = document.querySelector(".submit_change_password_rec");

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

if (passwordRecButton !== null) {
    passwordRecButton.addEventListener("click", function () {
        password_rec_status();
    });
}

if (passwordChangeRecButton !== null) {
    passwordChangeRecButton.addEventListener("click", function () {
        change_password_rec_status();
    });
}