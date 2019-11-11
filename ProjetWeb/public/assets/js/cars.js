let grid = document.querySelector("div.grid3")
let item = document.querySelector("div.grid_item")

/*xhr.onreadystatechange = function () {
  if (xhr.readyState === 4 && xhr.status === 200) {
      let data = JSON.parse(xhr.responseText)
      console.log(data.length)


      let length = 6

      if (data.length < 6) {
          length = data.length
      }

      for (let i = 0; i < length; i++) {
          let container = document.createElement('div')
          let head = document.createElement('div')
          let body = document.createElement('div')
          let foot = document.createElement('div')
          let image = document.createElement('img')
          let p = document.createElement('p')

          container.classList.add('grid_item')
          head.classList.add('head')
          body.classList.add('body')
          foot.classList.add('foot')

          head.innerText = data[i].marque.toUpperCase() + " - " + data[i].modele
          image.setAttribute('src', data[i].image_url)
          p.innerHTML = "Type de voiture : " + data[i].type + "<br>Moteur : " + data[i].moteur

          foot.appendChild(p)
          body.appendChild(image)
          container.appendChild(head)
          container.appendChild(body)
          container.appendChild(foot)
          grid.appendChild(container)
      }

  }
}
xhr.open('GET', 'cars.json')
xhr.setRequestHeader('X-Requested-With', 'xmlhttprequest')
xhr.send()*/

class LazyLoading {

    constructor(gridContainer, item) {
        this.xhr = new window.XMLHttpRequest()
        this.gridContainer = gridContainer
        this.item = item
        this.start = 6
        this.end = 9

        const options = {
            root: null,
            rootMargin: '0px',
            threshold: 0.2
        }

        this.observer = new IntersectionObserver(this.handleIntersect, options)

    }

    setObservable() {
        lazy.footer = document.querySelector('footer')
        lazy.observer.observe(lazy.footer)
    }

    handleIntersect(entries, observer) {
        entries.forEach(function (entry) {
            if (entry.intersectionRatio > 0.2) {
                lazy.getCars(lazy.start, lazy.end)
                lazy.start += 3
                lazy.end += 3
            } else {
            }
        })
    }

    getCars(start, end) {
        console.log("appelé")
        this.xhr.onreadystatechange = function () {
            if (this.xhr.readyState === 4 && this.xhr.status === 200) {

                let data = JSON.parse(this.xhr.responseText)

                for (let i = start; i < end; i++) {
                    if (data[i]) {
                        let clone = this.item.cloneNode(true)
                        let head = clone.querySelector("div.head")
                        let body = clone.querySelector("div.body")
                        let foot = clone.querySelector("div.foot")
                        let img = body.firstElementChild
                        let link = foot.querySelector('a')

                        clone.style.display = "block"
                        head.innerText = data[i].marque.toUpperCase() + " - " + data[i].modele
                        img.setAttribute('src', data[i].image_url)
                        foot.firstElementChild.innerHTML = "Type de voiture : " + data[i].type + "<br>Moteur : " + data[i].moteur
                        link.setAttribute('href', "car.php?modele=" + (i+1))
                        this.gridContainer.appendChild(clone)
                    } else {
                        lazy.observer.unobserve(lazy.footer)
                    }
                }
            }
        }.bind(this)
        this.xhr.open('GET', 'cars.json')
        this.xhr.setRequestHeader('X-Requested-With', 'xmlhttprequest')
        this.xhr.send()
    }

}

let lazy = new LazyLoading(grid, item)
window.setTimeout(lazy.setObservable, 200)
