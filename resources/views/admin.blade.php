<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Search dbs</title>
</head>
<body>

    <select name="db" id="db" onchange="getDb()">
        <option selected>Izberi tabelo</option>
        @foreach ($tables as $table )
        <option value="{{$table->Tables_in_izdajablaga}}">{{$table->Tables_in_izdajablaga}}</option>
        @endforeach
    </select>

    <div>
        <h1 id="dbName" class="m-5 text-center"></h1>
        <div id="result">

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    
    <script>
        function getDb() {
            var db = document.getElementById('db').value;
            document.getElementById('dbName').innerHTML = "Table " + "'" + db + "'";
            let url = '{{route('getDb')}}';
            fetch(url,{
                method: 'POST',
                headers:{
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({db: db})
            })
            .then(response=>{
                if(!response.ok){
                    document.getElementById('result').innerHTML = "Something was wrong!!";
                }
                return response.json();
            })
            .then(tableData=>{
                switch(db){
                    case 'employee':
                        employees(tableData);
                        break;
                    case 'holidays':
                        holidays(tableData);
                        break;
                }
                console.log(tableData);
            })
            .catch(error=>{
                console.log(error);
            })
        }

        function employees(tableData){
            let result = document.getElementById('result');
            result.innerHTML = "";
            let table = document.createElement('table');
            let thead = document.createElement('thead');
            let tbody = document.createElement('tbody');
            let th = thead.insertRow();
            th.insertCell(0).textContent = 'User name';
            th.insertCell(1).textContent = 'Name';
            th.insertCell(2).textContent = 'Last name';
            th.insertCell(3).textContent = 'Email';
            th.insertCell(4).textContent = 'Status';
            th.insertCell(5).textContent = 'Working status';
            result.appendChild(table);
            table.appendChild(thead);
            table.appendChild(tbody);
            table.classList.add('table', 'table-responsive','table-bordered','table-dark', 'table-hover', 'text-center');
            tableData.data.forEach(user=>{
                let bodyRow = tbody.insertRow();
                bodyRow.insertCell(0).textContent = user.user_name;
                bodyRow.insertCell(1).textContent = user.name;
                bodyRow.insertCell(2).textContent = user.last_name;
                bodyRow.insertCell(3).textContent = user.email;
                bodyRow.insertCell(4).textContent = user.status;
                bodyRow.insertCell(5).textContent = user.working_status;
            })
        }
        function holidays(tableData){
            let result = document.getElementById('result');
            result.innerHTML = "";
            let table = document.createElement('table');
            let thead = document.createElement('thead');
            let tbody = document.createElement('tbody');
            let th = thead.insertRow();
            th.insertCell(0).textContent = 'From';
            th.insertCell(1).textContent = 'To';
            th.insertCell(2).textContent = 'Applied';
            th.insertCell(3).textContent = 'Status';
            th.insertCell(4).textContent = 'Employee id';
            result.appendChild(table);
            table.appendChild(thead);
            table.appendChild(tbody);
            table.classList.add('table', 'table-responsive','table-bordered','table-dark', 'table-hover', 'text-center');

            tableData.data.forEach(user=>{
                let bodyRow = tbody.insertRow();
                bodyRow.insertCell(0).textContent = user.from;
                bodyRow.insertCell(1).textContent = user.to;
                bodyRow.insertCell(2).textContent = user.apply_date;
                bodyRow.insertCell(3).textContent = user.status;
                bodyRow.insertCell(4).textContent = user.employee_id;
            })
        }

    </script>
</body>
</html>