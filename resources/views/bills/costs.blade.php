<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Ermin Joldić">
    <meta name="keywords" content="izdaja-blaga, Izdaja-blaga, izdelava računov, primer izdelave računov">
    <meta name="description" content="izdaja-blaga, Ermin Joldić, interna izdaja blaga">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/costs.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/allPages.css')}}">
    <title>Costs</title>
</head>

<body>
     @include('navbar') %>
    <%# include('../flashError.ejs') %>
        <!-- <div class="container text-center">-->
        <div id="cols" class="row row-cols-2 w-100 ms-auto me-auto">

            <div id="col2" class="col-4 p-0 ms-auto me-auto">
                <table id="cashTable" class="table table-dark p-2 ms-auto me-auto text-center align-middle">
                    <thead>
                    <tr>
                        <th>Earned</th>
                        <th>Spended</th>
                        <th>Neto</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                            <td class="bg-info"><% if(currentUser.role != 'visitor' || currentUser.username == 'jan') {%>
                                <strong id="payed"> </strong> &euro;</h4>
                                <% }else {%>
                                        <strong>/</strong>
                                    <% } %>
                            </td>
                            <td class="bg-danger"><% if(currentUser.role != 'visitor' || currentUser.username == 'jan') {%>
                        <strong id="spended"></strong> &euro;</h4>
                        <% }else {%>
                                <strong>/</strong>
                            <% } %>
                        </td>
                        <td class="bg-success"><% if(currentUser.role != 'visitor' || currentUser.username == 'jan') {%>
                            <strong id="cash"></strong> &euro;</h2>
                            <% }else {%>
                                    <strong>/</strong>
                                <% } %>
                        </td>
                    </tr>
                </tbody>
                </table>
                <table id="hide">
                    <tr>
                        <thead>
                            <th>Price</th>
                        </thead>
                    </tr>
                    <% for(price of payed) {%>
                        <tr>
                            <td>
                                <%= price%>
                            </td>
                        </tr>
                        <% } %>
                </table>
                <div class="addBills mt-5 mb-5 ms-auto me-auto text-center">
                    <button id="showFormBtn" class="btn btn-primary" onclick="showInputForm()">Add new bill</button>
                </div>
                <div id="hideMe">
                    <div id="addBillForm" class="col p-3 d-flex shadow text-center justify-content-center">
                        <form action="/addCosts" method="post" class="w-75 p-4 shadow">
                            <div class="mb-2 text-center">
                                <label for="date" class="form-label">Bill Date:</label><br>
                                <input type="date" id="date" class="form-control text-center" name="date"
                                    placeholder="Enter Bill date" required>
                            </div>

                            <div class="mb-2 col">
                                <label for="buyedProducts" class="form-label">Buyed products:</label>
                                <textarea type="text" id="buyedProducts" class="form-control text-center"
                                    name="buyedProducts" placeholder="Enter buyed products" required></textarea>
                            </div>

                            <div class="mb-2">
                                <label for="totalPrice" class="form-label">Price:</label>
                                <input type="text" id="totalPrice" class="form-control text-center" name="totalPrice"
                                    placeholder="Enter total price &euro;" required>
                            </div>

                            <button class="btn btn-success mt-4 mb-4">Submit</button>
                        </form>
                    </div>
                </div>
            </div>

            <div id="col1" class="col-8 text-center p-0">

                <% if(!allCosts.length) {%>
                    <h1>Nothing to show yet. Add some bills.</h1>
                    <% }else {%>
                        <div class="header mt-3 text-center">
                            <h1>Bills from using company money</h1>
                        </div>

                        <div id="bills">

                            <table id="tableOfCosts" class="table table-dark table-hover align-middle">
                                <tr>
                                    <thead id="table-data" class="align-middle">
                                        <th>#</th>
                                        <th>Bill date</th>
                                        <th>Total price &euro;</th>
                                        <th>Buyed</th>
                                        <th>Booked date</th>
                                        <th>Booked by user</th>
                                        <th class="bg-danger">WARNING</th>
                                    </thead>
                                </tr>
                                <% for(data of allCosts) {%>
                                    <tr>
                                        <td>

                                        </td>
                                        <td>
                                            <%= data.date %>
                                        </td>
                                        <% if(currentUser.role != 'visitor' || currentUser.username == 'jan') {%>
                                        <td>
                                            <%= data.totalPrice %>
                                        </td>
                                        <% }else{%>
                                            <td> €</td>
                                            <% } %>
                                        <td>
                                            <%= data.buyedProducts %>

                                        </td>
                                        <td>
                                            <%= data.bookedDate %>
                                        </td>
                                        <td>
                                            <% if(currentUser.role != 'visitor' || currentUser.username == 'jan') {%>
                                                <%= data.bookedUser %>
                                            <% }else{ %>
                                                <p>Not available</p>
                                            <% } %>
                                        </td>
                                        <td>
                                            <form action="/costs/<%= data._id%>/?_method=DELETE" method="post">
                                                <button class="btn btn-danger btn-sm">DELETE</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <% } %>
                                        <tr>
                                            <td>Total spend:</td>
                                            <td></td>
                                            <% if(currentUser.role != 'visitor' || currentUser.username == 'jan') {%>
                                            <td id="spend" class="bg-danger"></td>
                                            <% }else {%>
                                                <td>Not available</td>
                                                <% } %>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                            </table>
                            <% } %>
                        </div>
            </div>

        </div>


        <script>

            document.getElementById("hideMe").style.display = "none";
            document.getElementById("hide").style.display = "none";
            function showInputForm() {
                document.getElementById("hideMe").style.display = "block";
                document.getElementById("showFormBtn").style.display = "none";
            }

            let totalTable = document.getElementById('hide')
            if (totalTable.rows.length) {
                let tableRows = totalTable.rows.length - 1;
                let cash = 0;
                for (let j = 2; j <= tableRows; j++) {
                    var y = totalTable.rows[j].cells[0].innerText;
                    cash += parseFloat(y, 10);
                }
                document.getElementById("payed").innerText = cash.toFixed(2);
                document.getElementById("payed").style.color = 'white'
            }

            let myTable = document.getElementById('tableOfCosts')
            if (myTable.rows.length) {
                let rows = myTable.rows.length - 1
                let pay = 0;
                for (let i = 2; i < rows; i++) {
                    var x = myTable.rows[i].cells[2].innerHTML
                    pay += parseFloat(x, 10);
                }
                let number = 0;
                for (let j = 2; j < rows; j++) {
                    number += 1
                    myTable.rows[j].cells[0].innerHTML = number;

                    //document.getElementById('num').innerText = j
                }
                document.getElementById('spend').innerText = pay.toFixed(2);
                document.getElementById('spended').innerText = pay.toFixed(2);
                document.getElementById('spended').style.color = 'white'
            }

            let spended = document.getElementById('spended').innerText;
            let payed = document.getElementById('payed').innerText;
            let cash = parseFloat(payed) - parseFloat(spended)
            document.getElementById('cash').innerHTML = cash.toFixed(2);
            document.getElementById('cash').style.color = 'white';
            function notAllowed() {
                window.alert('Not Allowed. Only Admin or Moderator can add bill.');
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
            integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
            integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
            crossorigin="anonymous"></script>

</body>

</html>