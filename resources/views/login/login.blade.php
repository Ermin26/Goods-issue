<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="{{asset('css/header.css')}}">
        <link rel="stylesheet" href="{{asset('css/login.css')}}">
    <title>Login</title>
</head>

<body>
    @include('navbar')
    @include('flash')
        <div id="container" class="container mt-5 d-flex justify-content-center">
            <div class="row mt-2">
                <div class="col text-center">
                    <div class="card">
                        <form action="{{route('login.login')}}" method="post">
                            @csrf
                            <div class="card-header" id="image">
                            </div>
                            <div id="fieldsGroup" class="card-body text-center">
                                <div id="field" class="d-inline-flex p-2 text-center">
                                    <label id="label" for="username"
                                        class="d-inline-flex"><strong>Uporabniško ime:</strong></label>
                                    <input type="text" class="col d-inline-flex text-center" name="name" id="name"
                                        placeholder="Uporabniško ime">
                                </div>
                                <div id="field" class="d-inline-flex p-2 text-center">
                                    <label id="label" for="password" class="d-inline-flex"><strong>Geslo:
                                        </strong></label>
                                    <input type="password" class="col d-inline-flex text-center" name="password"
                                        id="password" placeholder="Vaše geslo">
                                </div>
                            </div>
                            <div class="card-footer text-center bg-transparent border-0">
                                <button class="btn btn-primary d-inline p-2">Submit</button>
                            </div>
                        </form>
                    </div>
                        <div id="contactAdmin" class="row d-inline-flex caption ms-auto me-auto text-center">
                            <p>Imate težave s prijavo?<a href="mailto:mb.providio@gmail.com"> Kontakt
                                    Admin.</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
            integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
            integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
            crossorigin="anonymous"></script>
</body>

</html>