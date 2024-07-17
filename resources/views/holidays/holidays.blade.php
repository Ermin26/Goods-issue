<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/vacation.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/allPages.css')}}">
    <title>Vacation</title>
</head>

<body>
    @include('navbar')
        @include('flash')
            <div id="section" class="row row-cols-3 m-0 p-0 mt-2">
                <div id="left" class="col-3 text-center ms-auto me-auto shadow">
                    <div id="usersQtyHolidays" class="text-center ms-auto me-auto">
                        <div class="caption mt-2">
                            <h2 class="mb-3">Stanje dopusta</h2>
                        </div>
                        <table id="infoHolidays" class="table table-active table-hover text-center w-100">
                            <thead>
                                <th>Delavec</th>
                                <th>Lani</th>
                                <th>Letni dopust</th>
                                <th>Koriščeno</th>
                                <th>Preostalo</th>
                                <th>Ure</th>
                            </thead>
                            <tbody>
                                @foreach ($vacations as $vacation)
                                    @foreach($employees as $employee)
                                        @if($vacation->employee_id == $employee->id)
                                        <tr>
                                            <td>
                                                {{Auth::user()->role !== 'visitor' ? $vacation->user : "/"}}
                                            </td>
                                            <td>
                                                {{$vacation->last_year}}
                                            </td>
                                            <td>
                                                {{$vacation->holidays}}
                                            </td>
                                            <td>
                                                {{$vacation->used_holidays}}
                                            </td>
                                            <td id="preostalo">
                                            </td>
                                            <td>
                                                {{$vacation->overtime}}
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- USED HOLIDAYS, TABLES FOR EVERY USER-->
                    <div class="table-responsive">
                        @foreach ($employees as $employee)
                        @if($employee->status == 1)
                        <caption>{{Auth::user()->role !== 'visitor' ? $employee->name." ".$employee->last_name : "/"}}</caption>
                        <table class="mb-5 table table-bordered holidayTable bg-success">
                            <thead>
                                <th scope="col" class="ps-0 pe-0 align-middle">Od</th>
                                <th scope="col" class="ps-0 pe-0 align-middle">Do</th>
                                <th scope="col" class="ps-0 pe-0 align-middle">Status</th>
                                <th scope="col" class="ps-0 pe-0 align-middle">Dni</th>
                            </thead>
                            <tbody>
                                @foreach($holidays as $holiday)
                                <tr>
                                    @if($employee->id == $holiday->employee_id)
                                    <td scope="col" class="ps-0 pe-0 align-middle"><strong>{{\Carbon\Carbon::parse($holiday->from)->format('d.m.Y')}}</strong></td>
                                    <td scope="col" class="ps-0 pe-0 align-middle"><strong>{{\Carbon\Carbon::parse($holiday->to)->format('d.m.Y')}}</strong></td>
                                    <td scope="col" class="ps-0 pe-0 align-middle"><strong>{{$holiday->status}}</strong></td>
                                    <td scope="col" class="ps-0 pe-0 align-middle"><strong>{{$holiday->days}}</strong></td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                            @endif
                        @endforeach
                    </div>
                </div>


                <div id="right" class="col-3 shadow">
                    <div id="addUser" class="shadow mt-5 rounded-5 mb-5">
                        <caption>
                            <h1 class="text-center">
                                Edit users vacation
                            </h1>
                        </caption>
                        <div class="holidaysEdit mt-3 d-flex flex-column text-center">
                            <form action="/holidays" method="post">
                                <div class="mb-2 d-flex flex-column">
                                    <label for="user" class="">Ime delavca</label>
                                    <select name="user" id="user" class="w-50 ms-auto me-auto text-center" onchange="checkEmployee()">
                                        <option selected>Izberi delavca</option>
                                        @foreach($employees as $employee)
                                            <option value={{Auth::user()->role !== 'visitor' ? $employee->name." ".$employee->last_name : '/'}}>{{Auth::user()->role !== 'visitor' ? $employee->name." ".$employee->last_name : '/'}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2 d-flex flex-column">
                                    <label for="lastYearHolidays">Last year Holidays</label>
                                    <input type="number" name="lastYearHolidays"
                                        class="w-50 ms-auto me-auto text-center" id="lastYearHolidays" value="" min="0">
                                </div>
                                <div class="mb-2 d-flex flex-column">
                                    <label for="holidays">Holidays</label>
                                    <input type="number" name="holidays" class="w-50 ms-auto me-auto text-center"
                                        id="holidays" value="" min="0">
                                </div>
                                <div class="mb-2 d-flex flex-column">
                                    <label for="usedHolidays">Used holidays</label>
                                    <input type="number" name="usedHolidays" class="w-50 ms-auto me-auto text-center"
                                        id="usedHolidays" value="" min="0">
                                </div>
                                <div class="mb-2 d-flex flex-column">
                                    <label for="overtime">Overtime</label>
                                    <input class="w-50 ms-auto me-auto text-center" type="text" name="overtime"
                                        id="overtime" value="0">
                                </div>
                                    <div class="submit m-2 p-2">
                                        <button class="btn btn-success">Submit</button>
                                    </div>

                            </form>
                        </div>
                    </div>
                    <div id="div" class="mt-5 mb-5 text-center">
                        <h3>Update employee holiday data.</h3>
                        <button class="btn btn-primary" onclick="editEmployeeHolidayData()">Submit</button>
                    </div>
                </div>
            </div>



            <script>
                document.getElementById('addUser').style.display = 'none';
                const lastYearHolidays = document.getElementById('lastYearHolidays');
                const holidays = document.getElementById('holidays');
                const usedHolidays = document.getElementById('usedHolidays');
                const hours = document.getElementById('overtime');
                function editEmployeeHolidayData() {
                    document.getElementById('addUser').style.display = 'block';
                    document.getElementById('div').style.display = 'none';

                }

                const myTable = document.getElementById('infoHolidays');
                const rows = myTable.rows.length - 1;

                for (let i = 1; i <= rows; i++) {
                    let userLast = myTable.rows[i].cells[1].innerHTML;
                    let userThis = myTable.rows[i].cells[2].innerHTML;
                    let userUsed = myTable.rows[i].cells[3].innerHTML;
                    myTable.rows[i].cells[4].innerHTML = parseInt(userLast) + parseInt(userThis) - parseInt(userUsed);
                }

                function checkEmployee(){
                const employee = document.getElementById('user').value;
                    for(employeeData of holidayData){
                        if(employeeData.user == employee){
                            holidays.value = employeeData.holidays;
                            lastYearHolidays.value = employeeData.lastYearHolidays;
                            usedHolidays.value = employeeData.usedHolidays;
                            hours.value = employeeData.overtime;
                        }
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
