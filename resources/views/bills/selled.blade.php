<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="{{asset('css/allPages.css')}}">
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
        <link rel="stylesheet" href="{{asset('css/all.css')}}">
    <title>Selled Products</title>
</head>

<body>
    @include('navbar')

    <button onclick="topFunction()" id="myBtn" title="Go to top">︽</button>
    <button onclick="bottomFunction()" id="bottom" title="Go to bottom">︾</button>

    <div class="caption" id="caption">
        @include('flash')
            <h1><strong class="fs-1 text-primary">All bills</strong></h1>
            <h2><strong class="fs-2 text-info">This month:
                    50
                </strong></h2>
            <h2><strong class="fs-2 text-primary">Number of all bills:
                    145
                </strong></h2>
    </div>

    <!-- ALL BILLS -->

    <div id="allUsers" class="text-center mb-0 ">
        <table id="all" class="table table-striped-columns table-hover mb-0 ">
            <thead id="table-data" class="table table-dark text-center text-light align-middle border-bottom">
                <tr class="text-light">
                    <th class="col">User</th>
                    <th class="col">Product</th>
                    <th class="col-1">Qty</th>
                    <th class="col-1">Price</th>
                    <th class="col-1">DDV</th>
                    <th class="col">free</th>
                    <th class="col">Week</th>
                    <th class="col">Total</th>
                    <th class="col-1">Payed</th>
                    <th class="col-1">Num / Month</th>
                    <th class="col-1">Month</th>
                    <th class="col-1">Num / Year</th>
                    <th class="col">Year</th>
                    <th class="col">Sold Date</th>
                    <th class="col">Issued by</th>
                </tr>
            </thead>
            <tbody class="tableBody mt-3 align-middle">
                            <tr class="m-1 align-middle">
                                <td><h1 class="mt-5">Empty.</h1></td>
                            </tr>


            </tbody>
        </table>

    </div>

    <!-- PAYED BILLS -->


    <div id="placano" class="text-center">
        <table id="payed" class="table table-dark table-hover caption-top mb-0">
            <thead class="align-middle" id="table-head">
                <th>User</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total &euro;</th>
                <th>Free</th>
                <th>Week</th>
                <th>Sold Date</th>
                <th>Pay Date</th>
                <th>Issued by</th>
            </thead>
            <tbody>
                            <tr class="fs-4 align-middle">

                            </tr>


            </tbody>
        </table>
    </div>

    <!-- NOT PAYED BILLS -->




    <div id="niPlacano" class="text-center mb-0">
        <table id="notpayed" class="table table-dark table-hover mb-0">

            <thead class="align-middle text-light" id="table-head">
                <th>User</th>
                <th>Product</th>
                <th>Bill ID</th>
                <th>Quantity</th>
                <th>Total &euro;</th>
                <th>Week</th>
                <th>Sold Date</th>
                <th>Issued by</th>
            </thead>
            <tbody class="text-danger">
                            <tr class="align-middle">
                            </tr>
                                        <tr class="fs-1">
                                            <td>Total:</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td id="total"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
    integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
    crossorigin="anonymous"></script>


    <script>
        let active = document.getElementById('vsi')
        active.classList.add('border-bottom', 'border-3', 'rounded-bottom', 'border-primary')
        document.getElementById('placano').style.display = 'none';
        document.getElementById('niPlacano').style.display = 'none';
        document.getElementById('payedCaption').style.display = 'none';
        document.getElementById('notPayedcaption').style.display = 'none';
        document.getElementById('allUsers').style.display = 'block';
        document.getElementById('allUsers').style.marginLeft = 'auto';
        document.getElementById('allUsers').style.marginRight = 'auto';

        function payedBills() {

            document.getElementById('allUsers').style.display = 'none';
            document.getElementById('niPlacano').style.display = 'none';
            document.getElementById('caption').style.display = 'none';
            document.getElementById('notPayedcaption').style.display = 'none';
            document.getElementById('payedCaption').style.display = 'block';
            document.getElementById('placano').style.display = 'block';
            document.getElementById('placano').style.marginLeft = 'auto';
            document.getElementById('placano').style.marginRight = 'auto';
            let active = document.getElementById('placali')
            active.classList.add('border-bottom', 'border-3', 'rounded-bottom', 'border-success')
            document.getElementById('vsi').classList.remove('border-bottom', 'border-3', 'rounded-bottom', 'border-primary')
            document.getElementById('nePlacali').classList.remove('border-bottom', 'border-3', 'rounded-bottom', 'border-danger')
            let placano = document.getElementById('payed');
            let rows = placano.rows.length - 1;
            let totalPayed = 0;
            for (let i = 1; i <= rows; i++) {
                var x = document.getElementById('payed').rows[i].cells[3].innerText;
                totalPayed += parseFloat(x, 10);
            }
            document.getElementById('skupajPlacano').innerHTML = "Total: " + " " + totalPayed.toFixed(2) + " €";

        }
        function notPayedBills() {
            let active = document.getElementById('nePlacali')
            active.classList.add('border-bottom', 'border-3', 'rounded-bottom', 'border-danger')
            document.getElementById('placali').classList.remove('border-bottom', 'border-3', 'rounded-bottom', 'border-success')
            document.getElementById('vsi').classList.remove('border-bottom', 'border-3', 'rounded-bottom', 'border-primary')
            document.getElementById('placano').style.display = 'none';
            document.getElementById('allUsers').style.display = 'none';
            document.getElementById('caption').style.display = 'none';
            document.getElementById('payedCaption').style.display = 'none';
            document.getElementById('notPayedcaption').style.display = 'block';
            document.getElementById('niPlacano').style.display = 'block';
            document.getElementById('niPlacano').style.marginLeft = 'auto';
            document.getElementById('niPlacano').style.marginRight = 'auto';

            let all = document.getElementById('notpayed');
            let rows = all.rows.length - 2;

            let total = 0;
            for (let i = 1; i <= rows; i++) {
                var x = document.getElementById('notpayed').rows[i].cells[4].innerText;
                total += parseFloat(x, 10);
            }
            document.getElementById('total').innerHTML = "<strong>" + total.toFixed(2) + " " + "€" + "</strong>";
            document.getElementById('dolg').innerHTML = "Total to pay:" + " " + total.toFixed(2) + " " + "€";


        }
        function allBills() {
            let active = document.getElementById('vsi')
            active.classList.add('border-bottom', 'border-3', 'rounded-bottom', 'border-primary')
            document.getElementById('placali').classList.remove('border-bottom', 'border-3', 'rounded-bottom', 'border-success')
            document.getElementById('nePlacali').classList.remove('border-bottom', 'border-3', 'rounded-bottom', 'border-danger')
            document.getElementById('placano').style.display = 'none';
            document.getElementById('niPlacano').style.display = 'none';
            document.getElementById('notPayedcaption').style.display = 'none';
            document.getElementById('payedCaption').style.display = 'none';
            document.getElementById('allUsers').style.display = 'block';
            document.getElementById('caption').style.display = 'block';
            document.getElementById('allUsers').style.marginLeft = 'auto';
            document.getElementById('allUsers').style.marginRight = 'auto';
        }


        let mybutton = document.getElementById("myBtn");
        let bottom = document.getElementById("bottom");

        // When the user scrolls down 20px from the top of the document, show the button
        bottom.style.display = "block";
        window.onscroll = function () { scrollFunction() };
        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                mybutton.style.display = "block";

            } else {
                mybutton.style.display = "none";
                bottom.style.display = "block";
            }
            if (document.body.scrollBottom == 0 || document.documentElement.scrollBottom == 0) {
                bottom.style.display = "none";
                console.log("yooo yooo yoo")
            }
        }

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
        function bottomFunction() {
            window.scrollTo(0, document.body.scrollHeight);

        }

    </script>
    
</body>


</html>
