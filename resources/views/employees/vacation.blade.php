<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/test.css')}}">
    <title>Dopust</title>
</head>
<body>
    @include('employees.nav')

    <main>
        <section id="vacation">
            <div class="holidaysInfo">
                <h3 class="text-center text-light p-2">Lanski dopust {{$vacation->last_year}}<br>Letni dopust {{$vacation->holidays}}</h3>
            </div>
            <form action="{{route('newHoliday')}}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="from">Prvi dan dopusta</label>
                    <input type="date" class="text-center" name="from" id="from">
                </div>
                <div class="mb-3">
                    <label for="to">Zadnji dan dopusta</label>
                    <input type="date" class="text-center" name="to" id="to">
                </div>
                <div class="mb-3">
                    <label for="days">Število dni</label>
                    <input type="number" class="text-center" name="days" id="days" required>
                </div>
                <div class="mb-3">
                    <label for="status">Status</label>
                    <input type="text" class="text-center" name="status" id="status" value="Pending" readonly>
                </div>
                <div class="row">
                    <button class="btn btn-primary d-flex flex-row ms-auto me-auto w-50 justify-content-center">Oddaj</button>
                </div>
            </form>
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