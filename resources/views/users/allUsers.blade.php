<!DOCTYPE html>
<html lang="sl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/allpages.css')}}">
    <link rel="stylesheet" href="{{asset('css/allUsers.css')}}">
    <title>Vsi uporabniki</title>
</head>

<body>
    @include('navbar')
        @include('flash')
            <div class="containerr">
                <div id="row" class="row row-cols-2 ms-auto me-auto w-100">

                    <div id="col" class="col1 col text-center">
                        <div class="users mt-3">
                            <div>
                                <h1>Uporabniki</h1>
                            </div>
                            <table class="table table-info table-hover table-bordered border-dark align-middle">
                                <thead id="usersTable">
                                    <tr>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forEach($users as $user)
                                        <tr>
                                            <td>
                                                @if(Auth::check())
                                                    @if(Auth::user()->role != 'visitor' || Auth::user()->role == 'jan')
                                                    {{$user->name}}
                                                    @else /
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                {{$user->role}}
                                            </td>
                                                <td>
                                                    @if(Auth::check())
                                                        @if(Auth::user()->role === 'admin')
                                                            <a href="/users/edit/{{$user->id}}"><button class="btn btn-warning">Edit</button></a>
                                                        @else
                                                            <a href="/users" disabled><button class="btn btn-warning" disabled>Edit</button></a>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    <form action="/users/{{$user->id}}/?_method=DELETE" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        @if(Auth::check())
                                                            @if(Auth::user()->role === 'admin')
                                                                <button class="btn btn-danger">DELETE</button>
                                                            @else
                                                                <button class="btn btn-danger" disabled>DELETE</button>
                                                            @endif
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
                                            <th>Name</th>
                                            <th>Last name</th>
                                            <th>Employment status</th>
                                            <th>Status</th>
                                            <th>Edit</th>
                                            <th>DELETE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($employees)
                                            @foreach ($employees as $employee)
                                                <tr>
                                                    <td></td>
                                                    <td>{{$employee->name}}</td>
                                                    <td>{{$employee->last_name}}</td>
                                                    <td>{{$employee->working_status}}</td>
                                                    <td>{{$employee->status === 1 ? 'Aktiven' : 'Neaktiven'}} </td>
                                                    <td><a href="users/employee/{{$employee->id}}" class="btn btn-warning">Edit</a></td>
                                                    <td><form action="users/employee/delete/{{$employee->id}}/?_method=DELETE" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        @if(Auth::check())
                                                            @if(Auth::user()->role === 'admin')
                                                                <button class="btn btn-danger">DELETE</button>
                                                            @else
                                                                <button class="btn btn-danger" disabled>DELETE</button>
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
                    if (status == 'Neaktiven') {
                        //document.getElementById('employeeTable').rows[i].setAttribute('class', 'bg-danger')
                        document.getElementById('employeeTable').rows[i].style.backgroundColor = 'gray';
                    } else {
                        document.getElementById('employeeTable').rows[i].setAttribute('class', 'bg-success')

                    }

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