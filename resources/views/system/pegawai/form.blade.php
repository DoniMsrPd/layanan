@push('scripts')
<script>
    $(document).on("click",'.pilih',function () {
    kd_unit_org = $(this).data('kd_unit_org')
    nm_unit_org = $(this).data('nm_unit_org')
    $('#Nama').val(nm_unit_org)
    $('#Id').val(kd_unit_org)
    $('#unit-org-modal').modal('toggle');
});
</script>
@endpush


{{--  --}}

@extends('layouts/contentLayoutMaster')

{{-- @section('title', 'Users') --}}

@section('content-button')
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <p class="card-text float-left relative top-8">
            <h5 class="form-header">{{ $data->title }}<a class="btn btn-primary btn-sm" href="javascript:history.back()"
                    style=" float: right;">Kembali</a></h5>
        </p>

        <form method="POST" action="{{ $data->action }}" enctype="multipart/form-data">
            @csrf
            @method( $data->method )
            <input type="hidden" name="id" id="id" value="{{ $data->user->id ??''}}">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for=""> Nama <sup class="text-danger">*</sup></label>
                        <div class=" col-sm-4">
                            <div class="input-group">
                                <input required class="form-control" placeholder="Nama" name="name" id="name"
                                    value="{{ $data->user->name ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for=""> NIP <sup class="text-danger">*</sup></label>
                        <div class="col-sm-4"><input class=" form-control" placeholder="NIP" required=""
                                value="{{ $data->user->NIP ??'' }}" name="NIP">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for=""> Email <sup class="text-danger">*</sup></label>
                        <div class="col-sm-4"><input type="email" class=" form-control" placeholder="Email" required=""
                                value="{{ $data->user->email ??'' }}" name="email">
                        </div>
                    </div>
                    @if($data->method=='POST')
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for=""> Password <sup class="text-danger">*</sup></label>
                        <div class="col-sm-4"><input type="password" class=" form-control" placeholder="Password"
                                required="" value="{{ $data->user->email ??'' }}" name="password"
                                autocomplete="new-password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for=""> Ulangi Password <sup
                                class="text-danger">*</sup></label>
                        <div class="col-sm-4"><input type="password" class=" form-control" placeholder="Password"
                                required="" value="{{ $data->user->email ??'' }}" name="password_confirmation"
                                autocomplete="new-password">
                        </div>
                    </div>
                    @endif
                    <div class="form-group row">

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="text-left form-buttons-w"><button class="btn btn-primary" type="submit">
                    Submit</button>
            </div>
        </form>

    </div>
</div>
@endsection