let links = document.querySelectorAll('.likes a')
let xhr = new XMLHttpRequest();

for (let i = 0; i < links.length; i++) {

    links[i].addEventListener('click', function (e) {
        e.preventDefault()

        let link = this
        let span = this.lastElementChild

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                let data = JSON.parse(xhr.responseText)

                console.log(link)
                if (data.action === 0) {
                    link.classList.add('active')
                    span.textContent++
                } else if (data.action === 1) {
                    link.classList.remove('active')
                    span.textContent--
                } else if (data.action === 2) {
                    if (link.nextElementSibling) {
                        link.nextElementSibling.classList.remove('active')
                        link.nextElementSibling.lastElementChild.textContent--
                        link.classList.add('active')
                        link.lastElementChild.textContent++
                    } else {
                        link.previousElementSibling.classList.remove('active')
                        link.previousElementSibling.lastElementChild.textContent--
                        link.classList.add('active')
                        link.lastElementChild.textContent++
                    }
                }
            }
        }
        xhr.open('GET', this.href)
        xhr.setRequestHeader('X-Requested-With', 'xmlhttprequest');
        xhr.send();

    })

}