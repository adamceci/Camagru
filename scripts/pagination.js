function changePage(data) {
    let targetPage = data.target;
    let targetPageNb;
    let currentPageNbDiv = document.querySelector(".active");
    if (targetPage.innerHTML == "»")
        targetPageNb = parseInt(currentPageNbDiv.innerHTML) + 1;
    else if (targetPage.innerHTML == "«")
        targetPageNb = parseInt(currentPageNbDiv.innerHTML) - 1;
    else
        targetPageNb = parseInt(targetPage.innerHTML);

    let xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (this.response.substr(-2) == "OK") {
                currentPageNbDiv.classList.remove("active");
                targetPage.classList.add("active");
                window.location.replace("index");
            }
        }
    };
    xhttp.open("GET", "responses?changePageTo=" + targetPageNb.toString(), true);
    xhttp.send();
}

let paginationDiv = document.querySelector(".pagination");
let paginationElems = document.querySelectorAll(".pagination p");

paginationElems.forEach(paginationElem => {
    paginationElem.addEventListener("click", changePage);    
});