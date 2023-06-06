@if(session()->has('message'))
    <div class="alert alert-primary dark alert-dismissible fade show" role="alert">
        {{session('message')}}
        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close" data-bs-original-title="" title=""></button>
    </div>
@endif
