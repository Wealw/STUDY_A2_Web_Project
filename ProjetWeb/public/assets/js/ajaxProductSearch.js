let xhr = new XMLHttpRequest();
let searchBar = document.querySelector('.search_bar input');
let tbody = document.querySelector('tbody.product')

searchBar.addEventListener('keyup', function () {

    let value = searchBar.value;
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {

            let data = JSON.parse(xhr.responseText);
            let divProds = document.querySelectorAll('.admin_product');

            console.log(data)

            for (let i = 0; i < divProds.length; i++) {
                divProds[i].parentNode.removeChild(divProds[i])
            }

            for (let j = 0; j < data.length; j++) {
                let tr = document.createElement('tr');
                let td1 = document.createElement('td');
                let td2 = document.createElement('td');
                let td3 = document.createElement('td');
                let td4 = document.createElement('td');
                let td5 = document.createElement('td');
                let link = document.createElement('a');


                console.log(data[j])
                console.log(data[j].Id)

                tr.classList.add('admin_product')

                link.setAttribute('href', 'product/' + data[j].Id+'/edit')
                link.innerText = data[j].productName;
                td3.innerText = data[j].productPrice;
                td5.innerText = data[j].productInventory;
                td4.innerText = data[j].productDescription;
                td2.innerText = data[j].productType;




                td1.appendChild(link);
                tr.appendChild(td1);
                tr.appendChild(td2);
                tr.appendChild(td3);
                tr.appendChild(td4);
                tr.appendChild(td5);
                tbody.appendChild(tr);
            }
        }
    };

    xhr.open('GET', '/admin/product/search/' + value);
    xhr.setRequestHeader('X-Requested-With', 'xmlhttprequest');
    xhr.send();

});