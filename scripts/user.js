function status(data) {
    let xhttp = new XMLHttpRequest();
    let all_inputs = document.querySelectorAll(".sign_in_inputs");
    let login = all_inputs[0].value;
    let password = all_inputs[1].value;
    let errorWrapper = document.querySelector(".error_wrapper");


    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200)
            errorWrapper.innerHTML = this.response;
    };
    xhttp.open("POST", "ajax?method=login", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("login=" + login + "&password=" + password);

}

document.querySelector(".sign_in_submit").addEventListener("click", function () {
    status();
});

