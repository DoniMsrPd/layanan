@extends('layouts/contentLayoutMaster')

@section('content')

<div class="card">
    <h5 class="card-header">{{ $data->title }}<a class="btn btn-primary btn-sm" href="javascript:history.back()"
                style=" float: right;">Kembali</a></h5>
                <div class="card-body">
        <form method="POST" action="{{ $data->action }}" enctype="multipart/form-data">
            @csrf
            @method( $data->method )
            <input type="hidden" name="KdUnitOrgOwnerLayanan" value="{{ kdUnitOrgOwner() }}">
            <input type="hidden" name="unitOrgSelected" id="unitOrgSelected" value="{{ $data->unitOrgSelected }}">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for=""> Unit Org </label>
                        <div class=" col-sm-8">
                                <div class="input-group">
                                    <input type="hidden" name="Id" id="Id" value="{{ $data->groupSolver->Id ??'' }}">
                                    <input  style="cursor: pointer;" readonly class="form-control lookup-unit-org" placeholder="" name="Nama" id="Nama" value="{{ $data->groupSolver->Nama ?? '' }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary lookup-unit-org" type="button">{!! btnSearch() !!}</button>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for=""> Kode <sup
                                class="text-danger">*</sup></label>
                        <div class="col-sm-4"><input class=" form-control" placeholder="Kode" required=""
                                value="{{ $data->groupSolver->Kode ??'' }}" name="Kode">
                        </div>
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
@include('core.modals.unit_org')

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