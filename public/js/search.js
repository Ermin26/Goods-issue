let allBillsResults = document.getElementById('allBills');
let unPayedBills = document.getElementById('unPayedBills');
let allBillsTable = document.querySelector('#table tbody');
let notPayedTable = document.querySelector('#notPayedTable tbody');
let userName = document.getElementById('userName');
let buyedProductsDiv = document.getElementById('qtyBuyedProducts');
let btns = document.getElementById('searchBtns');
let emptyDiv = document.getElementById('empty');

document.getElementById('searchUser').addEventListener('submit', function(event) {
event.preventDefault();

let username = document.getElementById('username').value;
let product = document.getElementById('product').value;
    allBillsTable.innerHTML ="";
    notPayedTable.innerHTML ="";
    btns.style.display = "none";

fetchData(`{{ route('searchUser') }}`, { username: username, product: product });

});

function fetchData(url, params) {
    let tbody = document.querySelector('#tableProducts tbody');
    tbody.innerHTML = '';
    allBillsResults.style.display = "none";
    buyedProductsDiv.style.display = "none";
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(params)
    })
    .then(response => response.json())
    .then(data => {
        if(data.bills && data.bills.length > 0  && !data.products){
            searchedName(data, tbody, allBillsTable, allBillsResults,notPayedTable, buyedProductsDiv,btns);
            notPayedBills(data)
        }
        else if(data.bills && data.bills.length > 0 && data.products && data.products.length > 0){
            searchedNameAndProduct(data, tbody, allBillsTable, allBillsResults,notPayedTable, buyedProductsDiv,btns);
            notPayedBills(data)
        }
        else if(data.products && data.products.length > 0){
            searchedProduct(data,tbody)
        }
        else if(data.productsSummary && data.productsSummary.length > 0){
            allBuyedProducts(data, tbody);
        }
        else{
            emptyDiv.style.display = 'block';
            emptyDiv.innerHTML = "";
            let h1 = document.createElement('h1');
            let h2 = document.createElement('h2');
            h1.innerHTML = "Iskani kupec ne obstaja!";
            h2.innerHTML = "Preverite upisane parametre in poskusite še enkrat.";
            emptyDiv.appendChild(h1);
            emptyDiv.appendChild(h2);
            emptyDiv.style.backgroundColor = '#D16161';
        }
    })
.catch(error => console.error('Error:', error));
}

function searchedNameAndProduct(data, tbody, allBillsTable, allBillsResults,notPayedTable, buyedProductsDiv,btns){
    btns.style.display = 'block';
    emptyDiv.style.display = 'none';
        userName.textContent = data.bills[0].buyer;
        buyedProductsDiv.style.display = 'block';
        // Add data for searched product
        let rows = 1;
        data.products.forEach(product => {
            let row = tbody.insertRow();
            let soldDate;
            data.bills.forEach(bills => {
                if(bills.id === product.bills_id){
                    let sold_Date = bills.sold_date.split(' ');
                    soldDate = sold_Date[0];
                }
            })
            row.insertCell(0).textContent = rows;
            row.insertCell(1).textContent = product.name;
            row.insertCell(2).textContent = soldDate;

            rows++;
        });
        data.bills.forEach(bills => {
            let row = allBillsTable.insertRow();
            let soldDate = bills.sold_date.split(' ');
                    let date = soldDate[0];
                    row.insertCell(0);
                    row.insertCell(1);
                    row.insertCell(2);
                    row.insertCell(3).textContent = bills.month;
                    row.insertCell(4).textContent = bills.kt;
                    row.insertCell(5);
                    row.insertCell(6);
                    row.insertCell(7).textContent = date;
                    row.insertCell(8).textContent = bills.published;
                    row.insertCell(9).textContent = bills.id;
            data.allProducts.forEach(product => {
                if(bills.id === product.bills_id){
                    let imageUrl = product.firstOfWeek === 1 ? '{{ asset('img/payed.jpg') }}' : '{{ asset('img/notPay.jpg') }}';
                    let img = document.createElement('img');
                    img.src = imageUrl;
                    let payedUrl = bills.payed === 1 ? '{{ asset('img/payed.jpg') }}' : '{{asset('img/notPay.jpg')}}';
                    let payedImg = document.createElement('img');
                    let name = document.createElement('span');
                    let qty = document.createElement('span');
                    let total = document.createElement('span');
                    name.textContent = product.name;
                    qty.textContent = product.qty;
                    total.textContent = product.total.toFixed(2);
                    payedImg.src = payedUrl;
                        row.cells[0].appendChild(name);
                        row.cells[1].appendChild(qty);
                        row.cells[2].appendChild(total);
                        row.cells[5].appendChild(img);
                        row.cells[6].appendChild(payedImg);
                        }
                    });

        });
            allBillsResults.style.display="block";
            userName.textContent= data.bills[0].buyer + " " + "skupaj " + data.allBills  + " račun/ov.";
    }

function searchedName(data, tbody, allBillsTable, allBillsResults,notPayedTable, buyedProductsDiv,btns){
        allBillsTable.innerHTML ="";
        notPayedTable.innerHTML ="";
        btns.style.display="block";
        data.bills.forEach(bills => {
            let row = allBillsTable.insertRow();
            let soldDate = bills.sold_date.split(' ');
                    let date = soldDate[0];
                    row.insertCell(0);
                    row.insertCell(1);
                    row.insertCell(2);
                    row.insertCell(3).textContent = bills.month;
                    row.insertCell(4).textContent = bills.kt;
                    row.insertCell(5);
                    row.insertCell(6);
                    row.insertCell(7).textContent = date;
                    row.insertCell(8).textContent = bills.published;
                    row.insertCell(9).textContent = bills.id;
            data.allProducts.forEach(product => {
                if(bills.id === product.bills_id){
                    let imageUrl = product.firstOfWeek === 1 ? '{{ asset('img/payed.jpg') }}' : '{{ asset('img/notPay.jpg') }}';
                    let img = document.createElement('img');
                    img.src = imageUrl;
                    let payedUrl = bills.payed === 1 ? '{{ asset('img/payed.jpg') }}' : '{{asset('img/notPay.jpg')}}';
                    let payedImg = document.createElement('img');
                    let name = document.createElement('span');
                    let qty = document.createElement('span');
                    let total = document.createElement('span');
                    name.textContent = product.name;
                    qty.textContent = product.qty;
                    total.textContent = product.total.toFixed(2);
                    payedImg.src = payedUrl;
                        row.cells[0].appendChild(name);
                        row.cells[1].appendChild(qty);
                        row.cells[2].appendChild(total);
                        row.cells[5].appendChild(img);
                        row.cells[6].appendChild(payedImg);
                        }
                    });

        });
            allBillsResults.style.display="block";
            userName.textContent= data.bills[0].buyer + " " + "skupaj " + data.allBills  + " račun/ov.";
}
function notPayedBills(data) {
    let notPayedTable = document.querySelector('#notPayedTable tbody')
    document.getElementById('notPayedUserName').textContent= data.bills[0].buyer;
    data.bills.forEach(bills => {
        let link = document.createElement('a');
        let url = `/all/edit/${bills.id}`;
        link.classList.add('btn', 'btn-warning');
        link.innerHTML = "Uredi";
        link.href = url;
        let soldDate = bills.sold_date.split(' ');
        let date = soldDate[0];
        if(bills.payed === 0){
            let row = notPayedTable.insertRow();
            row.insertCell(0)
            row.insertCell(1)
            row.insertCell(2)
            row.insertCell(3).textContent = bills.month;
            row.insertCell(4).textContent = bills.kt;
            row.insertCell(5).textContent = date;
            row.insertCell(6).textContent = bills.published;
            row.insertCell(7);
            row.cells[7].appendChild(link); ;
            data.allProducts.forEach(product => {
                    if(bills.id === product.bills_id){
                        let name = document.createElement('span');
                        let qty = document.createElement('span');
                        let total = document.createElement('span');
                        name.textContent = product.name;
                        qty.textContent = product.qty;
                        total.textContent = product.total.toFixed(2);
                        row.cells[0].appendChild(name);
                        row.cells[1].appendChild(qty);
                        row.cells[2].appendChild(total);
                        
                    }
                })
            }
        });
}

function allBuyedProducts(data, tbody){
buyedProductsDiv.style.display = 'block';
        let rows = 1;
        data.productsSummary.forEach(product => {
            let row = tbody.insertRow();
            row.insertCell(0).textContent = rows;
            row.insertCell(1).textContent = product.name;
            row.insertCell(2).textContent = product.buyed_times;
            rows++;
        });
}
function searchedProduct(data, tbody){
buyedProductsDiv.style.display = 'block';
        // Add data for searched product
        let rows = 1;
        data.products.forEach(product => {
            let row = tbody.insertRow();
            row.insertCell(0).textContent = rows;
            row.insertCell(1).textContent = product.name;
            row.insertCell(2).textContent = product.buyed_times;
            rows++;
        });
}

function notPayed(){
    allBillsResults.style.display = "none";
    unPayedBills.style.display = "block";
    let taable = document.getElementById('notPayedTable');
    let rows = taable.rows.length
    let pay = 0;
    let qtys = 0;
    for (let i = 1; i < rows; i++) {
        var x = document.getElementById('notPayedTable').rows[i].cells[2]//.innerHTML;
        let cell = x.querySelectorAll('span');
            cell.forEach(span=>{
                let value = parseFloat(span.innerText);
                pay += value;
            })
        var y = document.getElementById('notPayedTable').rows[i].cells[1];
        let qty = y.querySelectorAll('span');
        qty.forEach(span=>{
                let value = parseFloat(span.innerText);
                qtys += value;
            })

    }

    let lastRow = document.querySelector('#notPayedTable tbody');
    let row = lastRow.insertRow();
    row.classList.add('text-danger')
    row.insertCell(0).textContent = "Total";
    row.insertCell(1).textContent = qtys;
    row.insertCell(2).textContent = pay.toFixed(2) + " €";
    row.insertCell(3);
    row.insertCell(4);
    row.insertCell(5);
    row.insertCell(6);
    row.insertCell(7);
}

function allBills() {
allBillsResults.style.display = "block";
unPayedBills.style.display = "none";
}