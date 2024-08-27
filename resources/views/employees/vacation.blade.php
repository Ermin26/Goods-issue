<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/test.css')}}">
    <title>Dopust</title>
</head>
<body>
    @include('employees.nav')

    <main>
        @if($vacation && $employee->working_status == 'zaposlen/a')
            <section id="vacation">
                <div class="holidaysInfo">
                    <h3 class="text-center text-light p-2">Lanski dopust {{$vacation->last_year}}<br>Letni dopust {{$vacation->holidays}}</h3>
                </div>
                <form action="{{route('newHoliday')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="from">Prvi dan dopusta</label>
                        <input type="date" class="text-center" name="from" id="from">
                    </div>
                    <div class="mb-3">
                        <label for="to">Zadnji dan dopusta</label>
                        <input type="date" class="text-center" name="to" id="to">
                    </div>
                    <div class="mb-3">
                        <label for="days">Število dni</label>
                        <input type="number" class="text-center" name="days" id="days" required>
                    </div>
                    <div class="row">
                        <button class="btn btn-primary">Oddaj</button>
                    </div>
                </form>
            </section>
            <section id="searchVacation">
                <div id="error" class="w-100">
                    <h1 class="text-center bg-danger text-light p-4" style="display: none">Ni podatkov za izbrani kriterij.</h1>
                </div>
                <h3>Išči dopust</h3>
                <form id="getVacations">
                    <div class="mb-3">
                        <label for="month">Mesec</label>
                        <input type="number" name="month" min="1" max="12" id="month">
                    </div>
                    <div class="mb-3">
                        <label for="year">Leto</label>
                        <select name="year" id="year">
                            <option value="">Izberi leto</option>
                            @foreach ($years as $year)
                                @if($year != '0')
                                    <option value="{{$year}}">{{$year}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="">Status</option>
                            <option value="Approved">Odobreno</option>
                            <option value="Rejected">Zavrnjeno</option>
                        </select>
                    </div>
                    <button class="btn btn-sm btn-primary">Išči</button>
                </form>
                <section id="showVacations">
                    <table id="myHolidays" class="table table-responsive table-bordered border-1 border-light">
                        <thead>
                            <tr>
                                <th>Od</th>
                                <th>Do</th>
                                <th>Days</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </section>
            </section>
        @elseif($employee->working_status == 'študent')
        <section id="studentSection">
            <form id="studentForm" action="{{route('studentSendEmail', $employee->id)}}" method="POST">
                @csrf
                <div class="mb-3">
                    <input type="text" id="msgInfo" name="msgInfo" placeholder=" " required>
                    <span id="msgInfoSpan">Zadeva</span>
                    <textarea name="msg" id="msg" cols="36" rows="5" placeholder=" " required></textarea>
                    <span id="msgSpan">Sporočilo</span>
                </div>
                <button type="submit" class="btn btn-outline-primary btn-sm">Pošlji</button>
            </form>
        </section>
        @endif
    </main>


    <script>

        let id = @json($employee);
        let myHolidays = document.getElementById('myHolidays');
        myHolidays.style.display='none';
        document.getElementById('getVacations').addEventListener('submit', function(event){
            event.preventDefault();

            let month = document.getElementById('month').value;
            let year = document.getElementById('year').value;
            let status = document.getElementById('status').value;
            let url = "{{ route('myHolidays', ['id' => '__ID__']) }}".replace('__ID__', id);
            fetchData(url,{month: month, year: year, status: status});
        });

        function fetchData(url, params){
            let tbody = document.querySelector('#myHolidays tbody');
            tbody.innerHTML = "";
            myHolidays.style.display ='none';
            fetch(url, {
                method: 'POST',
                headers:{
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body:JSON.stringify(params)
            })
            .then(response=>response.json())
            .then(data =>{
                showHolidays(data, tbody);
            })
            .catch(error=>console.error("Error: ", error));
        };

        function showHolidays(data, tbody){
            if(data && data.holidays.length > 0){
                data.holidays.forEach(array => {
                    let row = tbody.insertRow();
                    row.insertCell(0).innerHTML = array.from.split(' ')[0];
                    row.insertCell(1).innerHTML = array.to.split(' ')[0];
                    row.insertCell(2).innerHTML = array.days;
                    row.insertCell(3).innerHTML = array.status;
                    if(array.status == 'Approved'){
                        row.cells[3].classList.add('bg-success')
                    }else if(array.status == 'Rejected'){
                        row.cells[3].classList.add('bg-danger')
                    }
                });
                myHolidays.style.display="block";
            }else{
                document.getElementById('error').innerHTML = "";
                let h1 = document.createElement('h1');
                h1.innerHTML = "Ni podatkov."
                h1.classList.add('text-center', 'bg-danger', 'p-2', 'text-light');
                document.getElementById('error').appendChild(h1);
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