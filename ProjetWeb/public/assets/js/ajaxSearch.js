let xhr = new XMLHttpRequest()
let searchBar = document.querySelector('.search_bar input')
let tbody = document.querySelector('tbody.event')
let actions = document.querySelector('tr.admin_event td.row form').parentNode

searchBar.addEventListener('keyup', function () {

    let value = searchBar.value

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let data = JSON.parse(xhr.responseText)
            let divEvents = document.querySelectorAll('.admin_event');

            for (let i = 0; i < divEvents.length; i++) {
                divEvents[i].parentNode.removeChild(divEvents[i])
            }

            for (let j = 0; j < data.length; j++) {
                let tr = document.createElement('tr')
                let td1 = document.createElement('td')
                let td2 = document.createElement('td')
                let td3 = document.createElement('td')
                let td4 = document.createElement('td')
                let link = document.createElement('a')

                console.log(data[0])

                tr.classList.add('admin_event')
                link.setAttribute('href', '/events/' + data[j].id)
                link.innerText = data[j].eventName
                td2.innerText = data[j].eventDate
                td3.innerText = data[j].createdAt
                td4.innerText = data[j].createdBy

                let tdActions

                if (data[j].isVisible) {
                    console.log('yay')
                    tdActions = actions.cloneNode(true);
                    tdActions.firstElementChild.setAttribute('href', '/admin/events/edit/' + data[j].id)
                    let form = tdActions.firstElementChild.nextElementSibling.nextElementSibling

                } else {
                    tdActions = document.createElement('td')
                    let button = document.createElement('button')
                    button.setAttribute('disabled', true)
                    button.classList.add('disabled')
                    button.innerText = 'SUPPRIMÉ'

                    tdActions.appendChild(button)
                }

                td1.appendChild(link)
                tr.appendChild(td1)
                tr.appendChild(td2)
                tr.appendChild(td3)
                tr.appendChild(td4)
                tr.appendChild(tdActions)
                tbody.appendChild(tr)
            }

        }
    }
    xhr.open('GET', '/admin/events/search/' + value)
    xhr.setRequestHeader('X-Requested-With', 'xmlhttprequest');
    xhr.send();

})