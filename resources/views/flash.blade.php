@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show ms-auto me-auto text-center w-75 p-2" role="alert">
        
         <strong>{{ session('success') }}</strong>
  <button type="button" class="close bg-transparent border-0" data-bs-dismiss="alert" aria-label="Close">
    <span class="p-1" aria-hidden="true">&times;</span>
  </button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show ms-auto me-auto text-center w-75 p-2" role="alert">
         <strong>{{ session('error') }}</strong>
  <button type="button" class="close bg-transparent border-0" data-bs-dismiss="alert" aria-label="Close">
    <span class="p-1" aria-hidden="true">&times;</span>
  </button>
    </div>
@endif