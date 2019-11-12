let xhr = new XMLHttpRequest();
let searchBar = document.querySelector('.search_bar input');

searchBar.addEventListener('keyup', function () {

    let value = searchBar.value

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let data = JSON.parse(xhr.responseText)

            let divEvents = document.querySelectorAll('.admin_event');

            for (let i = 0; i < divEvents.length; i++) {
                divEvents[i].parentNode.removeChild(divEvents[i])
            }

            console.log(data[0])

        }
    }
    xhr.open('GET', '/admin/events/search/' + value)
    xhr.setRequestHeader('X-Requested-With', 'xmlhttprequest');
    xhr.send();

})