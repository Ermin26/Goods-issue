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
    <link rel="stylesheet" href="{{asset('css/header.css')}}">
    <title>
        {{$bill->buyer}}
    </title>
</head>

<body>
    @include('navbar')
    <div id="userBill" class="text-center">
            @include('flash')
                <h1 class="mt-3 text-center">
                    @if(Auth::user()->role != 'visitor')
                     Ime kupca: {{$bill->buyer}}
                    @else
                            Ime kupca: <br>
                            <strong>/</strong>
                            <br>
                    @endif
                </h1>
    </div>
    <div class="mt-3" id="containerr">
        <table id="user" class="table table-dark table-hover mt-3">
            <thead class="table-data fs-4 text-center">
                <th>Produkt</th>
                <th>Količina</th>
                <th>Brezplačen</th>
                <th>Neto</th>
                <th>Skupaj</th>
                <th>Plačano</th>
                <th>Teden</th>
                <th>Prodano</th>
                <th>Plačeno</th>
                <th>Izdal</th>
                <th>Uredi</th>
                <th>Izbriši</th>
                <th>Re-Print</th>
            </thead>
            <tbody class="text-center">
                <tr>
                    <td>
                        @foreach($products as $product)
                        {{$product->name}}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach($products as $product)
                        {{$product->qty}}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach($products as $product)
                            @if($product->firstOfWeek =='1')
                                <img src="{{asset('img/payed.jpg')}}" alt="Free"><br>
                            @else
                                <img src="{{asset('img/notPay.jpg')}}" alt="Not Free"><br>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($products as $product)
                            {{$product->total}} &euro;
                        @endforeach
                    </td>
                    <td>{{$bill->total}} &euro;</td>
                    <td>
                        @foreach($products as $product)
                            @if($bill->payed =='1' )
                                <img src="{{asset('img/payed.jpg')}}" alt="Payed"><br>
                            @else
                                <img src="{{asset('img/notPay.jpg')}}" alt="Not Payed"><br>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        {{$bill->kt}}
                    </td>
                    <td>
                        {{\Carbon\Carbon::parse($bill->sold_date)->format('d.m.Y')}}
                    </td>
                    <td>
                        {{$bill->pay_date !== null ? \Carbon\Carbon::parse($bill->pay_date)->format('d.m.Y') : " "}}
                    </td>
                    <td>
                        {{Auth::user()->role == 'visitor' ? "/" : "$bill->published"}}
                    </td>
                    <td>
                        <a href="/all/edit/{{$bill->id}}"><button class="btn btn-warning btn-sm">
                                EDIT
                            </button>
                        </a>

                    </td>

                    <td>
                        <form action="/all/delete/{{$bill->id}}/?_method=DELETE" method="post">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" {{Auth::user()->role == 'visitor' ? "disabled" : ' '}}>DELETE</button>
                        </form>
                    </td>
                    <td>
                        <button class="btn btn-success btn-sm" id="hideOnPrint"
                            onclick=window.print() {{Auth::user()->role == 'visitor' ? "disabled" : ' '}}>Print</button>
                    </td>
                </tr>
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
                        value="{{$bill->num_per_year}}">
                    <input type="text" class="border border-white text-left" name="year" id="year"
                        value="{{$bill->year}}">

                    <br>
                    <label class="" for="numPerMonth">Številka/Mesec:
                    </label>
                    <input type="text" class="border border-white" name="numPerMonth" id="numPerMonth"
                        value="{{$bill->num_per_month}}">
                    <input type="text" class="border border-white" name="month" id="month" value="{{$bill->month}}">
                    <br>
                    <label for="kt">Koledarski Teden:</label>
                    <input type="text" class="border border-white" name="kt" id="kt" value="{{$bill->kt}}">
                </div>

                <div class="col mt-2" id="info">
                    <ul id="info-ul" class="d-flex flex-column">
                        <li class="d-inline">
                            <p id="kraj" class="d-inline">Kraj: Maribor</p>
                        </li>
                        <li class="d-inline">
                            <label for="payDate">Datum Plačila:</label>
                            @if($bill->pay_date)
                                <input type="text" class="border border-white" id="payDate" name="payDate"
                                    value="{{\Carbon\Carbon::parse($bill->pay_date)->format('d.m.Y')}}">
                            @else
                            <!--
                                <label for="payDate">Datum Plačila:</label>
                            -->
                                <input type="text" class="border border-white" id="payDate" name="payDate"
                                        value=" ">
                            @endif
                        </li>
                        <li class="d-inline">
                            <label for="soldDate">Datum Izdaje:</label>
                            <input type="text" class="d-inline border border-white ms-1" id="soldDate" name="soldDate"
                                value="{{\Carbon\Carbon::parse($bill->sold_date)->format('d.m.Y')}}">

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
                        @foreach($products as $product)
                            <tr class="mt-5 border border-start-0 border-end-0 border-dark" id="cash">
                                <td>
                                    {{$product->name}}
                                </td>
                                <td>
                                    {{$product->qty}}
                                </td>
                                <td>
                                    {{$product->price}}
                                </td>
                                <td>1.50</td>
                                <td>
                                    @if($product->firstOfWeek =='1' )
                                        <img src="{{asset('img/payed.jpg')}}" alt="Free">
                                    @else
                                        <img src="{{asset('img/notPay.jpg')}}" alt="Not Free">
                                    @endif
                                </td>
                                <td>
                                    {{$product->total}}
                                </td>
                            </tr>
                            @endforeach
                                <tr>
                                    <td>Plačano</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td id="payed">

                                    </td>
                                    <td>
                                        @if($bill->payed =='1' )
                                            <img src="{{asset('img/payed.jpg')}}" alt="Payed">
                                        @else
                                            <img src="{{asset('img/notPay.jpg')}}" alt="Not Payed">
                                        @endif
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
                            value="{{$bill->buyer}}">
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
