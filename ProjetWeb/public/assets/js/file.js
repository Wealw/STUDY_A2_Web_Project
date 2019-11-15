let realButton = document.querySelector("div.file input")
let customButton = document.querySelector("div.file button")
let span = document.querySelector('div.file span.custom_text')

customButton.addEventListener("click", function (e) {
    e.preventDefault()
    realButton.click()
})

realButton.addEventListener('change', function () {
    if (realButton.value) {
        span.innerText = realButton.value.match(/[\/\\]([\w\d\s\.\-\(\)]+)$/)[1];
    } else {
        span.innerText = "Aucun fichier choisi.";
    }
})