<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
    
    <script>
        function getDb() {
            var db = document.getElementById('db').value;
            document.getElementById('dbName').innerHTML = db;
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
            .then(table=>{
                console.log(table);
            })
            .catch(error=>{
                console.log(error);
            })
        }
    </script>
</body>
</html>