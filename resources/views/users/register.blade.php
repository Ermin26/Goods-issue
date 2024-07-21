<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="{{asset('css/register.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/allPages.css')}}">
    <title>Novi uporabnik</title>
</head>

<body>
    @include('navbar')
    @include('flash')
    <div class="container text-center mt-5 mb-5">

            <div class="info mt-5 mb-5">
                <h1 class="mb-3">Podatki o uporabniku</h1>
            </div>
            <div id="formField" class="col-8 ms-auto me-auto">
                <form action="{{route('users.createUser')}}" method="post">
                    @csrf
                    <div class="mb-2 d-flex flex-column col-4 ms-auto me-auto">
                        <label for="username">Ime:</label>
                        <input type="text" name="name" id="username">
                        <span id="user-exists" style="display:none; color:red; background-color:rgb(24, 23, 23)">Uporabnik že obstaja!</span>
                    </div>
                    <div class="mb-2 d-flex flex-column col-4 ms-auto me-auto">

                        <label for="password">Geslo:</label>
                        <input class="" type="password" name="password" id="password">
                    </div>
                    <div class="mb-2 col-4 ms-auto me-auto">
                        <label for="role">Role:</label>
                        <select class="form-select form-select-sm ms-auto me-auto" id="role"
                            aria-label=".form-select-sm example" name="role">
                            <option selected>Select role</option>
                            <option value="admin">Admin</option>
                            <option value="moderator">Moderator</option>
                            <option value="visitor">Visitor</option>
                        </select>
                    </div>
                    @if(Auth::user()->role !== 'visitor')
                        <button type="submit" id="submit" class="btn btn-success mt-2">Dodaj</button>
                    @else
                        <button type="submit" id="submit" class="btn btn-success mt-2" disabled="true">Dodaj</button>
                    @endif
                </form>
                <a href="/users"><button class="btn btn-dark mt-3">Nazaj</button></a>

            </div>
    </div>


        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
            integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
            integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
            crossorigin="anonymous"></script>

            <script>
                $(document).ready(function() {
                    var existingEmails = @json($users);
        
                    $('#username').on('keyup', function() {
                        var email = $(this).val();
        
                        if (existingEmails.includes(email)) {
                            $('#user-exists').show();
                            $('#submit').attr('disabled','true');
                        } else {
                            $('#user-exists').hide();
                            $('#submit').removeAttr('disabled','false');
                        }
                    });
                });
            </script>


</body>

</html>