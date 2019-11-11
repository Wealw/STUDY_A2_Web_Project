let searchBar = document.querySelector("form.search input")
let xhr = new XMLHttpRequest()
let ul = document.querySelector('ul.search')
let data = []
let liArray = []
let increment = 0
let value = ""

xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
        data = JSON.parse(xhr.responseText)
    }
}
xhr.open('GET', 'autocomplete.json')
xhr.setRequestHeader('X-Requested-With', 'xmlhttprequest')
xhr.send()

searchBar.addEventListener('input', function () {

    let content = this.value.toLowerCase()
    let regex = RegExp("^" + content)
    let li = document.querySelectorAll("ul.search li")

    for (let i = 0; i < li.length; i++) {
        liArray.pop()
        li[i].parentNode.removeChild(li[i])
    }

    if (content.length > 0) {
        for (let i = 0; i < data.length; i++) {
            data[i].words.forEach(function (word) {
                if (regex.test(word)) {
                    let li = document.createElement('li')
                    let link = document.createElement('a')
                    li.classList.add('search')
                    link.innerText = word.toUpperCase()
                    li.appendChild(link)
                    ul.appendChild(li)

                    liArray.push(li)

                }
            })
        }
    }
})

searchBar.addEventListener('focus', function () {
    increment = 0
    searchBar.addEventListener('keyup', function (e) {
        if (e.key !== 'ArrowDown' && e.key !== 'ArrowUp') {
            value = this.value
            increment = 0
        }
        if ((e.key === 'ArrowDown' || e.key === 'Down') && liArray.length > increment) {
            this.value = liArray[increment].innerText
            liArray[increment].style.backgroundColor = "#C62828"
            if (increment !== 0) {
                liArray[increment - 1].style.backgroundColor = "#D23F2F"
            }
            increment += 1;
        }
        if ((e.key === 'ArrowUp' || e.key === 'Up')) {
            if (increment !== 0) {
                increment--
            }
            if (increment > 0) {
                this.value = liArray[increment - 1].innerText
                liArray[increment - 1].style.backgroundColor = "#C62828"
            } else if (increment === 0) {
                this.value = value
            }
            liArray[increment].style.backgroundColor = "#D23F2F"
        }
    })
})