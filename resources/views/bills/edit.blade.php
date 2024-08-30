<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
        <link rel="stylesheet" href="{{asset('css/header.css')}}">
        <link rel="stylesheet" href="{{asset('css/edit.css')}}">
    <title>Uredi</title>
</head>

<body style="background-color: rgb(4, 1, 30);">

    @include('navbar')
    <div class="flash">
        @include('flash')
    </div>

        <div class="text-center mt-3">
            <h1 class="text-info">Uredi račun:</h1>
            <h2 class=" fs-2 text-primary">
              @if(Auth::user()->role != 'visitor')  {{$bill->buyer}} @else / @endif
            </h2>
        </div>
        <div id="container" class="container justify-content-center">
            <div id="tableData" class="buyed-products text-center">
                <h3 class="ms-5 mt-5 mb-2">Kupljeni produkti</h3>
                <form action="{{route('updateProducts', $bill->id)}}" method="POST">
                    @csrf
                    <table id="productsTable" class="table table-dark table-hover text-center">
                        <thead>
                            <th>Produkt</th>
                            <th>Količina</th>
                            <th>Cena</th>
                            <th>Neto</th>
                            <th>Brezplačen</th>
                        </thead>

                        <tbody>
                            @foreach ($products as $product )
                                <tr>
                                    <td>
                                        <input type="text" id="productId" name="productId"
                                            value="<%= products->id %>" hidden>
                                        <input type="text" id="productName" name="name[]"
                                            class=" text-center bg-dark" value="{{$product->name}}" onchange="calculate()">
                                    </td>
                                    <td>
                                        <input type="number" id="productQty" name="qty[]"
                                            class="w-50 text-center bg-dark" min="1" value="{{$product->qty}}" onchange="calculate()">

                                    </td>
                                    <td>
                                        <input type="text" id="productPrice" name="price[]"
                                            class="w-75 text-center bg-dark" value="{{$product->price}}" onchange="calculate()">
                                        &euro;
                                    </td>
                                    <td>
                                        <input type="text" id="productTotal" name="total[]"
                                            class="w-75 text-center bg-dark" value="{{$product->total}}" onchange="calculate()">
                                        &euro;
                                    </td>
                                    <td>
                                        @if ($product->firstOfWeek === 1 )
                                            <img src="{{asset('img/payed.jpg')}}" alt="Payed">
                                            <input type="checkbox" id="firstOffWeek" checked style="display: none">
                                        @else
                                            <img src="{{asset('img/notPay.jpg')}}" alt="Not Payed">
                                            <input type="checkbox" id="firstOffWeek" style="display: none">
                                        @endif
                                    </td>

                                </tr>
                                @endforeach
                                <tr>
                                    <td>Skupaj</td>
                                    <td></td>
                                    <td></td>
                                    <td><input type="text" class="w-75 text-center" name="payment" id="sum" value="{{$bill->total}}"> €</td>
                                    <td></td>
                                </tr>
                        </tbody>
                    </table>
                    @if(Auth::user()->role == 'visitor')
                    <button class="btn btn-warning" disabled>Potrdi</button>
                    @else
                    <button class="btn btn-warning">Potrdi</button>
                    @endif
                </form>
            </div>

            <div class="row bg-success d-flex justify-content-center text-center mt-5 mb-5">
                <div class="row bg-gray" id="col">
                    <div class="card justify-content-center text-center">
                        <form action="{{route('updateBill', $bill->id)}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-header text-center">
                                <!-- Hear put img -->
                            </div>
                            <div class="card-body justify-content-center" id="card-body">
                                <ul
                                    class="list-group list-group-flush flex-row row-cols-2 flex-wrap justify-content-center align-content-center">

                                    <li id="list" class="list-group-item col">
                                        <label for="pay">Plačano</label><br>
                                        @if($bill->payed == '1' )
                                            <input type="checkbox" id="pay" name="pay" class="form-check-input"
                                                value="" checked onclick="update()">
                                            <input type="checkbox" id="pay2" name="pay" class="form-check-input"
                                                value="false">
                                            @else
                                                <input type="checkbox" id="pay" name="pay"
                                                    class="form-check-input" value="" onclick="update()">
                                                <input type="checkbox" id="pay2" name="pay"
                                                    class="form-check-input" value="false">
                                            @endif
                                    </li>

                                    <li id="list" class="list-group-item col"><label for="kt">Koledarski
                                            teden</label> <br>
                                        <input type="text" id="kt" class="fs-5 text-dark col-6" name="kt"
                                            value="{{$bill->kt}}" required>
                                    </li>

                                    <li id="list" class="list-group-item">
                                        <label for="perMonth">Št / Mesec</label> <br>
                                        <input type="text" id="perMonth" class="fs-5 text-dark col-6"
                                            name="num_per_month" value="{{$bill->num_per_month}}">
                                    </li>

                                    <li id="list" class="list-group-item">
                                        <label for="month">Mesec</label> <br>
                                        <input type="text" id="month" class="fs-5 text-dark bg-light col-6"
                                            value="{{$bill->month}}" name="month">
                                    </li>

                                    <li id="list" class="list-group-item">
                                        <label for="perYear">Št / Leto</label> <br>
                                        <input type="text" id="perYears" class="fs-5 text-dark col-6"
                                            value="{{$bill->num_per_year}}" name="num_per_year">
                                    </li>

                                    <li id="list" class="list-group-item">
                                        <label for="year">Leto</label> <br>
                                        <input type="text" id="year" class="fs-5 text-dark col-6"
                                            value="{{$bill->year}}" name="year">
                                    </li>


                                    <li id="list" class="list-group-item">
                                        <label for="soldDate">Prodano</label> <br>
                                        <input type="text" id="soldDate" class="fs-5 text-dark col-6"
                                            value="{{\Carbon\Carbon::parse($bill->sold_date)->format('d.m.Y')}}" name="sold_date">
                                    </li>
                                    <li id="list" class="list-group-item">
                                        <label for="payDate">Plačano</label> <br>
                                        <input type="text" id="payDate" class="fs-5 text-dark col-6"
                                            value="{{$bill->pay_date !== null ? \Carbon\Carbon::parse($bill->pay_date)->format('d.m.Y') : " "}}" name="pay_date">
                                    </li>


                                    <li id="list" class="list-group-item">
                                        <label for="izdal">Izdal:</label> <br>
                                        <input type="text" id="izdal" class="fs-5 text-dark"
                                            value="{{Auth::user()->role != 'visitor' ? $bill->published : "/"}}" name="published">
                                    </li>

                                </ul>
                            </div>

                            <div class="card-footer d-flex mt-2 mb-2 justify-content-evenly">
                                <button class="btn btn-outline-warning" onclick="update()" {{Auth::user()->role == 'visitor' ? "disabled" : " "}}>Potrdi</button>

                                <a href="/all/view/{{$bill->id}} " class="card-link d-inline btn btn-success"><strong
                                        class="text-light">Nazaj</strong></a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>




            <script>
                document.getElementById('pay2').style.display = 'none';
                //document.getElementById('hiddenInput').style.display = 'none';
                function update() {
                    let payed = document.getElementById('pay');
                    if (payed.checked) {
                        document.getElementById('pay').setAttribute('value', 'true');
                        document.getElementById('pay').checked = true;
                    } else {
                        document.getElementById('pay2').setAttribute('value', 'false');
                        document.getElementById('pay2').checked = true;
                    }

                }

                function calculate(){
                    let productsTables = document.getElementById('productsTable');
                    let rows = productsTables.rows.length - 2;
                    
                    let qty = document.querySelectorAll('#productQty');
                    let price = document.querySelectorAll('#productPrice');
                    let neto = document.querySelectorAll('#productTotal');
                    let checkBox = document.querySelectorAll('#firstOffWeek');
                    let billPrice = 0;
                    for (let i = 0; i < rows; i++) {
                        if(checkBox[i].checked){
                            if(qty[i].value < 2){
                                neto[i].value = 0.00;
                            }else{
                                let updatedtQty = qty[i].value - 1;
                                let calculatePrice = parseFloat(price[i].value) + 1.5;
                                let totalPrice = calculatePrice * updatedtQty;
                                neto[i].value = totalPrice.toFixed(2);
                            }
                        }else{
                            let calculatePrice = parseFloat(price[i].value) + 1.5;
                            let totalPrice = parseFloat(calculatePrice) * qty[i].value;
                            neto[i].value = totalPrice.toFixed(2);
                        }
                        billPrice = parseFloat(billPrice) + parseFloat(neto[i].value);
                    }
                    document.getElementById('sum').value = billPrice.toFixed(2);
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