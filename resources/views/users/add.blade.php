<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/employee.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/allPages.css')}}">
    <title>Add employee</title>
</head>

<body>
    @include('navbar')
        <div class="container text-center">
            @include('flash')
                <div class="text-center mt-3 mb-5">
                    <h1>Add new Employee</h1>
                </div>
                <div class="add col col-md-6 ms-auto me-auto">
                    <form action="/addEmploye" method="post">
                        <div class="mb-2">
                            <label for="username">Username</label><br>
                            <input type="text" name="username" id="username">
                        </div>

                        <div class="mb-2">
                            <label for="lastname">Lastname</label><br>
                            <input type="text" name="lastname" id="lastname">
                        </div>

                        <div class="mb-2">
                            <label for="email">email</label><br>
                            <input type="email" name="email" id="email">
                        </div>
                        <div class="mb-2">
                            <label for="password">Password</label><br>
                            <input type="password" name="password" id="password">
                        </div>
                        
                        <div class="mb-2">
                            <label for="emplStatus">Employment status:</label><br>
                            <select class="form-select form-select-sm text-center ms-auto me-auto" id="emplStatus"
                                aria-label=".form-select-sm example" name="emplStatus">
                                <option selected>Select status</option>
                                <option value="zaposlen/a">Zaposlen/a</option>
                                <option value="študent">Študent</option>
                                <option value="upokojenec">Upokojenec</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label for="status">Status</label><br>
                            <select class="form-select form-select-sm text-center ms-auto me-auto" id="status"
                                aria-label=".form-select-sm example" name="status">
                                <option selected>Select status</option>
                                <option value="active">Aktiven</option>
                                <option value="inactive">Neaktiven</option>
                            </select>
                        </div>
                            <button class="btn btn-success mt-2">Submit</button>
                    </form>
                </div>
                <a href="/users"><button class="btn btn-dark mt-2">Go Back</button></a>
        </div>





        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
            integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
            integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
            crossorigin="anonymous"></script>
</body>

</html>