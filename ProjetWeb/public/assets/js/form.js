let inputs = document.querySelectorAll('div.group div input')
let textareas = document.querySelectorAll('div.group div textarea')

for (let i = 0; i<inputs.length; i++) {

    inputs[i].addEventListener('focus', function () {

        let group = this.parentNode.parentNode
        if (group.classList.contains('off')) {
            group.classList.replace('off', 'on')
        }

    })

    inputs[i].addEventListener('blur', function () {

        let content = this.value
        let group = this.parentNode.parentNode

        if (group.classList.contains('on') && content.length === 0) {
            group.classList.replace('on', 'off')
        }

    })

    let content = inputs[i].value
    if (content.length !== 0) {
        inputs[i].parentNode.parentNode.classList.replace('off', 'on')
    }

}

for (let j = 0; j<textareas.length; j++) {

    textareas[j].addEventListener('focus', function () {

        let group = this.parentNode.parentNode
        if (group.classList.contains('off')) {
            group.classList.replace('off', 'on')
        }

    })

    textareas[j].addEventListener('blur', function () {

        let content = this.value
        let group = this.parentNode.parentNode

        if (group.classList.contains('on') && content.length === 0) {
            group.classList.replace('on', 'off')
        }

    })

    let content = textareas[j].value
    if (content.length !== 0) {
        textareas[j].parentNode.parentNode.classList.replace('off', 'on')
    }

}