<!DOCTYPE html>
<html lang="sl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- jQuery -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

<!-- Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/editHoliday.css')}}">
    <title>Uredi dopust</title>
</head>
<body>
    @include('navbar')
    <section class="row w-100">
        @include('flash')
        <h1 class="text-center">{{$holiday->name." ".$holiday->last_name. " ". \Carbon\Carbon::parse($holiday->from_date)->format("d.m.Y"). " - ". \Carbon\Carbon::parse($holiday->to)->format("d.m.Y")}}</h1>
        <form id="userData" action="{{route('updateUserHoliday', $holiday->id)}}" method="POST">
            @csrf
            <div class="mb-3 text-dark">
                <label for="from">Od</label>
                <input type="date" id="from" name="from" value="{{\Carbon\Carbon::parse($holiday->from_date)->format("Y-m-d")}}">
            </div>
            <div class="mb-3">
                <label for="to">Do</label>
                <input type="date" id="to" name="to" value="{{\Carbon\Carbon::parse($holiday->to)->format("Y-m-d")}}">
            </div>
            <div class="mb-3 text-dark">
                <label for="days">Dni</label>
                <input type="number" id="days" min="1" name="days" value="{{$holiday->days}}">
            </div>
            <div class="row">
                <button class="btn btn-sm btn-primary">Potrdi</button>
            </div>
        </form>
        <div class="row w-100">
            <form action="/deleteUserHoliday/{{$holiday->id}}" method="POST">
                @csrf
                @method('DELETE')
                   <button type="submit" class="btn btn-sm btn-danger">Izbriši</button>
            </form>
        </div>
        <table class="table table-bordered border-2 table-dark text-center text-light">
            <thead>
                <th>Ime</th>
                <th>Vrednost</th>
            </thead>
            <tbody>
                @foreach ($holiday->toArray() as $key => $value)
                <tr>
                    @if($key != 'password')
                    <td>{{$key}}</td>
                    <td>{{$value}}</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
    <script>
        let data = @json($holiday);
        console.log(data);
    </script>
</body>
</html>