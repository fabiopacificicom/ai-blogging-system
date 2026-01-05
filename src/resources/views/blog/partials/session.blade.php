@if(session('message'))

<div class="alert alert-warning alert-dismissible fade show position-fixed top-0 left-0" role="alert">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <strong>{{session('message')}}</strong>
</div>

@endif
