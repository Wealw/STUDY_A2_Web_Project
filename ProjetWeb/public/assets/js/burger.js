let burger = document.querySelector("div.burger");
let line = document.querySelectorAll("div.line");

burger.addEventListener('click', function () {

    let submenu = this.nextElementSibling;

    if (submenu.classList.contains('display_none')) {
        submenu.classList.remove('display_none');
        submenu.style.right = '0';
        line[0].style.display = "none";
        line[1].classList.add("active1");
        line[2].classList.add("active2");
    } else {
        submenu.classList.add('display_none');
        submenu.style.right = '-312px';
        line[0].style.display = "block";
        line[1].classList.remove("active1");
        line[2].classList.remove("active2");
    }

});