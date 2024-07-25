<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/test.css')}}">
    <title>Uredi dopust</title>
</head>
<body>
    @include('employees.nav')
    <main>
        @include('flash')
        <section id="updateForm">
            <form action="{{route('updateHoliday', $holiday->id)}}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="from">Prvi dan dopusta</label>
                    <input type="date" class="text-center" name="from" id="from" value="{{\Carbon\Carbon::parse($holiday->from)->format('Y-m-d')}}">
                </div>
                <div class="mb-3">
                    <label for="to">Zadnji dan dopusta</label>
                    <input type="date" class="text-center" name="to" id="to" value="{{\Carbon\Carbon::parse($holiday->to)->format('Y-m-d')}}">
                </div>
                <div class="mb-3">
                    <label for="days">Število dni</label>
                    <input type="number" class="text-center" name="days" id="days" value="{{$holiday->days}}" required>
                </div>
                <div class="row">
                    <button class="btn btn-primary">Potrdi</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>