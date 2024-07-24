<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/test.css')}}">
    <title>Employee name</title>
</head>
<body>
    @include('employees.nav')
    <main>
        @include('flash')

        <section id="racuni">
            @if(count($unpayedBills) > 0)
                <div id="notPayed">
                    <h2 class="bg-danger">Odprti računi za plačilo.</h2>
                    <table class="table table-responsive table-bordered border-1">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>KT</th>
                                <th>Mesec</th>
                                <th>Leto</th>
                                <th>Izdano</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>52</td>
                                <td>12</td>
                                <td>2024</td>
                                <td>25.07.2024</td>
                                <td>3.65 €</td>
                            </tr>
                            @foreach ($unpayedBills as $notPayed)
                                <tr>
                                    <td>{{$notPayed->id}}</td>
                                    <td>{{$notPayed->kt}}</td>
                                    <td>{{$notPayed->month}}</td>
                                    <td>{{$notPayed->year}}</td>
                                    <td>{{$notPayed->sold_date}}</td>
                                    <td>{{\Carbon\Carbon::parse($notPayed->total)->format('d.m.Y')}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td>Dolg</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td id="total"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <section id="vacationInfo">
            <h2>Stanje letnega dopust</h2>
            <table id="vacationTable" class="table table-responsive table-bordered border-1 border-light">
                <thead>
                    <tr>
                        <td>Lani</td>
                        <td>LD</td>
                        <td>Skupaj</td>
                        <td>Iskoriščeno</td>
                        <td>Preostalo</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($userVacations as $vacation)
                        <tr>
                            <td>{{$vacation->last_year}}</td>
                            <td>{{$vacation->holidays}}</td>
                            <td>{{$vacation->last_year + $vacation->holidays}}</td>
                            <td>{{$vacation->used_holidays}}</td>
                            <td>24</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <section id="holidays">
            <h2>Oddane vloge za dopust</h2>
            <div class="tableData">
                <table class="table table-responsive table-bordered border-1 border-light">
                    <thead>
                        <tr>
                            <td>Od</td>
                            <td>Do</td>
                            <td>Status</td>
                            <td>Uredi</td>
                            <td>Izbriši</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($userHolidays as $holiday)
                            <tr>
                                <td>{{\Carbon\Carbon::parse($holiday->from)->format('d.m.Y')}}</td>
                                <td>{{\Carbon\Carbon::parse($holiday->to)->format('d.m.Y')}}</td>
                                <td>{{$holiday->status}}</td>
                                <td><button class="btn btn-sm btn-warning">Uredi</button></td>
                                <td><button class="btn btn-sm btn-danger">Izbriši</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
            integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
            integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
            crossorigin="anonymous"></script>
</body>
</html>