<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="{{asset('css/employee.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/header.css')}}">
    <title>Dodaj delavca</title>
</head>

<body>
    @include('navbar')
        <div class="container text-center mt-5">
            @include('flash')
                <div class="text-center mt-3 mb-5">
                    <h1>Dodaj delavca</h1>
                </div>
                <div class="add col col-md-8 ms-auto me-auto mb-5">
                    <form action="{{route('users.addEmployee')}}" method="post">
                        @csrf
                        <div class="mb-2">
                            <label for="username">Ime</label><br>
                            <input type="text" name="name" id="username" autocomplete="off">
                        </div>

                        <div class="mb-2">
                            <label for="lastname">Priimek</label><br>
                            <input type="text" name="last_name" id="lastname" autocomplete="off">
                        </div>

                        <div class="mb-2">
                            <label for="email">E-naslov</label><br>
                            <input type="email" name="email" id="email" autocomplete="off"><br>
                            <span class="m-2 p-1" id="email-exists" style="display:none; color:red; background-color:rgb(26, 25, 25)">Email že obstaja!</span>
                        </div>
                        <div class="mb-2">
                            <label for="user_name">Uporabniško ime</label><br>
                            <input type="text" name="user_name" id="user_name" autocomplete="off" required><br>
                            <span class="m-2 p-1" id="user-exists" style="display:none; color:red; background-color:rgb(26, 25, 25)">Uporabniško ime že obstaja</span>
                        </div>
                        <div class="mb-2">
                            <label for="password">Geslo</label><br>
                            <input type="password" name="password" id="password">
                        </div>
                        <div class="mb-2">
                            <label for="password">Potrdi Geslo</label><br>
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Potrdi geslo">
                        </div>
                        <div class="mb-2">
                            <label for="emplStatus">Status delavca:</label><br>
                            <select class="form-select form-select-sm text-center ms-auto me-auto" id="emplStatus"
                                aria-label=".form-select-sm example" name="working_status">
                                <option selected>Izber status</option>
                                <option value="zaposlen/a">Zaposlen/a</option>
                                <option value="študent">Študent</option>
                                <option value="upokojenec">Upokojenec</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label for="status">Status</label><br>
                            <select class="form-select form-select-sm text-center ms-auto me-auto" id="status"
                                aria-label=".form-select-sm example" name="status">
                                <option selected>Izberi status</option>
                                <option value="active">Aktiven</option>
                                <option value="inactive">Neaktiven</option>
                            </select>
                        </div>
                        @if(Auth::user()->role !== 'visitor')
                            <button class="btn btn-success mt-2">Dodaj</button>
                        @else
                            <button class="btn btn-success mt-2" disabled="true">Dodaj</button>
                        @endif
                    </form>
                </div>
                <a href="/users"><button class="btn btn-dark mt-2">Nazaj</button></a>
        </div>





        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
            integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
            integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
            crossorigin="anonymous"></script>
            <script>
                $(document).ready(function() {
                    var existingEmails = @json($emails);
                    var existingusername = @json($userName);
        
                    $('#email').on('keyup', function() {
                        var email = $(this).val();
                        console.log(existingEmails);
                        if (existingEmails.includes(email)) {
                            $('#email-exists').show();
                        } else {
                            $('#email-exists').hide();
                        }
                    });
                    $('#user_name').on('keyup',function(){
                        var username = $(this).val();
                        if(existingusername.includes(username)){
                            $('#user-exists').show();
                        }else{
                            $('#user-exists').hide();
                        }
                    })
                });
            </script>
</body>

</html>