@if(Session::has('flash_message'))
<div class="alert alert-primary" role="alert">
    <div class="alert-body"><strong>Sukses!</strong> {!! session('flash_message') !!}</div>
</div>
@endif

@if (Session::has('errors'))
<div class="alert alert-danger" role="alert">
    <div class="alert-body"><strong>Error!</strong> {!! session('errors') !!}</div>
</div>
@endif