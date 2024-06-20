<!DOCTYPE html>
<html lang="sl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
        <link rel="stylesheet" href="{{asset('css/allPages.css')}}">
    <title>
        {{$user->name}}
    </title>
</head>

<body>
    @include('navbar')
    @include('flash')
        <div class="container text-center mb-5">
            <div class="info mt-5 mb-5">
                <h1 class="mb-3">Edit data for user {{$user->name}}
                </h1>
            </div>
            <div class="col-8 text-center ms-auto me-auto">
                <form action="{{route('users.update', $user->id)}}" method="post">
                    @csrf
                    <div class="mb-2 bg-warning col-4 d-flex flex-column text-center ms-auto me-auto">
                        <label for="name">Username:</label>
                        <input type="text" name="name" id="name" value="{{$user->name}}">
                    </div>

                    <div class="mb-2 bg-warning col-4 d-flex flex-column ms-auto me-auto">
                        <label for="role">Role:</label>
                        <select class="form-select form-select-sm ms-auto me-auto" id="role"
                            aria-label=".form-select-sm example" name="role">
                            <option selected>
                                {{$user->role}}
                            </option>
                            <option value="admin">Admin</option>
                            <option value="moderator">Moderator</option>
                            <option value="visitor">Visitor</option>
                        </select>
                    </div>
                    @if(Auth::check())
                        @if(Auth::user()->role === 'admin')
                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                        @else
                            <button type="submit" class="btn btn-primary btn-sm" disabled>Submit</button>
                        @endif
                    @endif
                </form>
                <a href="/users"><button class="btn btn-dark btn-sm mt-2">Go Back</button></a>

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