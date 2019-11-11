document.addEventListener("DOMContentLoaded", function () {

    let carousel = new Carousel(document.querySelector("#carousel1"), {
        slidesToScroll: 2,
        slidesVisible: 3,
        pagination: true,
        infinite: true
    })

    window.setInterval(function () {
        carousel.goToItem(carousel.currentItem + carousel.options.slidesToScroll)
    }, 6000)

})

class Carousel {

    /**
     *
     * @param {HTMLElement} element
     * @param {Object} options
     * @param {Object} options.slidesToScroll Nombre d'élément à faire défiler
     * @param {Object} options.slidesVisible Nombre d'élément à afficher
     * @param {boolean} [options.loop=false] Doit-on boucler en fin de carousel
     * @param {boolean} [options.pagination=false]
     * @param {boolean} [options.infinite=false]
     */
    constructor(element, options = {}) {
        this.element = element;
        this.options = Object.assign({}, {
            slidesToScroll: 1,
            slidesVisible: 1,
            loop: false,
            pagination: true,
            infinite: false
        }, options);
        let children = [].slice.call(element.children)
        this.isMobile = false;
        this.currentItem = 0;
        this.root = this.createDivWithClass('carousel')
        this.container = this.createDivWithClass('carousel__container')
        this.root.setAttribute('tabindex', '0');
        this.root.appendChild(this.container)
        this.element.appendChild(this.root)
        this.moveCallBacks = []
        this.offset = 0
        this.items = children.map((child) => {
            let item = this.createDivWithClass('carousel__item')
            item.appendChild(child)
            return item
        })
        if (this.options.infinite === true) {
            this.offset = this.options.slidesVisible + this.options.slidesToScroll
            this.items = [
                ...this.items.slice(this.items.length - this.offset).map(item => item.cloneNode(true)),
                ...this.items,
                ...this.items.slice(0, this.offset).map(item => item.cloneNode(true)),
            ]
            this.goToItem(this.offset, false)
        }
        this.items.forEach(item => this.container.appendChild(item))
        this.setStyle()
        this.createNavigation()
        if (this.options.pagination === true) {
            this.createPagination()
        }
        this.moveCallBacks.forEach(cb => cb(this.currentItem))
        this.onWindowResize();

        window.addEventListener('resize', this.onWindowResize.bind(this));
        this.root.addEventListener('keyup', e => {
            if (e.key === 'ArrowRight' || e.key === 'Right') {
                this.next()
            } else if (e.key === 'ArrowLeft' || e.key === 'Left') {
                this.prev()
            }
        })
        if (this.options.infinite) {
            this.container.addEventListener('transitionend', this.resetInfinite.bind(this))
        }

    }

    /**
     * Applique les bonnes dimensions aux éléments du carousel
     */
    setStyle() {
        let ratio = this.items.length / this.options.slidesVisible
        this.container.style.width = (ratio * 100) + "%"
        this.items.forEach(item => item.style.width = ((100 / this.options.slidesVisible) / ratio) + "%")
    }

    createNavigation() {
        let prevButton = this.createDivWithClass('carousel__prev')
        let nextButton = this.createDivWithClass('carousel__next')
        let iPrev = document.createElement('i')
        let iNext = document.createElement('i')
        iPrev.classList.add('fas')
        iNext.classList.add('fas')
        iPrev.classList.add('fa-chevron-left')
        iNext.classList.add('fa-chevron-right')

        prevButton.appendChild(iPrev)
        nextButton.appendChild(iNext)
        this.root.appendChild(prevButton)
        this.root.appendChild(nextButton)

        prevButton.addEventListener('click', this.prev.bind(this))
        nextButton.addEventListener('click', this.next.bind(this))

        if (this.options.loop === true) {
            return
        }

        this.onMove(index => {
            if (index === 0) {
                prevButton.classList.add('hidden')
            } else {
                prevButton.classList.remove('hidden')
            }
            if (this.items[this.currentItem + this.options.slidesVisible] === undefined) {
                nextButton.classList.add('hidden')
            } else {
                nextButton.classList.remove('hidden')
            }
        })
    }

    /**
     * Créé la pagination
     */
    createPagination() {
        let pagination = this.createDivWithClass('carousel__pagination')
        let buttons = []
        this.root.appendChild(pagination)
        for (let i = 0; i < (this.items.length - 2 * this.offset); i = i + this.options.slidesToScroll) {
            let button = this.createDivWithClass('carousel__pagination__button')
            button.addEventListener('click', () => this.goToItem(i + this.offset))
            pagination.appendChild(button)
            buttons.push(button)
        }
        this.onMove(index => {
            let count = this.items.length - 2 * this.offset
            let activeButton = buttons[Math.floor(((index - this.offset) % count) / this.options.slidesToScroll)]
            if (activeButton) {
                buttons.forEach(button => button.classList.remove('active'))
                activeButton.classList.add('active')
            }
        })
    }

    next() {
        this.goToItem(this.currentItem + this.options.slidesToScroll)
    }

    prev() {
        this.goToItem(this.currentItem - this.options.slidesToScroll)
    }

    /**
     * Deplace le carousel vers l'élément ciblé
     * @param {number} index
     * @param animation
     */
    goToItem(index, animation = true) {
        if (index < 0) {
            if (this.options.loop) {
                index = this.items.length - this.slidesVisible
            } else {
                return
            }
        } else if (index >= this.items.length || (this.items[this.currentItem + this.slidesVisible] === undefined && index > this.currentItem)) {
            if (this.options.loop) {
                index = 0
            } else {
                return
            }
        }

        let translateX = index * -100 / this.items.length
        if (animation === false) {
            this.container.style.transition = 'none'
        }
        this.container.style.transform = 'translate3D( ' + translateX + '%, 0, 0)'
        this.container.offsetHeight // Force le repaint
        if (animation === false) {
            this.container.style.transition = ''
        }
        this.currentItem = index

        this.moveCallBacks.forEach(cb => cb(index))
    }

    resetInfinite() {
        if (this.currentItem <= this.options.slidesToScroll) {
            this.goToItem(this.currentItem + (this.items.length - 2 * this.offset), false)
        } else if (this.currentItem >= this.items.length - this.offset) {
            this.goToItem(this.currentItem - (this.items.length - 2 * this.offset), false)
        }
    }

    onMove(cb) {
        this.moveCallBacks.push(cb)
    }

    onWindowResize () {
        let mobile = window.innerWidth < 1440 ;
        if (mobile !== this.isMobile) {
            this.isMobile = mobile
            this.setStyle()
            this.moveCallBacks.forEach(cb => cb(this.currentItem))
        }
    }

    /**
     *
     * @param className
     * @return {HTMLElement}
     */
    createDivWithClass (className) {
        let div = document.createElement('div')
        div.setAttribute('class', className)
        return div
    }

    get slidesToScroll () {
        return this.isMobile ? 1 : this.options.slidesToScroll
    }

    get slidesVisible () {
        return this.isMobile ? 1 : this.options.slidesVisible
    }

}
