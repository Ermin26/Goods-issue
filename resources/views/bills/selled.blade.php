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
    <title>Računi</title>
</head>

<body>
    @include('navbar')

    <button onclick="topFunction()" id="myBtn" title="Go to top">︽</button>
    <button onclick="bottomFunction()" id="bottom" title="Go to bottom">︾</button>

    <div class="caption" id="caption">
        @include('flash')
        @if(request()->is('all'))
            <h1><strong class="fs-1 text-primary">Vsi računi</strong></h1>
            <h2><strong class="fs-2 text-info">Mesec <?php echo date("F") ?> :
                    {{$thisMonth}}
                </strong></h2>
            <h2><strong class="fs-2 text-primary">Število vseh računov:
                    {{$totalBills}}
            </strong></h2>
        @elseif (request()->is('all/payed'))
        <h2>Total</h2>
        <h3>Število plačanih računov: <span class="text-success">{{$totalPayed}}</span></h3>
        @elseif (request()->is('all/notpayed'))
        <h2>Total</h2>
        <h3>Število ne plačanih računov: <span class="text-danger">{{$totalNotPayed}}</span></h3>
        @endif
    </div>
    <div class="selectBills ms-auto mr-5px" style="">
        <select name="billStatus" id="billsStatus" onchange="showBills()">
            <option value="all" {{ request()->is('all') ? 'selected' : '' }}>Vsi računi</option>
            <option value="payed" {{ request()->is('all/payed') ? 'selected' : '' }}>Plačani računi</option>
            <option value="not_payed" {{ request()->is('all/notpayed') ? 'selected' : '' }}>Neplačani računi</option>
        </select>
    </div>

<div id="results">
    
    @if (request()->is('all'))
        @include('bills.allbills')
    @elseif (request()->is('all/payed'))
        @include('bills.payed')
    @elseif (request()->is('all/notpayed'))
        @include('bills.notPayed')
    @endif
</div>







    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
    integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
    crossorigin="anonymous"></script>


    <script>



    document.getElementById('billsStatus').addEventListener('change', function () {
            var value = this.value;
            if (value === 'all') {
                window.location.href = "{{ route('all') }}";
            } else if (value === 'payed') {
                window.location.href = "{{ route('payed') }}";
            } else if (value === 'not_payed') {
                window.location.href = "{{ route('notpayed') }}";
            }
        });

/*
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
*/
        let mybutton = document.getElementById("myBtn");
        let bottom = document.getElementById("bottom");

        // When the user scrolls down 20px from the top of the document, show the button
        bottom.style.display = "block";
        window.onscroll = function () { scrollFunction() };
        function scrollFunction() {
            if (document.body.scrollTop > 140 || document.documentElement.scrollTop > 140) {
                mybutton.style.display = "block";
                
            } else {
                mybutton.style.display = "none";
                bottom.style.display = "block";
            }
            if ((window.innerHeight + window.scrollY) != document.body.offsetHeight) {
                bottom.style.display = "block";
            }
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                bottom.style.display = "none";
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