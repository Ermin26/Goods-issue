<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/notification.css')}}">
    <link rel="stylesheet" href="{{asset('css/allPages.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <title>Document</title>
</head>
<body>
    @include('navbar')
    @include('flash')
<section id="main">
    <section class="topSection">
        <h2>Providio, 2000 Maribor</h2>
        <hr>
        <article class="izdal">
            <p>Izdal:</p>
            <div class="mb-2">
                <label for="izdal">Delavec: </label>
                <input type="text" id="izdal" name="izdal" value="Ermin Joldić">
            </div>
            <p>Podpis: ____________________________________</p>
        </article>
        <section id="numbersSection" class="row row-cols-2">
            <article class="numbers">
                <div class="mb">
                    <label id="numPerYear">Številka/Leto:</label>
                    <input type="text" class="form-control" id="numPerYear" name="numPerYear" value="117">/ <span><?php echo date('Y')?></span>
                </div>
                <div class="mb">
                    <label id="numPerMonth">Številka/Mesec:</label>
                    <input type="text" class="form-control" id="numPerMonth" name="numPerMonth" value="23">/ <span><?php echo date('m')?></span>
                </div>
                <div class="mb">
                    <label id="kt">Koledarski teden:</label>
                    <input type="text" class="form-control" id="kt" name="kt" value="<?php echo date('W')?>">
                </div>
            </article>
            <article class="dates">
                <ul>
                    <li>Kraj: Maribor</li>
                    <li><label for="soldDate">Izdano:</label>
                        <input type="text" id="soldDate" name="soldDate" value=" <?php echo date('d.m.Y') ?>" contenteditable="true">
                    </li>
                    <li><label for="payedDate">Plačano:</label>
                        <input type="text" id="payedDate" name="payedDate" value=" <?php echo date('d.m.Y') ?>" contenteditable="true">
                    </li>
                </ul>

            </article>
        </section>
    </section>
    <section class="btnSection">
        <div id="btns" class="row d-flex flex-row flex-wrap">
            <button class="btn btn-outline-primary d-inline" type="submit">Dodaj izdelek</button>
            <button class="btn btn-outline-primary d-inline" type="submit">Zaključi</button>
            <button class="btn btn-outline-primary d-inline" type="submit">Plačilo</button>
        </div>
    </section>
    <section class="tableSection">
        <table class="table table-borderless table-responsive">
            <thead>
                <tr>
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
                    <td id="placilo"><input type="text" class="form-control" id="payment" value="" placeholder=" 0.00 €"></td>
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
        <button class="btn btn-success printBtn">Print</button>
    </div>
</section>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>


</body>
</html>