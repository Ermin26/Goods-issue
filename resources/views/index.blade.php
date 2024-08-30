<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/notification.css')}}">
    <link rel="stylesheet" href="{{asset('css/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <title>Novi račun</title>
</head>
<body>
    @include('navbar')
    <section id="main">
    @include('flash')
    <form action="{{route('create.newBill')}}" method="POST">
        @csrf
        <section class="topSection">
            <h2>Providio, 2000 Maribor</h2>
            <hr>
                <article class="izdal">
                    <p>Izdal:</p>
                    <div class="mb-2">
                        <label for="izdal">Delavec: </label>
                        <input type="text" id="izdal" name="published" value="Ermin Joldić">
                    </div>
                    <p>Podpis: ____________________________________</p>
                </article>
                <section id="numbersSection" class="row row-cols-2">
                    <article class="numbers">
                        <div class="mb">
                            <label id="numPerYear">Številka/Leto:</label>
                            <input type="text" class="form-control" id="numPerYear" name="num_per_year" value="{{$numYear}}">/
                            <input class="form-control" id="year" name="year" value="<?php echo date('Y')?>">
                        </div>
                        <div class="mb">
                            <label id="numPerMonth">Številka/Mesec:</label>
                            <input type="number" class="form-control" id="numPerMonth" name="num_per_month" value="{{$numMonth}}">/
                            <input class="form-control" type="number" id="month" name="month" value="<?php echo ltrim(date('m'), '0')?>">
                        </div>
                        <div class="mb">
                            <label id="kt">Koledarski teden:</label>
                            <input type="number" min="1" max="52" class="form-control" id="kt" name="kt" value="<?php echo date('W')?>">
                        </div>
                    </article>
                    <article class="dates">
                        <ul>
                            <li>Kraj: Maribor</li>
                            <li><label for="soldDate">Izdano:</label>
                                <input type="text" id="soldDate" name="sold_date" value="<?php echo date('d.m.Y')?>" contenteditable="true">
                            </li>
                            <li><label for="payedDate">Plačano:</label>
                                <input type="text" id="payedDate" name="payedDate" value="<?php echo date('d.m.Y')?>" contenteditable="true">
                            </li>
                        </ul>
                    </article>
                </section>
        </section>
        <section class="btnSection">
            <div id="btns" class="row d-flex flex-row flex-wrap">
                <div class="btn btn-outline-primary d-inline" onclick="addRow()">Dodaj izdelek</div>
                <div class="btn btn-outline-primary d-inline" onclick="zakljuci()">Zaključi</div>
                <div class="btn btn-outline-primary d-inline" onclick="placaj()">Plačilo</div>
            </div>
        </section>
        <section class="tableSection">
            <table id="productsTable" class="table table-borderless table-responsive">
                <thead>
                    <tr id="homeTable" class="border-bottom border-dark">
                        <th>Izdelek</th>
                        <th>Količina</th>
                        <th>Cena</th>
                        <th>DDV</th>
                        <th>Free</th>
                        <th>Neto</th>
                        <th>Plačano</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="mt-5 border border-start-0 border-end-0 border border-dark" id="cash">
                        <td class="text-center">Za plačilo</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td id="placilo"><input type="text" class="form-control" name="payment" id="payment" value="" placeholder=" 0.00 €"></td>
                        <td class="checkBoxes">
                            <input type="checkbox" id="pay" name="pay" value="">
                            <input type="checkbox" id="pay2" name="pay" value="false">
                            <img src="{{asset('img/payed.jpg')}}" alt="Plačano" id="imgPayed" style="display: none">
                            <img src="{{asset('img/notPay.jpg')}}" alt="Ni Plačano" id="imgNotPayed" style="display: none">
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
        <section class="buyer">
            <ul>
                <li>
                    <label for="buyer">Ime prejemnika: </label>
                    <input type="text" name="buyer" id="buyer" value="" placeholder="Kupec">
                </li>
                <li>
                    <h5>PODPIS KUPCA</h5>
                    <h4>________________________________</h4>
                </li>
            </ul>
        </section>
        <div class="row">
            <button class="btn btn-success printBtn" type="submit" value="submit" onclick="window.print()">Print</button>
        </div>
    </form>
</section>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

<script>
    document.getElementById("placilo").style.width = "85px";
            document.getElementById("placilo").style.backgroundColor = "white";

            document.getElementById('pay2').style.display = 'none';
            document.getElementById('imgPayed').style.display = 'none';
            document.getElementById('imgNotPayed').style.display = 'none';

            function addRow() {
                const table = document.getElementById('productsTable')
                const row = table.insertRow(1);
                const cell1 = row.insertCell(0);
                const cell2 = row.insertCell(1);
                const cell3 = row.insertCell(2);
                const cell4 = row.insertCell(3);
                const cell5 = row.insertCell(4);
                const cell6 = row.insertCell(5);
                const cell7 = row.insertCell(6);
                cell1.innerHTML = '<input type="text" id ="izdelek" class="form-control text-center ms-auto me-auto" name="product[]" value="" placeholder="Product" required>';
                cell2.innerHTML = '<input type="text" id ="kolicina" class="form-control text-center ms-auto me-auto" name="qty[]" value="" placeholder="1" required>';
                cell3.innerHTML = '<input type="text" id="cena" class="col form-control text-center ms-auto me-auto" name="price[]" value="" placeholder="0.00" required>';
                cell4.innerHTML = '1.50 €';
                cell5.innerHTML = '<input class="form-check-input" type="checkbox" value="true" id="freeProduct" name="firstOfWeek[]"> <input class="form-check-input" type="checkbox" value="false" id="hiddenInput" name="firstOfWeek[]"> <img src="{{asset('img/payed.jpg')}}" alt="Payed" id="freChecked" class="ms-auto me-auto"> <img src="{{asset('img/notPay.jpg')}}" alt="Not Payed" id="freeNotChecked" class="ms-auto me-auto">';
                cell6.innerHTML = '<input type="text" class="form-control text-center ms-auto me-auto" id="total" name="total[]" value="" placeholder="0.00 €" readonly>';
                cell7.innerHTML = '<input id="rowDelete" type="button" class="deleteDep btn btn-sm btn-danger" value="Delete" onclick="deleteOneRow(this)">';

                cell4.id = "ddv"
                cell5.id = "free"
                cell6.id = "cell6"
                row.classList.add('border-bottom', 'border-dark')
                document.getElementById('hiddenInput').style.display = 'none';
                document.getElementById('freChecked').style.display = 'none';
                document.getElementById('freeNotChecked').style.display = 'none';
            }

            function deleteOneRow(r){
                var i = r.parentNode.parentNode.rowIndex;
                console.log(i)
            document.getElementById("productsTable").deleteRow(i);
            }

            function zakljuci() {

                const kolicina = parseFloat(document.getElementById('kolicina').value);
                const cena = parseFloat(document.getElementById('cena').value);
                const free = document.querySelector('#freeProduct');
                const ddv = parseFloat(document.getElementById('ddv').innerHTML);

                document.getElementById('kolicina').setAttribute('value', kolicina);

                if (free.checked) {
                    if (kolicina > 1) {
                        const cena1 = kolicina * (cena + ddv);
                        const cena2 = 1 * (cena + ddv);
                        let y = parseFloat(cena1) - parseFloat(cena2);
                        document.getElementById('total').innerHTML = y;
                        document.getElementById('total').setAttribute('value', y.toFixed(2));
                        document.getElementById('freeProduct').setAttribute('value', 'true')
                        document.getElementById('freeProduct').checked = true;
                        document.getElementById('freeProduct').style.display = 'none';
                        document.getElementById('freChecked').style.display = 'block';

                    } else {
                        document.getElementById('total').innerHTML = 0.00;
                        document.getElementById('total').setAttribute('value', 0.00);
                        document.getElementById('freeProduct').setAttribute('value', 'true')
                        document.getElementById('freeProduct').checked = true;
                        document.getElementById('freeProduct').style.display = 'none';
                        document.getElementById('freChecked').style.display = 'block';
                    }
                    //document.getElementById('hiddenInput').setAttribute('value', 'false')
                }
                else {
                    const cena1 = kolicina * (cena + ddv);
                    document.getElementById('total').innerHTML = cena1.toFixed(2);
                    document.getElementById('total').setAttribute('value', cena1.toFixed(2));
                    //document.getElementById('freeProduct').setAttribute('value', 'false');
                    document.getElementById('hiddenInput').setAttribute('value', 'false')
                    document.getElementById('hiddenInput').checked = true;
                    document.getElementById('freeProduct').style.display = 'none';
                    document.getElementById('freChecked').style.display = 'none';
                    document.getElementById('freeNotChecked').style.display = 'block';
                }
            }

            function placaj() {

                let taable = document.getElementById('productsTable');
                let rows = taable.rows.length - 2;
                let payed = document.getElementById('pay');
                let pay = 0;
                for (let i = 1; i <= rows; i++) {
                    var x = document.getElementById('productsTable').rows[i].cells[5].children[0].value;
                    pay += parseFloat(x, 10);

                }
                //document.getElementById('payment').innerHTML = pay.toFixed(2);
                document.getElementById('payment').setAttribute('value', pay.toFixed(2));
                if (payed.checked) {
                    document.getElementById('pay').setAttribute('value', 'true');
                    document.getElementById('pay').checked = true;
                    document.getElementById('pay2').setAttribute('disabled', 'true');
                    document.getElementById('pay').style.display = 'none';
                    document.getElementById('imgPayed').style.display = 'block';
                } else {
                    document.getElementById('pay2').setAttribute('value', 'false');
                    document.getElementById('pay2').checked = true;
                    document.getElementById('pay2').style.display = 'none';
                    document.getElementById('pay').setAttribute('disabled', 'true');
                    document.getElementById('pay').style.display = 'none';
                    document.getElementById('imgNotPayed').style.display = 'block';
                    document.getElementById('payedDate').innerHTML = ""+""+"";
                    document.getElementById('payedDate').setAttribute('value', ""+""+"");

                }

            }
</script>

</body>
</html>