@if (Session::has('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <h4 class="alert-heading"><i class="fas fa-ban"></i> Alert!</h4>
    {{ Session::get('error') }}
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <h4 class="alert-heading"><i class="fas fa-check"></i> Success!</h4>
    {{ Session::get('success') }}
</div>
@endif
