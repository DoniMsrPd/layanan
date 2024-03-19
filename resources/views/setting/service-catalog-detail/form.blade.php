@extends('layouts/contentLayoutMaster')

@section('content')

<div class="card">
    <h5 class="card-header">{{ $data->title }}<a class="btn btn-primary btn-sm" href="javascript:history.back()"
            style=" float: right;">Kembali</a></h5>
    <div class="card-body">
        <form method="POST" action="{{ $data->action }}" enctype="multipart/form-data">
            @csrf
            @method( $data->method )
            <input type="hidden" class="form-control" name="id" id="id" value="{{ $data->catalogDetail->Id ??'' }}">
            <input type="hidden" class="form-control" name="serviceCatalogId" id="serviceCatalogId"
                value="{{ $data->catalog->Id ??'' }}">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for=""> Kode ITSM <sup
                                class="text-danger">*</sup></label>
                        <div class=" col-sm-9">
                            <b>{{ $data->catalog->Kode ??'' }}</b>
                            <br>
                            {{ $data->catalog->Nama ??'' }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for=""> Nomor Urut <sup
                                class="text-danger">*</sup></label>
                        <div class="col-sm-3"><input class=" form-control" required=""
                                value="{{ $data->catalogDetail->NoUrut ??'' }}" name="noUrut" {{ $data->readonly ?? ''
                            }}>
                        </div>
                    </div>
                    <div class="form-group row"><label class="col-sm-3 col-form-label">Nama SLA <sup
                                class="text-danger">*</sup></label>
                        <div class="col-sm-8"><textarea class="form-control" rows="3" name="nama" {{
                                $data->readonly ?? '' }}>{{ $data->catalogDetail->Nama ??'' }}</textarea></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for=""> Norma Waktu <sup
                                class="text-danger">*</sup></label>
                        <div class="col-sm-3"><input class=" form-control" required=""
                                value="{{ $data->catalogDetail->NormaWaktu ??'' }}" name="normaWaktu" {{ $data->readonly
                            ?? '' }}>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">

                    <div class="form-group row"><label class="col-sm-3 col-form-label" for="">SLA Waktu (Jam)</label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="number" class="form-control" name="waktu"
                                    value="{{ $data->catalogDetail->Waktu ??'' }}" {{ $data->readonly ?? '' }}>

                                <div class="input-group-prepend">
                                    <div class="input-group-text">Jam</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row"><label class="col-sm-3 col-form-label" for="">SLA Limit (Jam)</label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="number" class="form-control" name="limit"
                                    value="{{ $data->catalogDetail->Limit ??'' }}" {{ $data->readonly ?? '' }}>

                                <div class="input-group-prepend">
                                    <div class="input-group-text">Jam</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for="">Jenis Perhitungan</label>
                        <div class="col-sm-6">
                            <input type="radio" required name="jenisPerhitungan" value="0" @if(($data->catalogDetail)
                            &&$data->catalogDetail->JenisPerhitungan==0 ) checked @endif {{ $data->readonly ?? '' }}>
                            Hari Kerja <br>
                            <input type="radio" required name="jenisPerhitungan" value="1" @if(($data->catalogDetail)
                            &&$data->catalogDetail->JenisPerhitungan==1 ) checked @endif {{ $data->readonly ?? '' }}>
                            Hari Kalender
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-form-label col-sm-3" for="">Jenis Layanan</label>
                        <div class="col-sm-6">
                            <input type="radio" name="jenisLayanan" value="r" @if((($data->catalogDetail)
                            &&$data->catalogDetail->JenisLayanan=='r') || !(substr($data->KdUnitOrgLayanan, 0, 6) == '100205')) checked @endif {{ $data->readonly ?? '' }}>
                            Request
                            @if (substr($data->KdUnitOrgLayanan, 0, 6) == '100205')
                            <br>
                            <input type="radio" name="jenisLayanan" value="i" @if(($data->catalogDetail)
                            &&$data->catalogDetail->JenisLayanan=='i' ) checked @endif {{ $data->readonly ?? '' }}>
                            Incident
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @if (!isset($data->readonly))
            <div class="text-left form-buttons-w"><button class="btn btn-primary" type="submit">
                    Submit</button>
            </div>
            @endif
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    @if(($data->catalog))
        $( function() {
            var tglStart = '{{ $data->catalog->TglStart }}';
            $('#tglStart').datepicker({ dateFormat: 'dd M yy'}).datepicker("setDate", new Date(tglStart));
            var tglEnd = '{{ $data->catalog->TglEnd }}';
            $('#tglEnd').datepicker({ dateFormat: 'dd M yy'}).datepicker("setDate", new Date(tglEnd));
        });
    @else

        $( function() {
            $('.datepicker').datepicker({ dateFormat: 'dd M yy'}).datepicker("setDate", new Date());
        });
    @endif
</script>
@endpush
