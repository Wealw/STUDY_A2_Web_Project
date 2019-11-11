let icon = document.querySelector("div.relative div.icon_container");
let profile = icon.nextElementSibling
let visible = 0

icon.addEventListener("mouseover", function () {
    profile.style.display = "block"
})

icon.addEventListener("mouseout", function () {
    if (visible === 0) {
        profile.style.display = "none";
    } else if (visible === 1) {
        profile.style.display = "block";
    }
})

icon.addEventListener("click", function () {
    if (visible === 1) {
        visible = 0
    } else if (visible === 0) {
        visible = 1
    }
})