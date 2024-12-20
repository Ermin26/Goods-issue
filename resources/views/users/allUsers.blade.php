<!DOCTYPE html>
<html lang="sl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/allUsers.css')}}">
    <title>Vsi uporabniki</title>
</head>

<body>
    @include('navbar')
    <div class="containerr mt-5">
                @include('flash')
                <div id="row" class="row row-cols-2 ms-auto me-auto w-100">

                    <div id="col" class="col1 col text-center">
                        <div class="users mt-3">
                            <div>
                                <h1>Uporabniki</h1>
                            </div>
                            <table class="table table-dark table-hover table-bordered border-dark align-middle text-light">
                                <thead id="usersTable">
                                    <tr>
                                        <th>Ime</th>
                                        <th>Role</th>
                                        <th>Uredi</th>
                                        <th>Izbriši</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forEach($users as $user)
                                        <tr>
                                            <td>
                                                    {{Auth::user()->role != 'visitor' ? $user->name : "/"}}
                                            </td>
                                            <td>
                                                {{$user->role}}
                                            </td>
                                                <td>
                                                        @if(Auth::user()->role === 'admin')
                                                            <a href="users/edit/{{$user->id}}"><button class="btn btn-warning">Uredi</button></a>
                                                        @else
                                                            <a href="/users" disabled><button class="btn btn-warning" disabled>Uredi</button></a>
                                                        @endif
                                                </td>
                                                <td>
                                                    <form action="{{route('users.destroy', $user->id)}}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                            @if(Auth::user()->role === 'admin')
                                                                <button class="btn btn-danger">Izbriši</button>
                                                            @else
                                                                <button class="btn btn-danger" disabled>Izbriši</button>
                                                            @endif
                                                    </form>
                                                </td>
                                        </tr>
                                        @endforEach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="col" class="col text-center col2">
                        <div class="employees mt-3">
                            <div>
                                <h1>Zaposleni</h1>
                            </div>
                            <div id="tableEmployee">
                                <table id="employeeTable" class="table table-bordered border-dark align-middle">
                                    <thead id="employeeThead" class="align-middle">
                                        <tr>
                                            <th>#</th>
                                            <th>Ime</th>
                                            <th>Priimek</th>
                                            <th>Zaposlitev</th>
                                            <th>Status</th>
                                            <th>Uredi</th>
                                            <th>Izbriši</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($employees)
                                            @foreach ($employees as $employee)
                                                <tr>
                                                    <td></td>
                                                    <td>{{Auth::user()->role != 'visitor' ? $employee->name : "/"}}</td>
                                                    <td>{{Auth::user()->role != 'visitor' ? $employee->last_name : "/"}}</td>
                                                    <td>{{$employee->working_status}}</td>
                                                    <td>@if($employee->status === 1)
                                                            <span class="status bg-success"></span>
                                                        @else
                                                            <span class="status bg-danger"></span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(Auth::user()->role === 'admin')
                                                            <a href="users/employee/{{$employee->id}}" class="btn btn-warning">Uredi</a>
                                                        @else
                                                            <a href="#"><button class="btn btn-warning" disabled>Uredi</button></a>
                                                        @endif
                                                    </td>
                                                    <td><form action="{{route('users.deleteEmployee', $employee->id)}}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        @if(Auth::check())
                                                            @if(Auth::user()->role === 'admin')
                                                                <button class="btn btn-danger">Izbriši</button>
                                                            @else
                                                                <button class="btn btn-danger" disabled>Izbriši</button>
                                                            @endif
                                                        @endif
                                                    </form></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <script>

                let employee = document.getElementById('employeeTable')
                let employeeRows = employee.rows.length - 1
                let number = 0

                for (let i = 1; i <= employeeRows; i++) {
                    let status = document.getElementById('employeeTable').rows[i].cells[4].innerText;
                    number += 1
                    employee.rows[i].cells[0].innerHTML = number;
                }

            </script>


            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
                integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
                crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
                integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
                crossorigin="anonymous"></script>

</body>

</html>