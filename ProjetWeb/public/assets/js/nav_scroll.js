let header = document.querySelector("header");
let submenu = document.querySelector("div.submenu");
let search = document.querySelector("ul.search")
let prevScrollPos = window.pageYOffset;

window.addEventListener('scroll', function () {
    let currentScrollPos = window.pageYOffset;

    if (prevScrollPos > currentScrollPos) {
        header.style.top = "0";
        submenu.style.height = (window.innerHeight - 72) + "px"
        if (search.style.display === "none") {
            search.style.display = ""
        }
    } else if (prevScrollPos < currentScrollPos) {
        header.style.top = "-72px"
        submenu.style.height = "100vh"
        search.style.display = "none"
    }

    prevScrollPos = window.pageYOffset;
});
