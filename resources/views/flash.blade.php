@if(session('success'))
    <div class="alert alert-success ms-auto me-auto text-center w-75 m-2 p-2">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger ms-auto me-auto text-center w-75 m-2 p-2">
        {{ session('error') }}
    </div>
@endif