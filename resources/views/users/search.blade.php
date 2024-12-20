<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="{{asset('css/header.css')}}">
        <link rel="stylesheet" href="{{asset('css/search.css')}}">
        <title>Išči</title>
</head>

<body>
     @include('navbar')

     <section class="topSection mt-5">
        @include('flash')
        <div id="" class="text-center">
            <h2 class="text-center mt-3">Upišite ime delavca ali produkta!</h2>
        </div>
        <div class="row row-cols-2 d-flex flex-row-reverse flex-wrap-reverse border-bottom border-3 border-light" id="row">
            <div class="col-12 col-md-6 p-0" id="search">
                    <form class="text-center" id="searchUser">
                        <div class="form-group mt-3 me-auto">
                            <label for="user">Delavec</label>
                            <input type="text" class="form-control w-100 border-2 border-dark" name="username" id="username"
                                placeholder="Ime delavca">
                            <small>%ime% vključeno</small>
                        </div>

                        <div class="form-group mt-3 me-auto">
                            <label for="product">Produkt</label>
                            <input type="text" class="form-control w-100 border-2 border-dark" name="product"
                                id="product" placeholder="Produkt">
                            <small>%product% vključeno</small>
                        </div>

                        <div class="footer mb-4">
                            <button class="btn btn-primary mt-3">Išči</button>
                        </div>
                    </form>
            </div>
            <div class="col-12 col-md-6 infos text-start ms-auto me-auto p-4">
                <div class="searchInfo">
                    <small>-Vpiši samo ime delavca če želiš pridobit vse račune.</small><br>
                    <small>-Vpiši ime delavca in ime produkta če želiš pridobit vse račune in tudi kolikokrat je delavec kupil iskani produkt.</small><br>
                    <small>-Vpiši samo ime produkta če želiš pridobit podatke kolikokrat je kupljenj iskani produkt.</small><br>
                    <small>-Pusti vse prazno, če želiš pridobit podatke o vseh kupljenih produktih.</small>
                </div>
            </div>
        </div>
        
        <div id="productsRow" class="row">
            <div class="mt-4" id="searchBtns" style="display: none">
                <div class="btns mt-4 p-1 d-flex  justify-content-center">
                    <button class="btn btn-primary" onclick="allBills()">Vsi</button>
                    <button class="btn btn-danger" onclick="notPayed()">Ne plačano</button>
                    <button class="btn btn-success" onclick="window.print()">Print</button>
                </div>
            </div>

            <div id="qtyBuyedProducts" class="mt-3 mb-5" style="display: none">
                    <caption>
                        <h2 class="text-center">Product kupljen</h2>
                    </caption>
                    <table id="tableProducts" class="table table-dark table-hover text-center w-75 ms-auto me-auto">
                        <thead>
                            <th>#</th>
                            <th>Product</th>
                            <th>Kupljen</th>
                        </thead>
                        <tbody id="tableProductsBody"></tbody>
                        </tbody>
                    </table>
            </div>
        </div>
    </section>


    <div id="allBills" style="display: none">
                    <div class="head text-center">
                        <h1 id="searched">
                            <strong>Vsi računi uporabnika</strong> <br>
                            <strong id="userName" class="text-primary"></strong>
                        </h1>
                    </div>
                    <div id="data">
                        <table id="table" class="table table-dark table-hover mt-3 text-center">
                            <thead id="table-data">
                                <th class="col">Produkt</th>
                                <th class="col">Količina</th>
                                <th class="col">Total</th>
                                <th class="col">Mesec</th>
                                <th class="col">Teden</th>
                                <th class="col">Brezplačno</th>
                                <th class="col">Plačano</th>
                                <th class="col">Izdano</th>
                                <th class="col">Izdal</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
    </div>
            <!--UNPAYED BILLS-->
    <div id="unPayedBills" class="text-center" style="display: none">
                <div class="mt-4 mb-3">
                    <h1 id="unPayedH1" class="text-danger">Neplačani računi <br>
                        <strong id="notPayedUserName" class="text-primary">

                        </strong>
                    </h1>
                </div>
                <div class="data">
                    <table id="notPayedTable" class="table table-dark table-hover mt-3 text-center">
                        <thead id="table-data">
                            <th class="col">Product</th>
                            <th class="col">Količina</th>
                            <th class="col">Total</th>
                            <th class="col">Mesec</th>
                            <th class="col">Teden</th>
                            <th class="col">Prodano</th>
                            <th class="col">Izdal</th>
                            <th id="edit" class="col">Uredi</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
            integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
            integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
            crossorigin="anonymous"></script>

    <script>

                let allBillsResults = document.getElementById('allBills');
                let unPayedBills = document.getElementById('unPayedBills');
                let allBillsTable = document.querySelector('#table tbody');
                let notPayedTable = document.querySelector('#notPayedTable tbody');
                let userName = document.getElementById('userName');
                let buyedProductsDiv = document.getElementById('qtyBuyedProducts');
                let btns = document.getElementById('searchBtns');
                //let emptyDiv = document.getElementById('empty');

                document.getElementById('searchUser').addEventListener('submit', function(event) {
                    event.preventDefault();

                    let username = document.getElementById('username').value;
                    let product = document.getElementById('product').value;
                    allBillsTable.innerHTML ="";
                    notPayedTable.innerHTML ="";
                    btns.style.display = "none";

                    fetchData('{{ route('searchUser') }}', { username: username, product: product });
                });

                function fetchData(url, params) {
                    let tbody = document.querySelector('#tableProducts tbody');
                    tbody.innerHTML = '';
                    allBillsResults.style.display = "none";
                    buyedProductsDiv.style.display = "none";
                    //emptyDiv.style.display = "none";
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
                        //if(data.bills && data.bills.length > 0  && !data.products){
                        if(username.value && !product.value){
                            console.log("this is if");
                            searchedName(data, tbody, allBillsTable, allBillsResults,notPayedTable, buyedProductsDiv,btns);
                            notPayedBills(data)
                        }
                        //else if(data.bills && data.bills.length > 0 && data.products && data.products.length > 0){
                        else if(username.value && product.value){
                            console.log("this is 1 else if");
                            searchedNameAndProduct(data, tbody, allBillsTable, allBillsResults,notPayedTable, buyedProductsDiv,btns);
                            notPayedBills(data)
                        }
                        //else if(data.products && data.products.length > 0){
                        else if(!username.value && product.value){
                            console.log("this is 2 else if");
                            searchedProduct(data,tbody)
                        }
                        //else if(data.productsSummary && data.productsSummary.length > 0){
                        else if(!username.value && !product.value){
                            console.log("this is 3 else if");
                            allBuyedProducts(data, tbody);
                        }
                        else{
                            showError(emptyDiv);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }

                function searchedNameAndProduct(data, tbody, allBillsTable, allBillsResults,notPayedTable, buyedProductsDiv,btns){
                    if(data.bills.length || data.products.length){
                        //emptyDiv.style.display = 'none';
                        //! If data.products fill table for searched products
                        if(data.products.length){
                            btns.style.display = 'block';
                            buyedProductsDiv.style.display = 'block';
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
                        }else{
                            let productError = document.getElementById('productsRow');
                            let error = document.createElement('h3');
                            error.classList.add('text-center', 'm-4', 'p-2');
                            error.textContent = `Uporabnik ni kupil izdelek "${product.value}"`;
                            productError.appendChild(error);
                        }
                        if(data.bills.length){
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
                    }else{
                        showError(emptyDiv);
                    }
                }

                function searchedName(data, tbody, allBillsTable, allBillsResults,notPayedTable, buyedProductsDiv,btns){
                    if(data.bills.length){
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
                    }else{
                        showError(emptyDiv);
                    }
                }
                function notPayedBills(data) {
                    if(data.bills.length){
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
                }

                function allBuyedProducts(data, tbody){
                    console.log(data);
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

                function showError(emptyDiv){
                    emptyDiv.style.display = 'block';
                    emptyDiv.innerHTML = "";
                    let h1 = document.createElement('h1');
                    let h2 = document.createElement('h2');
                    h1.innerHTML = "Iskani kupec ali produkt ne obstaja!";
                    h2.innerHTML = "Preverite upisane parametre in poskusite še enkrat.";
                    emptyDiv.appendChild(h1);
                    emptyDiv.appendChild(h2);
                    emptyDiv.style.backgroundColor = '#D16161';
                }

    </script>
</body>

</html>