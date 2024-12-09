<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/notification.css')}}">
    <link rel="stylesheet" href="{{asset('css/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <title>Search dbs</title>
</head>
<body>
    @include('navbar')
    @include('flash')

    <select class="m-5 p-2 w-50" name="db" id="db" onchange="getDb()">
        <option selected>Izberi tabelo</option>
        @foreach ($tables as $table )
            @if (app()->environment('production'))
                <option value="{{$table->Tables_in_if0_36768205_providio}}">{{$table->Tables_in_if0_36768205_providio}}
            @else
                <option value="{{$table->Tables_in_izdajablaga}}">{{$table->Tables_in_izdajablaga}}</option>
            @endif
        @endforeach
    </select>

    <div>
        <h1 id="dbName" class="m-5 text-center"></h1>
        <div id="result">

        </div>
        <div id="tableResult">

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    
    <script>
        let allTableData;
        let tableKeys;
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
                tableInfo(tableData);
                allTableData = tableData.tableResult;
                tableKeys = Object.keys(allTableData.data[0]);
            })
            .catch(error=>{
                console.log(error);
            })
        }

        function tableInfo(tableData){
            let result = document.getElementById('result');
            result.innerHTML = "";
            let btn = document.createElement('button');
            btn.innerHTML = "Show table data";
            btn.classList.add('btn','btn-primary', 'm-5', 'p-2');
            btn.setAttribute('onclick','showData()')
            let table = document.createElement('table');
            let thead = document.createElement('thead');
            let tbody = document.createElement('tbody');
            let th = thead.insertRow();
            th.insertCell(0).textContent = 'Field';
            th.insertCell(1).textContent = 'Type';
            th.insertCell(2).textContent = 'Null';
            th.insertCell(3).textContent = 'Key';
            th.insertCell(4).textContent = 'Extra';
            th.insertCell(5).textContent = 'Default';
            result.appendChild(table);
            table.appendChild(thead);
            table.appendChild(tbody);
            table.classList.add('table', 'table-responsive','table-bordered','table-dark', 'table-hover', 'text-center');
            tableData.data.forEach(user=>{
                let bodyRow = tbody.insertRow();
                bodyRow.insertCell(0).textContent = user.Field;
                bodyRow.insertCell(1).textContent = user.Type;
                bodyRow.insertCell(2).textContent = user.Null;
                bodyRow.insertCell(3).textContent = user.Key;
                bodyRow.insertCell(4).textContent = user.Extra;
                bodyRow.insertCell(5).textContent = user.Default;
            })
            result.appendChild(btn);
        }

        function showData(){
            testTableKeys(tableKeys,allTableData)
        };

        function testTableKeys(tableKeys,allTableData){
            let dataTable = document.getElementById('tableResult');
            dataTable.innerHTML = '';
            let table = document.createElement('table');
            let thead = document.createElement('thead');
            let tbody = document.createElement('tbody');
            let th = thead.insertRow();
            tableKeys.forEach(key=>{
                th.insertCell(-1).textContent = key;
            });
            result.appendChild(table);
            table.appendChild(thead);
            table.appendChild(tbody);
            table.classList.add('table', 'table-responsive','table-bordered','table-info', 'table-hover', 'text-center');
            allTableData.data.forEach(user=>{
                let bodyRow = tbody.insertRow();
                tableKeys.forEach(key=>{
                    bodyRow.insertCell(tableKeys.indexOf(key)).textContent = user[key];
                });
            })
        }

    </script>
</body>
</html>