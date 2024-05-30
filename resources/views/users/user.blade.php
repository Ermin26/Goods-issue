<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/user.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/allPges.css')}}">
    <title>
        <%= userData.buyer %>
    </title>
</head>

<body>

    <%- include('../layouts/boilerplate.ejs') %>

        <div id="hideOnPrint" class="text-center">
            <%- include('../flashError') %>
                <h1 class="mt-3 text-center">
                    <% if(currentUser.username == 'jan' || currentUser.role != 'visitor') {%>
                        <%= userData.buyer %>
                        Bill ID: <%= userData._id %>
                        <% }else if(currentUser.role == 'visitor' && currentUser != 'jan'){ %>
                            Name: <br>
                            <strong>Not available for visitors</strong>
                            <% } %> <br>
                </h1>
        </div>
        <div class="mt-3" id="containerr">

            <table id="user" class="table table-dark table-hover mt-3">
                <thead class="table-data fs-4 text-center">
                    <th>User</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>1<sup>st</sup> / Week</th>
                    <th>Total &euro;</th>
                    <th>Payed</th>
                    <th>Week</th>
                    <th>Sold Date</th>
                    <th>Pay Date</th>
                    <th>Izdal</th>
                    <th>Edit</th>
                    <th>Delete</th>
                    <th>Re-Print</th>
                </thead>
                <tbody class="text-center">

                    <% for(product of userData.products) {%>
                        <tr>
                            <td class="fs-4 text-info">
                                <% if(currentUser.username == 'jan' || currentUser.role != 'visitor') {%>
                                <%= userData.buyer %>
                                <% }else{ %>
                                    <p>Not available for visitors</p>
                                    <% } %>
                            </td>

                            <td>
                                <%= product.name %>
                            </td>
                            <td>
                                <%= product.qty %>
                            </td>

                            <td>

                                <% if(product.firstOfWeek=='true' ){ %>
                                    <img src="../payed.jpg" alt="Free">
                                    <% } else { %>
                                        <img src="../notPay.jpg" alt="Not Free">
                                        <% } %>
                            </td>
                            <td>
                                <%= product.total %> &euro;
                            </td>
                            <td>
                                <% if(userData.pay=='true' ){ %>
                                    <img src="../payed.jpg" alt="Payed">
                                    <% } else { %>
                                        <img src="../notPay.jpg" alt="Not Payed">

                                        <% } %>
                            </td>
                            <td>
                                <%= userData.kt %>
                            </td>
                            <td>
                                <%= userData.soldDate %>
                            </td>
                            <td>
                                <% if(userData.payDate){ %>
                                    <%= userData.payDate %>
                                        <% } else{%>
                                            Not payed
                                            <% } %>
                            </td>
                            <td>
                                <%= userData.izdal %>
                            </td>
                                    <td><a href="/all/<%= userData._id%>/edit"><button class="btn btn-warning btn-sm">
                                                EDIT
                                            </button>
                                        </a>
                                    </td>

                                    <td>
                                        <form action="/all/<%= userData._id%>/?_method=DELETE" method="post">
                                            <button class="btn btn-danger btn-sm">DELETE</button>
                                        </form>
                                    </td>
                                    <td><button class="btn btn-success btn-sm" id="gideOnPrint"
                                            onclick=window.print()>Print</button></td>
                        </tr>
                        <% } %>

                </tbody>
            </table>

        </div>

        <div class="hide">

            <div class="naslov text-center mt-1 mb-3 border-bottom border-2 border-dark">
                <h3>PROVIDIO, 2000 Maribor</h4>
            </div>


            <div id="izdal" class="kupec mt-2 ms-5 mb-3">

                <p>Izdal:</p>

                <div class="col">
                    <label for="izdal">Ime in priimek delavca:</label>
                    <input type="text" class="d-inline border border-white bg-white" name="izdal" id="izdal"
                        value="Ermin Joldić">
                </div>
                <p>Podpis: ________________________ </p>

            </div>

            <div class="row mt-5" id="dates">
                <div id="dates-left" class="col mt-2 text-left">
                    <label for="numPerYear">Številka/Leto:</label>
                    <input type="text" class="border border-white text-left" name="numPerYear" id="numPerYear"
                        value="<%= userData.numPerYear %>">
                    <input type="text" class="border border-white text-left" name="year" id="year"
                        value="<%= userData.year %> ">

                    <br>
                    <label class="" for="numPerMonth">Številka/Mesec:
                    </label>
                    <input type="text" class="border border-white" name="numPerMonth" id="numPerMonth"
                        value="<%= userData.numPerMonth %>">
                    <input type="text" class="border border-white" name="month" id="month" value="<%= userData.month%>">
                    <br>
                    <label for="kt">Koledarski Teden:</label>
                    <input type="text" class="border border-white" name="kt" id="kt" value="<%= userData.kt %> ">
                </div>

                <div class="col mt-2" id="info">
                    <ul id="info-ul" class="d-flex flex-column">
                        <li class="d-inline">
                            <p id="kraj" class="d-inline">Kraj: Maribor</p>
                        </li>
                        <li class="d-inline">
                            <% if(userData.payDate) {%>
                                <label for="payDate">Datum Plačila:</label>
                                <input type="text" class="border border-white" id="payDate" name="payDate"
                                    value="<%= userData.payDate %> ">
                                <% }else {%>
                                    <label for="payDate">Datum Plačila:</label>
                                    <input type="text" class="border border-white" id="payDate" name="payDate"
                                        value="Not payed">
                                    <% } %>
                        </li>
                        <li class="d-inline">
                            <label for="soldDate">Datum Izdaje:</label>
                            <input type="text" class="d-inline border border-white ms-1" id="soldDate" name="soldDate"
                                value="<%= userData.soldDate %> ">

                        </li>

                    </ul>
                </div>
            </div>

            <div id="table" class="row izdelki mt-5 text-center">
                <table id="myTable" class="table table-borderless table-responsive mt-5">
                    <thead>
                        <tr class="border-bottom border-dark">
                            <th class="col-4">Izdelek</th>
                            <th class="col-1">Količina</th>
                            <th class="col-2">Cena</th>
                            <th class="col-1">DDV</th>
                            <th class="col-1">1/week</th>
                            <th class="col-2">Neto</th>
                            <th class="col-1">Placano</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <% for(selled of userData.products) {%>
                            <tr class="mt-5 border border-start-0 border-end-0 border-dark" id="cash">
                                <td>
                                    <%= selled.name %>
                                </td>
                                <td>
                                    <%= selled.qty %>
                                </td>
                                <td>
                                    <%= selled.price %>
                                </td>
                                <td>1.50</td>
                                <td>
                                    <% for(product of selled.firstOfWeek) {%>
                                        <% if(product=='true' ){ %>
                                            <img src="../payed.jpg" alt="Free">
                                            <% } else { %>
                                                <img src="../notPay.jpg" alt="Not Free">
                                                <% } %>
                                </td>
                                <td>
                                    <% if(product.firstOfWeek=="true" ){%>
                                        0.00
                                        <% } else {%>
                                            <%= selled.total %>
                                                <% } %>


                                </td>
                                <% } %>

                            </tr>
                            <% } %>
                                <tr>
                                    <td>Plačano</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td id="payed">

                                    </td>
                                    <td>
                                        <% if(userData.pay=='true' ){ %>
                                            <img src="../payed.jpg" alt="Payed">
                                            <% } else { %>
                                                <img src="../notPay.jpg" alt="Not Payed">

                                                <% } %>
                                    </td>
                                </tr>
                    </tbody>
                </table>
            </div>


            <div class="mt-5 me-5 text-end">
                <ul>
                    <li id="nekaj">
                        <label for="ime">Ime prejemnika:</label>
                        <input class="border border-white" id="ime" name="buyer" placeholder="Ime prejemnika"
                            value=" <%= userData.buyer %>  ">
                    </li>
                    <br>
                    <li>
                        <h4>PODPIS PREJEMNIKA</h2>
                            <h3>________________________</h3>
                    </li>

                </ul>
            </div>


        </div>



        <script>
            // Re-print function
            let taable = document.getElementById('myTable');
            let rows = taable.rows.length - 2
            let pay = 0;
            for (let i = 1; i <= rows; i++) {
                //console.log(rows[i].cells[5].innerHTML)
                // var x = document.getElementById('myTable').rows[i].cells[5].children[0].value;
                var x = document.getElementById('myTable').rows[i].cells[5].innerHTML
                pay += parseFloat(x, 10);

            }
            document.getElementById('payed').innerHTML = pay.toFixed(2);

        </script>




        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
            integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
            integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
            crossorigin="anonymous"></script>
</body>

</html>
