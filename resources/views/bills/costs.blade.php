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
        <link rel="stylesheet" href="{{asset('css/header.css')}}">
        <link rel="stylesheet" href="{{asset('css/costs.css')}}">
    <title>Costs</title>
</head>

<body>
     @include('navbar')
     @include('flash')
     <div id="cols" class="row row-cols-2 w-100 ms-auto me-auto">
            <div id="col2" class="col-3 p-0 ms-auto me-auto">
                <table id="cashTable" class="table table-dark p-2 ms-auto me-auto text-center align-middle">
                    <thead>
                        <tr>
                            <th>Prihodki</th>
                            <th>Odhodki</th>
                            <th>Neto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="bg-info">
                                <strong id="payed">{{round($payedBills),2}} </strong> &euro;</h4>
                            </td>
                            <td class="bg-danger">
                                <strong id="spended"></strong> &euro;</h4>
                            </td>
                            <td class="bg-success">
                                <strong id="cash"></strong> &euro;</h2>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="addBills mt-5 mb-5 ms-auto me-auto text-center">
                    <button id="showFormBtn" class="btn btn-primary" onclick="showInputForm()">Dodaj račun</button>
                </div>
                <div id="hideMe">
                    <div id="addBillForm" class="col p-3 d-flex shadow text-center justify-content-center">
                        <form action="{{route('addCosts')}}" method="post" class="w-75 p-4 shadow">
                            @csrf
                            <div class="mb-2 text-center">
                                <label for="date" class="form-label">Datum nakupa:</label><br>
                                <input type="date" id="date" class="form-control text-center" name="date"
                                    placeholder="Enter Bill date" required>
                            </div>

                            <div class="mb-2 col">
                                <label for="buyedProducts" class="form-label">Produkti:</label>
                                <textarea type="text" id="buyedProducts" class="form-control text-center"
                                    name="buyedProducts" placeholder="Produkti" required></textarea>
                            </div>

                            <div class="mb-2">
                                <label for="totalPrice" class="form-label">Cena:</label>
                                <input type="text" id="totalPrice" class="form-control text-center" name="totalPrice"
                                    placeholder="Total &euro;" required>
                            </div>
                            @if(Auth::user()->role !== 'visitor')
                                <button class="btn btn-success mt-4 mb-4">Dodaj</button>
                            @else
                                <button class="btn btn-success mt-4 mb-4" disabled="true">Submit</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <div id="col1" class="col-8 text-center p-0">

                @if(!count($bills) > 0)

                    <h1>Ni podatkov o računih.</h1>

                @else

                        <div class="header mt-3 text-center">
                            <h1>Poraba denarja podjetja</h1>
                        </div>


                        <div id="bills">
                            <table id="tableOfCosts" class="table table-dark table-hover align-middle">
                                <tr>
                                    <thead id="table-data" class="align-middle">
                                        <th>#</th>
                                        <th>Datum računa</th>
                                        <th>Cena &euro;</th>
                                        <th>Produkti</th>
                                        <th>Vpisano</th>
                                        <th>Uporabnik</th>
                                        <th class="bg-danger">WARNING</th>
                                    </thead>
                                </tr>
                                @foreach($bills as $bill)
                                    <tr>
                                        <td></td>
                                        <td>
                                        {{\Carbon\Carbon::parse($bill->date)->format('d.m.Y')}}
                                        </td>

                                        <td style="text-wrap:nowrap">
                                        {{$bill->price}} &euro;
                                        </td>

                                        <td>
                                        {{$bill->products}}

                                        </td>
                                        <td>
                                        {{\Carbon\Carbon::parse($bill->booked_date)->format('d.m.Y')}}
                                        </td>
                                        <td>
                                        @if(Auth::user()->role !== 'visitor')
                                        {{$bill->users_name}}
                                        @else
                                        /
                                        @endif
                                        </td>
                                        <td>
                                            <form action="{{route('costs.deleteBill', $bill->id)}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                @if(Auth::user()->role !== 'visitor')
                                                    <button class="btn btn-danger btn-sm">Izbriši</button>
                                                @else
                                                    <button class="btn btn-danger btn-sm" disabled="true">Izbriši</button>
                                                @endif
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                        <tr>
                                            <td>Total:</td>
                                            <td></td>
                                            <td id="spend" class="bg-danger"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                            </table>
                        </div>
                @endif
            </div>

    </div>


        <script>

            document.getElementById("hideMe").style.display = "none";
            function showInputForm() {
                document.getElementById("hideMe").style.display = "block";
                document.getElementById("showFormBtn").style.display = "none";
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