<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
        <link rel="stylesheet" href="{{asset('css/allPages.css')}}">
        <link rel="stylesheet" href="{{asset('css/register.css')}}">
    <title>
        {{$employee->name}} - Edit
    </title>
</head>

<body>

    @include('navbar')

        <div class="container text-center mt-5">
            @include('flash')
                <div class="text-center mt-3 mb-2">
                    <h1> <strong class="text-primary">
                            {{$employee->name}}
                                {{$employee->last_name}}
                        </strong>
                    </h1>
                </div>

                <form class="border border-2 p-4" action="{{route('users.updateEmployee', $employee->id)}}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label for="username">Ime</label><br>
                        <input type="text" class="text-center" name="name" id="username"
                            value="@if(Auth::user()->role !== 'visitor'){{$employee->name}}@else/@endif">
                    </div>

                    <div class="mb-2">
                    <label for="lastname">Priimek</label><br>
                    <input type="text" name="last_name" id="lastname" class="text-center"
                        value="@if(Auth::user()->role !== 'visitor'){{$employee->last_name}}@else/@endif">
                    </div>
                    <div class="mb-2">
                        <label for="email">E-naslov</label><br>
                        <input type="email" class="text-center" name="email" id="email"value="@if(Auth::user()->role !== 'visitor'){{$employee->email}}@else/@endif">
                    </div>
                    <div class="mb-2">
                        <label for="user_name">Uporabniško ime</label><br>
                        <input type="text" class="text-center" name="user_name" id="user_name"
                            value="@if(Auth::user()->role !== 'visitor'){{$employee->user_name}}@else/@endif">
                    </div>
                    <div class="mb-2">
                        <label for="password">Geslo</label><br>
                        <input type="password" name="password" id="password" value="{{$employee->password}}">
                    </div>
                    <div class="mb-2">
                        <label for="emplStatus">Status zaposlenog:</label><br>
                        <select class="form-select form-select-sm ms-auto me-auto text-center" id="emplStatus"
                            aria-label=".form-select-sm example" name="working_status">
                            <option selected>
                                @if(Auth::user()->role !== 'visitor'){{$employee->working_status}}@else / @endif
                            </option>
                            <option value="zaposlen/a">Zaposlen/a</option>
                            <option value="študent">Študent</option>
                            <option value="upokojenec">Upokojenec</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label for="status">Status</label><br>
                        <select class="form-select form-select-sm text-center ms-auto me-auto" id="status"
                            aria-label=".form-select-sm example" name="status">
                            <option selected>
                                @if(Auth::user()->role !== 'visitor')@if($employee->status == 1) Aktiven @else Neaktiven @endif @else/@endif
                            </option>
                            <option value="active">Aktiven</option>
                            <option value="inactive">Neaktiven</option>
                        </select>
                    </div>

                    <button class="btn btn-success mt-2">Potrdi</button>
                </form>
                <a href="/users"><button class="btn btn-dark mt-2">Nazaj</button></a>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
            integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
            integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
            crossorigin="anonymous"></script>
</body>

</html>