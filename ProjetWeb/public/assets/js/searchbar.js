let input = document.querySelector("form.search input")
let div = document.querySelector("div.search")

let inputLittle = document.querySelector("div.submenu div form input")

input.addEventListener("focus", function () {
    div.classList.replace("inactive", "active")
})

input.addEventListener("blur", function () {
    div.classList.replace("active", "inactive")
})