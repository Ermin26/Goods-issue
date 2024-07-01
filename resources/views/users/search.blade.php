<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/search.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/allPages.css')}}">
    <title>Išči delavca</title>
</head>

<body>
     @include('navbar')

        <div class="row" id="row">
            <div class="p-0 border-end border-bottom border-3 border-dark" id="search">
                <div class="ms-3 text-center">
                    <h1 class="mt-1">Išči delavca</h1>
                    <form class="text-center" id="searchUser">
                        <div class="form-group mt-3 w-50 ms-auto me-auto">
                            <label for="user">Delavec</label>
                            <input type="text" class="form-control w-100 border-2 border-dark" name="username" id="username"
                                placeholder="required" required>
                        </div>

                        <div class="form-group mt-3 w-50 ms-auto me-auto">
                            <label for="product">Produkt</label>
                            <input type="text" class="form-control w-100 border-2 border-dark" name="product"
                                id="product" placeholder="Not required">
                        </div>

                        <div class="footer mb-4">
                            <button class="btn btn-sm btn-primary mt-3 ms-2">Išči</button>
                        </div>
                    </form>
                </div>

                    <div class="mt-4" id="btns" style="display: none">
                        <div class="btns mt-4 p-1 d-flex  justify-content-evenly">
                            <button class="btn btn-primary" onclick="allBills()">All</button>
                            <button class="btn btn-danger" onclick="notPayed()">Not Payed</button>
                            <button class="btn btn-success" onclick="window.print()">Print</button>
                        </div>
                    </div>

                            <div id="qtyBuyedProducts" class="mt-3 mb-5" style="display: none">

                                <caption>
                                    <h2 class="text-center">Product kupljen</h2>
                                </caption>

                                <table id="tableProducts" class="table table-info table-hover text-center text-dark">
                                    <thead>
                                        <th>Product</th>
                                        <th>Datum</th>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
            </div>
            @include('flash')
            <div id="result">
                <h2 class="text-center mt-3">Nothing to show here.</h2>
            </div>
                <div id="allBills" style="display: none">
                    <div class="head text-center">
                        <h1 id="searched">
                            <strong>Vsi računi uporabnika</strong> <br>
                            <strong id="userName" class="text-primary"></strong>.
                        </h1>
                    </div>
                    <div id="data">
                        <table id="table" class="table table-dark table-hover mt-3 text-center">
                            <thead id="table-data">
                                <th class="col">Product</th>
                                <th class="col">Qty</th>
                                <th class="col">Total</th>
                                <th class="col">Month</th>
                                <th class="col">Week</th>
                                <th class="col">Free</th>
                                <th class="col">Payed</th>
                                <th class="col">Sold date</th>
                                <th class="col">Sold by</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--
                <%# } %>
                     UNPAYED BILLS-->
                    <div id="unPayedBills" class="text-center" style="display: none">
                        <div class="mt-4 mb-3">
                            <h1 id="unPayedH1" class="text-danger">Unpayed bills from user <br>
                                <strong class="text-primary">
                                    <!--
                                    <%# searched %>ž
                                    -->
                                </strong>
                            </h1>
                        </div>
                        <!--
                        <%# for(data of usersData) {%>
                        -->
                            <table id="notPayedTable" class="table table-dark table-hover mt-3 text-center">
                                <thead id="table-data">
                                    <th class="col">Product</th>
                                    <th class="col">Qty</th>
                                    <th class="col">Total</th>
                                    <th class="col">Month</th>
                                    <th class="col">Week</th>
                                    <th class="col">Payed</th>
                                    <th id="edit" class="col">Edit</th>
                                    <th class="col">Sold date</th>
                                    <th class="col">Sold by</th>
                                </thead>
                                <tbody>
                                    <!--
                                    <%# for(user of data) {%>
                                        <%# if(user.pay=="false" ) {%>
                                            <%# for(products of user.products) {%>
                                            -->
                                                <tr>
                                                    <!--
                                                    <td>
                                                        <%# products.name %>
                                                    </td>
                                                    <td>
                                                        <%# products.qty %>
                                                    </td>
                                                    <td>
                                                        <%# products.total %>
                                                    </td>
                                                    <td>
                                                        <%# user.month %>
                                                    </td>
                                                    <td>
                                                        <%# user.kt %>
                                                    </td>
                                                    -->
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <img src="../notPay.jpg" alt="Not Payed">
                                                    </td>
                                                    <td id="editLink"><a href="/all/<%# user.id %>">Edit</a></td>
                                                    <td>
                                                        <%# user.soldDate %>
                                                    </td>
                                                    <td>
                                                        <%# user.izdal %>
                                                    </td>
                                                </tr>
                                                <!--
                                                <%# } %>
                                                    <%# } %>
                                                        <%# } %>
                                                -->
                                                            <tr class="bg-danger">
                                                                <td>Skupaj</td>
                                                                <td id="qtys"></td>
                                                                <td id="payed" class="bg-danger"></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                </tbody>
                            </table>
                            <!--
                            <%# } %>
                            -->
                    </div>


            </div>

        </div>
        <script>

            let allBillsResults = document.getElementById('allBills');
            let unPayedBills = document.getElementById('unPayedBills');
            let allBillsTable = document.querySelector('#table tbody');
            let notPayedTable = document.querySelector('#notPayedTable tbody');

        document.getElementById('searchUser').addEventListener('submit', function(event) {
            event.preventDefault();
        
            let username = document.getElementById('username').value;
            let product = document.getElementById('product').value;

            fetchData('{{ route('searchUser') }}', { username: username, product: product });});

            function fetchData(url, params) {
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
                document.getElementById('btns').style.display = 'block';
                if(data.products.length > 0) {
                    document.getElementById('qtyBuyedProducts').style.display = 'block';
                    let tbody = document.querySelector('#tableProducts tbody');
                    tbody.innerHTML = '';
                    data.products.forEach(product => {
                        let row = tbody.insertRow();
                        let soldDate;
                        data.bills.forEach(bills => {
                            if(bills.id === product.bills_id){
                                soldDate = bills.sold_date;
                            }
                        })
                        row.insertCell(0).textContent = product.name;
                        row.insertCell(1).textContent = soldDate;
                    });
                    allBillsTable.innerHTML ="";
                    notPayedTable.innerHTML ="";
                    data.bills.forEach(bills => {
                        let row = allBillsTable.insertRow();
                        data.allProducts.forEach(product => {
                            if(bills.id === product.bills_id){
                                let imageUrl = product.firstOfWeek === 1 ? '{{ asset('img/payed.jpg') }}' : '{{ asset('img/notPay.jpg') }}';
                                let img = document.createElement('img');
                                img.src = imageUrl;
                                row.insertCell(0).textContent = product.name;
                                row.insertCell(1).textContent = product.qty;
                                row.insertCell(2).textContent = product.price;
                                row.insertCell(3).textContent = bills.month;
                                row.insertCell(4).textContent = bills.kt;
                                row.insertCell(5);
                                row.cells[5].appendChild(img);
                                row.insertCell(6).textContent = bills.payed;
                                row.insertCell(7).textContent = bills.sold_date;
                                row.insertCell(8).textContent = bills.published;
                            }
                        })
                        
                        
                    });
                        allBillsResults.style.display="block";
                        document.getElementById('username').textContent= data.bills[0].buyer;
                }
            })
            .catch(error => console.error('Error:', error));
        }
            



            document.getElementById("unPayedBills").style.display = "none";
            document.getElementById("allBills").style.display = "block";
            function notPayed() {
                document.getElementById("allBills").style.display = "none";
                document.getElementById("unPayedBills").style.display = "block";
            }
            function allBills() {
                document.getElementById("allBills").style.display = "block";
                document.getElementById("unPayedBills").style.display = "none";
            }

            let taable = document.getElementById('notPayedTable');
            let rows = taable.rows.length - 2
            let pay = 0;
            let qtys = 0;
            for (let i = 1; i <= rows; i++) {
                //console.log(rows[i].cells[5].innerHTML)
                // var x = document.getElementById('myTable').rows[i].cells[5].children[0].value;
                var x = document.getElementById('notPayedTable').rows[i].cells[2].innerHTML
                var y = document.getElementById('notPayedTable').rows[i].cells[1].innerHTML
                pay += parseFloat(x, 10);
                qtys += parseFloat(y, 10);

            }
            document.getElementById('payed').innerHTML = pay.toFixed(2) + ' ' + '€';
            document.getElementById('qtys').innerHTML = qtys;
        </script>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
            integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
            integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
            crossorigin="anonymous"></script>
</body>

</html>