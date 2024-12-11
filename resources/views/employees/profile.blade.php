<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/test.css')}}">
    <title>Uredi profil</title>
</head>
<body>
    @include('employees.nav')
    <main>
        @include('flash')
        <section id="profile">
            <h2>Uredi podatke</h2>
            <form action="{{route('editProfile', $myProfile->id)}}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="username">Uporabniško ime</label>
                    <input type="text" name="username" id="username" value="{{$myProfile->user_name}}">
                </div>
                <div class="mb-3">
                    <label for="email">E-naslov</label>
                    <input type="email" name="email" id="email" value="{{$myProfile->email}}">
                </div>
                <div class="mb-3">
                    <label for="password">Geslo</label>
                    <input type="password" name="password" id="password" placeholder="Pusti prazno će ne želiš spreminjati geslo">
                </div>
                <div class="mb-2">
                    <label for="password">Potrdi Geslo</label><br>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Potrdi geslo">
                </div>
                    <button class="btn btn-sm btn-warning">Posodobi</button>
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