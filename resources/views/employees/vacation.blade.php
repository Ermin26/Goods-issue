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
            <h3>Išči dopust</h3>
            <form id="getVacations">
                <div class="mb-3">
                    <label for="monthYear">Mesec ali Leto</label>
                    <input type="number" name="monthYear" min="1" id="monthYear">
                </div>
                <div class="mb-3">
                    <label for="status">Status</label>
                    <select name="status" id="status">
                        <option value="">Status</option>
                        <option value="Approved">Odobreno</option>
                        <option value="Rejected">Zavrnenjo</option>
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
    </main>


    <script>

        let id = @json($employee);
        let myHolidays = document.getElementById('myHolidays');
        myHolidays.style.display='none';
        document.getElementById('getVacations').addEventListener('submit', function(event){
            event.preventDefault();

            let time = document.getElementById('monthYear');
            let status = document.getElementById('status').value;
            let url = "{{ route('myHolidays', ['id' => '__ID__']) }}".replace('__ID__', id);
            fetchData(url,{time: time.value, status: status});
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
                console.log(data)
                showHolidays(data, tbody);
            })
            .catch(error=>console.error("Error: ", error));
        };

        function showHolidays(data, tbody){
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