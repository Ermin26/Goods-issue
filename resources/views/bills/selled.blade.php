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
            <h1><strong class="fs-1 text-primary">Vsi računi</strong></h1>
            <h2><strong class="fs-2 text-info">Mesec <?php echo date("F") ?> :
                    {{$thisMonth}}
                </strong></h2>
            <h2><strong class="fs-2 text-primary">Število vseh računov:
                    {{$totalBills}}
                </strong></h2>
    </div>

    <div id="allUsers" class="text-center">
        <table id="all" class="table table-striped-columns table-hover mb-0 ">
          <thead id="table-data" class="table-dark text-center align-middle border-bottom">
              <tr class="text-light">
                  <th class="col">Kupec</th>
                  <th class="col">Produkt</th>
                  <th class="col-1">Količina</th>
                  <th class="col-1">Cena</th>
                  <th class="col-1">DDV</th>
                  <th class="col">Free</th>
                  <th class="col">Teden</th>
                  <th class="col">Neto</th>
                  <th class="col-1">Plačano</th>
                  <th class="col-1">Št / mesec</th>
                  <th class="col-1">Mesec</th>
                  <th class="col-1">Redna št</th>
                  <th class="col">Leto</th>
                  <th class="col">Izdano</th>
                  <th class="col">Izdal</th>
              </tr>
          </thead>
              <tbody>
                @foreach($bills as $bill)
                @foreach ($products as $product)
                @if($bill->id == $product->bills_id)
                <tr class="m-1 align-middle">
                    @if(Auth::user()->role !== 'visitor')
                        @if ($bill->payed == 1)
                        <td class="bg-success"><a href="all/view/{{$bill->id}}">{{$bill->buyer}}</a></td>
                        @else
                        <td class="bg-danger"><a href="all/view/{{$bill->id}}">{{$bill->buyer}}</a></td>
                        @endif
                    @else
                    <td class="bg-warning">Not for visitors</td>
                    @endif
                    <td>{{$product->name}}</td>
                    <td>{{$product->qty}}</td>
                    <!--
                        <td>{number_format($product->price,2)}}</td>
                    -->
                    <td>{{$product->price,}}</td>
                    <td>1.50</td>
                    @if ($product->firstOfWeek == 1)
                            <td><img src="{{asset('img/payed.jpg')}}" alt="Payed"></td>
                        @else
                            <td><img src="{{asset('img/notPay.jpg')}}" alt="Not Payed"></td>
                        @endif
                    <td>{{$bill->kt}}</td>
                    <td>{{$product->total}}</td>
                    @if ($bill->payed == 1)
                       <td><img src="{{asset('img/payed.jpg')}}" alt="Payed"></td>
                    @else

                    <td><img src="{{asset('img/notPay.jpg')}}" alt="Not Payed"></td>
                    @endif
                    <td>{{$bill->num_per_month}}</td>
                    <td>{{$bill->month}}</td>
                    <td>{{$bill->num_per_year}}</td>
                    <td>{{$bill->year}}</td>
                    <td>{{$bill->sold_date}}</td>
                    @if(Auth::user()->role !== 'visitor')
                    <td>{{$bill->published}}</td>
                    @else
                    <td class="bg-warning">Not for visitors</td>
                    @endif
                </tr>
                @endif
            @endforeach
                @endforeach
              </tbody>
            </table>
          </div>
        {{ $bills->links('custom') }}
    </div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
    integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
    crossorigin="anonymous"></script>


    <script>
        /*
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

*/
        let mybutton = document.getElementById("myBtn");
        let bottom = document.getElementById("bottom");

        // When the user scrolls down 20px from the top of the document, show the button
        bottom.style.display = "block";
        window.onscroll = function () { scrollFunction() };
        function scrollFunction() {
            if (document.body.scrollTop > 140 || document.documentElement.scrollTop > 140) {
                mybutton.style.display = "block";
                console.log("1");
                
            } else {
                mybutton.style.display = "none";
                bottom.style.display = "block";
                console.log("2");
            }
            if ((window.innerHeight + window.scrollY) != document.body.offsetHeight) {
                bottom.style.display = "block";
                console.log("yooo yooo yoo")
            }
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
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