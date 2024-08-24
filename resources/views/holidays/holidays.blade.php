<!DOCTYPE html>
<html lang="sl">

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
        <section id="section" class="row p-0">
            @include('flash')
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
                <div class="vacationForm border-bottom border-1 border-light p-2">
                    <div class="formInfo col text-start bg-secondary p-2">
                        <small class="pt-2">* Pusti prazno če želite pridobiti podatke o vseh dopustih.</small><br>
                        <small class="pt-2">* Izberi samo leto če želite pridobiti podatke o vseh dopustih za izbrano leto.</small><br>
                        <small class="pt-2">* Izberi delavca, če želite pridobiti podatke o vseh dopustih izbranega delavca.</small><br>
                        <small class="pt-2">* Izberi delavca in leto, če želite pridobiti podatke o vseh dopustih izbranega delavca za izbrano leto.</small>
                    </div>
                    <form id="userUsedHolidays" class="col">
                        <div class="mb-3">
                            <label for="selectedYear">Leto</label>
                            <select name="selectedYear" id="selectedYear">
                                <option value="">Izberi leto</option>
                                @foreach($years as $year)
                                    @if($year !== 0)
                                        <option value="{{$year}}">{{$year}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="selectedUser">Delavec</label>
                            <select type="text" name="selectedUser" id="selectedUser">
                                <option value="">Izberi delavca</option>
                                @foreach($employees as $employee)
                                    @if(Auth::user()->role !== 'visitor')
                                        <option value="{{$employee->name.' '.$employee->last_name}}">{{$employee->name.' '.$employee->last_name}}</option>
                                    @else
                                        <option value="/">/</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="rowBtns">
                            <button id="searchBtn" class="btn btn-primary btn-sm p-2 text-light d-inline-flex text-center">Potrdi</button>
                            <div id="clearBtn" class="btn btn-secondary btn-sm p-2" style="display: none" onclick="clearData()">Počisti</div>
                        </div>
                    </form>
                </div>
                <div id="vacationResults">

                </div>
            </section>
            <section id="middle" class="col col-lg-7">
                <div class="row" id="pending_holidays">
                    @if($pending_holidays && count($pending_holidays) > 0)
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
                                                <td>{{$vacation->user}}
                                                </td>
                                                <td>{{\Carbon\Carbon::parse($pending->from)->format('d.m.Y')}}</td>
                                                <td>{{\Carbon\Carbon::parse($pending->to)->format('d.m.Y')}}</td>
                                                <td>{{$pending->days}}</td>
                                                <td>{{\Carbon\Carbon::parse($pending->apply_date)->format('d.m.Y')}}</td>
                                                <td>{{$vacation->holidays + $vacation->last_year - $vacation->used_holidays}}</td>
                                                <td>
                                                    <form action="{{route('approveHoliday', $pending->id)}}" method="post">
                                                        @csrf
                                                        <button class="btn btn-sm btn-success">Odobri</button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form action="{{route('rejectHoliday',$pending->id)}}" method="post">
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

            <section id="right" class="col col-lg-2 mb-4">
                <div id="firstForm">
                    <div id="addUser" class="mt-5 mb-5">
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
                                        <button class="btn btn-success">Potrdi</button>
                                    @else
                                        <button class="btn btn-success" disabled>Potrdi</button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="div" class="mt-5 mb-5 text-center">
                        <h3>Uredi podatke o dopustu.</h3>
                        <button class="btn btn-primary" onclick="editEmployeeHolidayData()">Uredi</button>
                    </div>
                </div>
                @if(Auth::user()->role == 'admin')
                    <div id="secondForm">
                        <div id="resultMsg" class="text-center justify-content-center p-2" style="display: none;">
                        </div>
                        <h4 class="p-4">Pošlji email vsem delavcem</h4>
                        <form id="sendMsg">
                            <div class="mb-3">
                                <input type="text" id="msgInfo" name="msgInfo" placeholder=" ">
                                <span id="msgInfoSpan">Zadeva</span>
                                <textarea name="msg" id="msg" cols="36" rows="5" placeholder=" "></textarea>
                                <span id="msgSpan">Sporočilo</span>
                            </div>
                            <button type="submit" class="btn btn-outline-primary btn-sm">Pošlji</button>
                        </form>
                    </div>
                @endif
            </section>
        </section>



            <script>
                let clearBtn = document.getElementById('clearBtn');
                let showResults = document.getElementById('vacationResults');
                let resultMsg = document.getElementById('resultMsg');
                let vacations = @json($holidays);
                let employees = @json($vacations);
                let role = @json(Auth::user()->role);
                const date = new Date();
                let setMonth = date.getMonth() + 1;
                let month = setMonth;  // Trenutni mesec
                let year = date.getFullYear();
                let vacationTable = document.querySelector('#showUserOnVacation tbody');

                document.getElementById('userUsedHolidays').addEventListener('submit',function(event){
                    event.preventDefault();
                    let selectedYear = document.getElementById('selectedYear').value;
                    let selectedUser = document.getElementById('selectedUser').value;
                    fetchData('{{route('userUsedHolidays')}}', {selectedYear:selectedYear,selectedUser:selectedUser});
                })

                function fetchData(url,params){
                    fetch(url,{
                        method: 'POST',
                        headers:{
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(params)
                    })
                    .then(response => response.json())
                    .then(data=>{
                        showResults.innerHTML = "";
                        data.holidays.forEach(function(user){
                            let h3 = document.createElement('h3');
                            h3.classList.add('mt-4')
                            h3.innerHTML = user.user;
                            let table = document.createElement('table');
                            table.classList.add('resultTable', 'table', 'table-responsive', 'text-light', 'border-1', 'border-dark', 'text-center', 'mb-5')
                            let thead = document.createElement('thead');
                            let tbody = document.createElement('tbody');
                            let row = thead.insertRow();
                            row.insertCell(0).innerHTML = "Od";
                            row.insertCell(1).innerHTML = "Do";
                            row.insertCell(2).innerHTML = "Dni";
                            row.insertCell(3).innerHTML = "Status";
                            table.appendChild(thead);
                            table.appendChild(tbody);
                            user.holidays.forEach(function(holiday){
                                let rows = tbody.insertRow();
                                let formatFrom = holiday.from;
                                let formatTo = holiday.to;
                                let newFromDate = new Date(formatFrom);
                                let newToDate = new Date(formatTo);
                                let yearFrom = newFromDate.getFullYear(newFromDate);
                                let yearTo = newToDate.getFullYear(newToDate);
                                let monthFrom = String(newFromDate.getMonth() + 1).padStart(2,'0');
                                let monthTo = String(newToDate.getMonth() + 1).padStart(2,'0');
                                let dayFrom = String(newFromDate.getDate()).padStart(2,'0');
                                let dayTo = String(newToDate.getDate()).padStart(2,'0');

                                let formatedFrom = `${dayFrom}.${monthFrom}.${yearFrom}`;
                                let formatedTo = `${dayTo}.${monthTo}.${yearTo}`;

                                rows.insertCell(0).innerHTML = formatedFrom;
                                rows.insertCell(1).innerHTML = formatedTo;
                                rows.insertCell(2).innerHTML = holiday.days;
                                if(holiday.status == 'Approved'){
                                    rows.insertCell(3).innerHTML = "Odobreno";
                                    rows.cells[3].style.backgroundColor = "Green"
                                }else if (holiday.status == 'Rejected'){
                                    rows.insertCell(3).innerHTML = "Zavrnjeno";
                                    rows.cells[3].style.backgroundColor = "Red"
                                }else{
                                    rows.insertCell(3).innerHTML = "Preverjanje";
                                    rows.cells[3].style.backgroundColor = "#d0d000"
                                }
                            })
                            showResults.appendChild(h3);
                            showResults.appendChild(table);
                            clearBtn.style.display = "flex"
                        })
                    })
                }

                function clearData(){
                    showResults.innerHTML = "";
                    clearBtn.style.display = "none";
                }


                document.getElementById('sendMsg').addEventListener('submit', function(e){
                    e.preventDefault();
                    let msgInfo = document.getElementById('msgInfo').value;
                    let msg = document.getElementById('msg').value;
                    sendMsg('{{route('sendMsg')}}', {msgInfo: msgInfo, msg: msg});
                })

                function sendMsg(url, params){
                    fetch(url,{
                        method: 'POST',
                        headers:{
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(params)
                    })
                    .then(response =>{
                        if(!response.ok){
                            let h3 = document.createElement('h3');
                            h3.innerHTML = response.status;
                            resultMsg.appendChild(h3);
                            resultMsg.style.display = "flex";
                            resultMsg.classList.add('bg-success')
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data=>{
                        document.getElementById('msgInfo').value = "";
                        document.getElementById('msg').value = "";
                        let h3 = document.createElement('h3');
                        h3.innerHTML = data.msg;
                        resultMsg.appendChild(h3);
                        resultMsg.style.display = "flex";
                        //resultMsg.style.backgroundColor = "green";
                        resultMsg.classList.add('bg-success')
                    })
                    .catch(error=>{
                        let h3 = document.createElement('h3');
                        h3.innerHTML = response.status;
                        resultMsg.appendChild(h3);
                        resultMsg.style.display = "flex";
                        resultMsg.classList.add('bg-success')
                        console.error('Error: ', error);
                    })
                }

                function generateCalendar(month,year){
                    let lastMonthDay = month - 1;
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
                        prevNextMonthDays(month,year,lastMonthDay);
                        findeTodayDate();
                    vacationTable.innerHTML = "";
                    document.getElementById('showUser').style.display = 'none';
                }
                document.addEventListener('DOMContentLoaded', function () {
                    markVacations(month,year); //
                    infoHolidaysTable();
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
                            if(month == new Date(startDate[0]).getMonth()+1 || month == new Date(endDate[0]).getMonth()+1){
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

                function prevNextMonthDays(month, year, lastMonthDay){
                    let lastRow = calendar.rows[calendar.rows.length - 1];
                    let firstRow = calendar.rows[1];

                    let nextMonthDay = 1;
                    let nextMonth = month + 1;
                    let nextMonthYear = year;

                    let prevMonth = month - 1;
                    let prevYear = year;

                    if (nextMonth > 12) {
                        nextMonth = 1;
                        nextMonthYear++;
                    }
                    else if(prevMonth < 1){
                        prevMonth = 12;
                        prevYear--;
                    }
                    let nextMonthFormatted = String(nextMonth).padStart(2, '0');
                    let prevMonthFormatted = String(prevMonth).padStart(2, '0');
                    let lastDayOfMonth = new Date(year, lastMonthDay, 0).getDate();

                    for (let i = 0; i < 7; i++) {
                        let cell = lastRow.cells[i];
                        if (cell.innerHTML.trim() === "") {
                            let nextDayFormatted = String(nextMonthDay).padStart(2, '0');
                            cell.innerHTML = `<span class="text-light text-opacity-50">${nextMonthDay}</span>`;
                            cell.setAttribute("data-date", `${nextMonthYear}-${nextMonthFormatted}-${nextDayFormatted}`);
                            nextMonthDay++;
                        }
                    }
                    for(let i = 6; i >= 0; i--){
                        let cell = firstRow.cells[i];
                        if(cell.innerHTML.trim() === ""){
                            let prevDayFormatted = String(lastDayOfMonth).padStart(2,'0');
                            cell.innerHTML = `<span class="text-light text-opacity-50">${lastDayOfMonth}</span>`;
                            cell.setAttribute("data-date", `${prevYear}-${prevMonthFormatted}-${prevDayFormatted}`);
                            lastDayOfMonth --;
                        }
                    }
                }

                function findeTodayDate(){
                    let getTodayDate = date.toISOString().split('T')[0];
                    let findedDate = document.querySelector(`[data-date="${getTodayDate}"]`)
                    if(findedDate){
                        findedDate.style.backgroundColor = "#474745";
                    }
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

                function infoHolidaysTable(){
                    const myTable = document.getElementById('infoHolidays');
                    const rows = myTable.rows.length - 1;
                    for (let i = 1; i <= rows; i++) {
                        let userLast = myTable.rows[i].cells[1].innerHTML;
                        let userThis = myTable.rows[i].cells[2].innerHTML;
                        let userUsed = myTable.rows[i].cells[3].innerHTML;
                        myTable.rows[i].cells[4].innerHTML = parseInt(userLast) + parseInt(userThis) - parseInt(userUsed);
                    }
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

                document.getElementById('msg').addEventListener('input', function() {
                    if (this.value.trim() !== "") {
                        this.classList.add('not-empty');
                    } else {
                        this.classList.remove('not-empty');
                    }
                });
            </script>

            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
                integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
                crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
                integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
                crossorigin="anonymous"></script>
</body>

</html>
