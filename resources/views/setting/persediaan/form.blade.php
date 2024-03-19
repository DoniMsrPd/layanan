@extends('core.layouts.master')

@section('content')

<div class="element-wrapper">
    <div class="element-box">

        <h5 class="form-header">{{ $data->title }}<a class="btn btn-primary btn-sm" href="javascript:history.back()"
                style=" float: right;">Kembali</a></h5>
        <form method="POST" action="{{ $data->action }}" enctype="multipart/form-data">
            @csrf
            @method( $data->method )
            <input type="hidden" class="form-control" name="id" id="id" value="{{ $data->persediaan->Id ??'' }}">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for=""> Kode Barang <sup
                                class="text-danger">*</sup></label>
                        <div class=" col-sm-6"><input class=" form-control" placeholder="Kode Barang" required=""
                                value="{{ $data->persediaan->KdBrg ??'' }}" name="KdBrg">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for=""> Nama Barang <sup
                                class="text-danger">*</sup></label>
                        <div class=" col-sm-6"><input class=" form-control" placeholder="Nama Barang" required=""
                                value="{{ $data->persediaan->NmBrg ??'' }}" name="NmBrg">
                        </div>
                    </div>
                    <div class="form-group row"><label class="col-sm-3 col-form-label">Nama Barang Lengkap <sup
                        class="text-danger">*</sup></label>
                        <div class="col-sm-6"><textarea required class="form-control" rows="3" name="NmBrgLengkap">{{ $data->persediaan->NmBrgLengkap ??'' }}</textarea></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for=""> Qty <sup
                                class="text-danger">*</sup></label>
                        <div class=" col-sm-6"><input type="number" class=" form-control" placeholder="Qty" required=""
                                value="{{ $data->persediaan->Qty ??'' }}" name="Qty">
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

@push('scripts')
<script>
</script>
@endpush