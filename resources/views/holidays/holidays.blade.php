<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
        <link rel="stylesheet" href="{{asset('css/allPages.css')}}">
        <link rel="stylesheet" href="{{asset('css/vacation.css')}}">
    <title>Vacation</title>
</head>

<body>
    @include('navbar')
        @include('flash')
            <section id="section" class="row row-cols-3 m-0 p-0">
                <section id="left" class="col-3 text-center ms-auto me-auto shadow">
                    <div id="usersQtyHolidays" class="text-center ms-auto me-auto">
                        <div class="caption mt-2">
                            <h2 class="mb-3">Stanje dopusta</h2>
                        </div>
                        <table id="infoHolidays" class="table table-active text-center w-100">
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
                    <div class="table-responsive mt-5">
                        @foreach ($employees as $employee)
                        @if($employee->status == 1)
                        <caption><strong>{{Auth::user()->role !== 'visitor' ? $employee->name." ".$employee->last_name : "Ime delavca"}}</strong></caption>
                        <table class="mb-5 table table-bordered table-hover holidayTable bg-secondary text-light">
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
                </section>

                <section id="middle" class="col-6 flex-column flex-nowrap shadow">
                    <div class="row row-cols-2 w-100">
                        <div class="col">
                            <h2><?php echo date('F')." ". date('Y')?></h2>
                        </div>
                        <div class="col text-end align-self-end">
                            <button class="bg-secondary p-1" onclick="changeMonth(-1)">prev</button>
                            <button class="bg-secondary p-1" onclick="changeMonth(1)">next</button>
                        </div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Pon</th>
                                <th>Tor</th>
                                <th>Sre</th>
                                <th>Čet</th>
                                <th>Pet</th>
                                <th>Sob</th>
                                <th>Ned</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $month = sprintf('%02d', date('m')); // Trenutni mesec
                            $year = date('Y');
                            $week = date('W');
                            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                            $firstDayOfMonth = date('N', strtotime("$year-$month-01"));
                
                            $currentDay = 1;
                            $startDay = 1;
                
                            while ($currentDay <= $daysInMonth) {
                                echo '<tr>';
                                for ($i = 1; $i <= 7; $i++) {
                                    if ($startDay < $firstDayOfMonth || $currentDay > $daysInMonth) {
                                        echo "<td></td>";
                                        $startDay++;
                                    } else {
                                        $dayFormatted = sprintf('%02d', $currentDay);
                                        echo "<td data-date=\"$year-$month-$dayFormatted\"><span>$currentDay</span></td>";
                                        $currentDay++;
                                    }
                                }
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>    
                </section>

                <div id="right" class="col-3 shadow">
                    <div id="addUser" class="shadow mt-5 rounded-5 mb-5">
                        <caption>
                            <h1 class="text-center">
                                Uredi podatke o dopustu
                            </h1>
                        </caption>
                        <div class="holidaysEdit mt-3 d-flex flex-column text-center">
                            <form action="{{route('updateVacation')}}" method="post">
                                @csrf
                                <div class="mb-2 d-flex flex-column">
                                    <label for="user" class="">Ime delavca</label>
                                    <select name="user" id="user" class="w-50 ms-auto me-auto text-center" onchange="checkEmployee()">
                                        <option selected>Izberi delavca</option>
                                        @foreach($employees as $employee)
                                            @if(Auth::user()->role !== 'visitor')
                                                <option value="{{$employee->name.' '.$employee->last_name}}">{{$employee->name.' '.$employee->last_name}}</option>
                                                @else
                                                <option value="/">/</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2 d-flex flex-column">
                                    <label for="lastYearHolidays">Lanski dopust</label>
                                    <input type="number" name="last_year"
                                        class="w-50 ms-auto me-auto text-center" id="lastYearHolidays" value="" min="0">
                                </div>
                                <div class="mb-2 d-flex flex-column">
                                    <label for="holidays">Letni dopust</label>
                                    <input type="number" name="holidays" class="w-50 ms-auto me-auto text-center"
                                        id="holidays" value="" min="0">
                                </div>
                                <div class="mb-2 d-flex flex-column">
                                    <label for="usedHolidays">Iskoriščen dopust</label>
                                    <input type="number" name="used_holidays" class="w-50 ms-auto me-auto text-center"
                                        id="usedHolidays" value="" min="0">
                                </div>
                                <div class="mb-2 d-flex flex-column">
                                    <label for="overtime">Nadure</label>
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
                        <h3>Uredi podatke o dopustu.</h3>
                        <button class="btn btn-primary" onclick="editEmployeeHolidayData()">Submit</button>
                    </div>
                </div>
            </section>



            <script>
                let vacations = @json($holidays);
                document.addEventListener('DOMContentLoaded', function () {
                    let thisYear = new Date();
                    let year = thisYear.getFullYear();
                    vacations.forEach(function(vacation) {
                        let splitDate = vacation.from.split(' ');
                        let getYear = splitDate[0].split('-');
                        if(vacation.status === 'Approved' && getYear[0] == year){
                            let startDate = vacation.from.split(' ');
                            let endDate = vacation.to.split(' ');
                            let from = new Date(startDate[0]);
                            let to = new Date(endDate[0]);
                            for (let d = from; d <= to; d.setDate(d.getDate() + 1)) {
                                let dateStr = d.getFullYear() + '-' + ('0' + (d.getMonth() + 1)).slice(-2) + '-' + ('0' + d.getDate()).slice(-2);
                                let cell = document.querySelector(`td[data-date="${dateStr}"]`);
                                if (cell) {
                                    cell.classList.add('vacation');
                                }
                            }
                        }
                    });
                });

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
                    for(employeeData of vacations){
                        if(employeeData.user == employee){
                            holidays.value = employeeData.holidays;
                            lastYearHolidays.value = employeeData.last_year;
                            usedHolidays.value = employeeData.used_holidays;
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
