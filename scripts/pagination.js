function changePage(data) {
    pageNumber = data.target.childNodes[0];

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200 && this.response == "OK")
            buttonToRemove.remove();
    };
    xhttp.open("GET", "responses?changePageTo=" + pageNumber, true);
    xhttp.send();
}

paginationDiv = document.querySelector(".pagination");
paginationElems = document.querySelectorAll(".pagination p");

paginationElems.forEach(paginationElem => {
    paginationElem.addEventListener("click", changePage);    
});