<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="{{asset('css/vacation.css')}}">
    <title>Vacation</title>
</head>

<body>
    @include('navbar')
        @include('flash')
            <section id="section" class="row p-0">
                <section id="left" class="col col-lg-3">
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
                                    </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- USED HOLIDAYS, TABLES FOR EVERY USER-->
                <div class="table-responsive mt-5 mb-5">
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
                <section id="middle" class="col col-lg-7">
                <div class="row mb-5" id="pending_holidays">
                    @if($pending_holidays)
                    <h2 class="text-center p-2 mb-2">Oddane vloge</h2>
                    <div class="pendingTable">
                    <table id="requestedTable">
                        <thead>
                            <tr>
                                <th>Delavec</th>
                                <th>Od</th>
                                <th>Do</th>
                                <th>Dni</th>
                                <th>Oddano</th>
                                <th>LD</th>
                                <th>Odobri</th>
                                <th>Zavrni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pending_holidays as $pending)
                                <tr>
                                    @php
                                        $vacation = $vacations->firstWhere('employee_id', $pending->employee_id);
                                    @endphp
                                        @if($vacation)
                                        <td>{{$vacation->user}}</td>
                                        <td>{{\Carbon\Carbon::parse($pending->from)->format('d.m.Y')}}</td>
                                        <td>{{\Carbon\Carbon::parse($pending->to)->format('d.m.Y')}}</td>
                                        <td>{{$pending->days}}</td>
                                        <td>{{\Carbon\Carbon::parse($pending->apply_date)->format('d.m.Y')}}</td>
                                        <td>{{$vacation->holidays + $vacation->last_year - $vacation->used_holidays}}</td>
                                        <td>
                                            <form action="{{route('approveHoliday', $holiday->employee_id)}}" method="post">
                                                @csrf
                                                <button class="btn btn-sm btn-success">Odobri</button>
                                            </form>
                                        </td>
                                        <td>
                                            <form action="{{route('rejectHoliday',$holiday->employee_id)}}" method="post">
                                                @csrf
                                                <button class="btn btn-sm btn-danger">Zavrni</button>
                                            </form>
                                        </td>
                                        @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    @endif
                </div>
                <div class="row row-cols-2 w-100">
                    <div class="col">
                        <h2 class="text-nowrap text-start ms-2"><span id="month"></span> <span id="year"></span></h2>
                    </div>
                    <div class="col text-end align-self-end">
                        <button class="bg-transparent border-0" onclick="changeMonth(-1)"><img src="{{asset('img/left.png')}}" style="height: 30px"></button>
                        <button class="bg-transparent border-0" onclick="changeMonth(1)"><img src="{{asset('img/right.png')}}" style="height: 30px"></button>
                    </div>
                </div>
                <table id="calendar">
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
                        
                    </tbody>
                </table>

                <div id="showUser" style="display: none">
                    <table id="showUserOnVacation">
                        <thead>
                            <tr>
                                <th>Delavec</th>
                                <th>Od</th>
                                <th>Do</th>
                                <th>Dni</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                </section>

                <section id="right" class="col col-lg-2">
                <div id="addUser" class="shadow mt-5 rounded-5 mb-5">
                    <caption>
                        <h3 class="text-center">
                            Uredi podatke o dopustu
                        </h3>
                    </caption>
                    <div class="holidaysEdit mt-3 d-flex flex-column text-center">
                        <form action="{{route('updateVacation')}}" method="post">
                            @csrf
                            <div class="mb-2 d-flex flex-column">
                                <label for="user" class="">Ime delavca</label>
                                <select name="user" id="user" class="text-center" onchange="checkEmployee()">
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
                                    class="text-center" id="lastYearHolidays" value="" min="0">
                            </div>
                            <div class="mb-2 d-flex flex-column">
                                <label for="holidays">Letni dopust</label>
                                <input type="number" name="holidays" class="text-center"
                                    id="holidays" value="" min="0">
                            </div>
                            <div class="mb-2 d-flex flex-column">
                                <label for="usedHolidays">Iskoriščen dopust</label>
                                <input type="number" name="used_holidays" class="text-center"
                                    id="usedHolidays" value="" min="0">
                            </div>
                            <div class="mb-2 d-flex flex-column">
                                <label for="overtime">Nadure</label>
                                <input class="text-center" type="text" name="overtime"
                                    id="overtime" value="0">
                            </div>
                                <div class="submit m-2 p-2">
                                    @if(Auth::user()->role !== 'visitor')
                                        <button class="btn btn-success">Submit</button>
                                    @else
                                        <button class="btn btn-success" disabled>Submit</button>
                                    @endif
                                </div>

                        </form>
                    </div>
                </div>
                <div id="div" class="mt-5 mb-5 text-center">
                    <h3>Uredi podatke o dopustu.</h3>
                    <button class="btn btn-primary" onclick="editEmployeeHolidayData()">Submit</button>
                </div>
                </section>
            </section>



            <script>
                let vacations = @json($holidays);
                let employees = @json($vacations);
                let role = @json(Auth::user()->role);
                const date = new Date();
                let setMonth = date.getMonth() + 1;
                let month = setMonth;  // Trenutni mesec
                let year = date.getFullYear();
                let vacationTable = document.querySelector('#showUserOnVacation tbody');

                function generateCalendar(month,year){
                    let monthName = new Date(0, month - 1).toLocaleString('default',{month: 'long'})
                    document.getElementById('month').innerText = monthName;
                    document.getElementById('year').innerText = year;
                    let calendar = document.querySelector('#calendar tbody');
                    calendar.innerHTML = "";
                    let daysInMonth = new Date(year, month,0).getDate();
                    let startDate = new Date(year,month -1,1);
                    let firstDayOfMonth = startDate.getDay();
                    let currentDay = 1;
                    let startDay = 1;

                    while (currentDay <= daysInMonth) {
                        let row = calendar.insertRow();
                        for (let i = 1; i <= 7; i++) {
                            let cell = row.insertCell(i - 1);
                            if (startDay < firstDayOfMonth || currentDay > daysInMonth) {
                                cell.innerHTML = " ";
                                startDay++;
                            } else {
                                let formattedMonth = String(month).padStart(2, '0');
                                let formattedDay = String(currentDay).padStart(2, '0');
                                cell.innerHTML = `<span>${currentDay}</span>`;
                                cell.setAttribute("data-date",`${year}-${formattedMonth}-${formattedDay}`);
                                currentDay++;
                            }
                        }
                    }
                    let getTodayDate = date.toISOString().split('T')[0];
                    let findedDate = document.querySelector(`[data-date="${getTodayDate}"]`)
                    if(findedDate){
                        findedDate.style.backgroundColor = "#474745";
                    }
                    vacationTable.innerHTML = "";
                    document.getElementById('showUser').style.display = 'none';
                }
                document.addEventListener('DOMContentLoaded', function () {
                    markVacations(month,year); //
                });

                function markVacations(month, year){
                    generateCalendar(month,year);
                    let thisYear = new Date();
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
                            let row = vacationTable.insertRow();
                            if(month == from.getMonth() + 1){
                                document.getElementById('showUser').style.display = 'block';
                                for(employee of employees) {
                                    if(employee.employee_id == vacation.employee_id){
                                        let fromDate = new Date(vacation.from);
                                        let toDate = new Date(vacation.to);
                                        row.insertCell(0).innerHTML = role != 'visitor' ? employee.user : "Ime delavca";
                                        row.insertCell(1).innerHTML = ('0' + fromDate.getDate()).slice(-2) + '.' + ('0' + (fromDate.getMonth()+1)).slice(-2) + '.' + fromDate.getFullYear();
                                        row.insertCell(2).innerHTML = ('0' + toDate.getDate()).slice(-2) + '.' + ('0' + (toDate.getMonth()+1)).slice(-2) + '.' + toDate.getFullYear();
                                        row.insertCell(3).innerHTML = vacation.days;
                                    }
                                }
                            }
                        }
                    });
                };


                function changeMonth(direction){
                    month += direction;
                    if(month < 1){
                        month = 12;
                        year -= 1;
                    }else if(month>12){
                        month = 1;
                        year += 1;
                    }
                    markVacations(month,year);
                }

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
                    let employees = @json($vacations);
                    const employee = document.getElementById('user').value;
                    for(employeeData of employees){
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
