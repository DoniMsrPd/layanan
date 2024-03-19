@extends('layouts/contentLayoutMaster')

{{-- @section('title', 'Layanan') --}}

{{-- @section('button-right')

@endsection --}}

@section('content')

<style>
    .form-group.row {
        margin-bottom: 0px;
    }

    p {
        margin: 0px
    }

    .datepicker {
        z-index: 9999 !important;
    }
</style>
<form method="POST" action="{{ $data->action }}" enctype="multipart/form-data">
    @csrf
    @method( $data->method )
    @if(request()->edit||$data->method=='POST')
    <div class="card">

        <h4 class="card-header">

            <span style="justify-content:left !important;">
            {{ $data->title }} :: {{ $data->layanan->NoTicket ??'-' }} {{
            strtoupper($data->layanan->NoTicketRandom ??'') ??'' }} :: {{ $data->layanan && $data->layanan->TglTicket ? ToDmyHi($data->layanan->TglTicket) :''   }} </span><br>
            <span class="btn-group" role="group" style=" float: right;">
                @if(request()->merge)
                <a class="mb-2  btn btn-primary btn-sm" style="margin-right: 10px;"
                    href="{{ route('layanan.eskalasi', explode('/',url()->previous())[4])}}"><i
                        class="icon-feather-arrow-left"></i> Kembali</a>
                @if ($data->isShow)
                <a data-url="{{ url('layanan').'/merge/'.explode('/',url()->previous())[4].'/'.$data->layanan->Id }} "
                    class="mb-2  btn btn-warning btn-sm mergeLayanan" data-title="Data" title="Merge"
                    title-pos="up"><i class="icon-feather-git-merge"></i> Gabungkan Tiket</a>
                @endif
                @else
                <a class="mb-2  btn btn-primary btn-sm" style="margin-right: 10px;"
                    href="{{ !request()->edit ? (request()->kembali ? route(request()->kembali) : route('layanan.index')) : (request()->kembali ? route(request()->kembali)  : route('layanan.eskalasi', $data->layanan->Id))}}"><i
                        class="icon-feather-arrow-left"></i> Kembali</a>
                @if(auth()->user()->can('layanan.eskalasi.all')&&$data->layanan&&!request()->edit&&!pegawaiBiasa()&&!$data->layanan->DeletedAt)
                <a href="{{ route('layanan.eskalasi', $data->layanan->Id) }}?edit=1"
                    class="mb-2  btn btn-warning btn-sm" title="Ubah" title-pos="up"><i
                        class="icon-feather-edit-2"></i> Edit</a>
                @elseif(auth()->user()->can('layanan.eskalasi.all')&&$data->layanan&&!request()->edit&&!pegawaiBiasa()&&!$data->layanan->DeletedAt)
                <a href="{{ route('layanan.edit', $data->layanan->Id) }}" class="mb-2  btn btn-warning btn-sm"
                    title="Ubah" title-pos="up"><i class="icon-feather-edit-2"></i> Edit</a>
                @endif
                @if(auth()->user()->can('layanan.delete')&&$data->layanan&&!request()->edit&&!pegawaiBiasa()&&!$data->layanan->DeletedAt)
                @if(!$data->layanan->NoTicket||($data->layanan->NoTicket&&auth()->user()->hasRole(['SuperUser','Admin Proses Bisnis', 'Admin Probis Layanan'])))
                <a data-id="{{ $data->layanan->Id }}" data-url="{{ url('layanan').'/'.$data->layanan->Id }} "
                    data-redirect="{{ url('/layanan') }}" class="mb-2  btn btn-danger btn-sm deleteData"
                    data-title="Data" title="Hapus" title-pos="up"><i class="trash"></i> Delete</a>
                @endif
                @endif
                @endif
            </span>
        </h4>
        <div class="card-body">
            @include('layanan.layanan.form-tiket-body')
        </div>
    </div>
    @else
    @if(request()->merge)

    <div class="element-wrapper" style="padding-bottom: 10px;">
        <div class="element-box">
            <h5 class="form-header">Informasi Layanan Baru</h5>
            <div class="row">
                <div class="col-lg-6">
                    {!! strip_tags(nl2br($data->layananBaru->PermintaanLayanan),"<p><br>") !!}
                </div>
            </div>
        </div>
    </div>
    @endif
    {{-- <div id="accordion" class="mb-5"> --}}
        <div class="card">

            <h4 class="card-header">
                <span style="justify-content:left !important;">
                {{ $data->title }} &nbsp;&nbsp;&nbsp;  :: &nbsp;&nbsp;&nbsp; {{ $data->layanan->NoTicket ??'-' }} &nbsp;&nbsp;&nbsp;  :: &nbsp;&nbsp;&nbsp; {{
                strtoupper($data->layanan->NoTicketRandom ??'') ??'' }} &nbsp;&nbsp;&nbsp;::&nbsp;&nbsp;&nbsp; <span id="StatusHeader">{{ optional($data->layanan->status)->Nama }}</span>  &nbsp;&nbsp;&nbsp;::&nbsp;&nbsp;&nbsp;{{ $data->layanan->TglTicket ? ToDmyHi($data->layanan->TglTicket) :''   }}
                </span>
                <span class="btn-group" role="group" style=" float: right;">

                    @if(!request()->pending)
                        @if(request()->merge)
                        <a  class="mb-2  btn btn-primary btn-sm" style="margin-right: 10px;"
                            href="{{ route('layanan.eskalasi', explode('/',url()->previous())[4])}}"><i
                                class="icon-feather-arrow-left"></i> Kembali</a>
                        @if($data->isShow)
                        <a style="margin-left: 10px" data-url="{{ url('layanan').'/merge/'.$data->layanan->Id.'/'.explode('/',url()->previous())[4] }} "
                            class="mb-2  btn btn-warning btn-sm mergeLayanan ml-3" data-title="Data" title="Merge"
                            title-pos="up"><i class="icon-feather-git-merge"></i> Gabungkan Tiket</a>
                        @endif
                        @else

                            @php
                                $segment = explode("/",url()->previous())
                            @endphp
                        <a class="mb-2  btn btn-primary btn-sm mr-3" style="margin-right: 10px;"
                        @if(end($segment)=='eskalasi?edit=1')
                            href=" {{ !request()->edit ? (request()->kembali ? route(request()->kembali) :
                        route('layanan.index')) : route('layanan.eskalasi', $data->layanan->Id)}}"
                        @else
                            onclick="history.back()"  style="color: white;margin-right: 10px"
                        @endif>
                        <i class="icon-feather-arrow-left"></i> Kembali</a>
                        @if(auth()->user()->can('layanan.eskalasi.all')&&$data->layanan&&!request()->edit&&!pegawaiBiasa()&&!$data->layanan->DeletedAt)
                        <a style="margin-right: 10px" href="{{ route('layanan.eskalasi', $data->layanan->Id) }}?edit=1"
                            class="mb-2  btn btn-warning btn-sm mr-3" title=" Ubah" title-pos="up"><i
                                class="icon-feather-edit-2"></i> Edit</a>
                        @elseif(auth()->user()->can('layanan.eskalasi.all')&&$data->layanan&&!request()->edit&&!pegawaiBiasa()&&!$data->layanan->DeletedAt)
                        <a style="margin-left: 10px" href="{{ route('layanan.edit', $data->layanan->Id) }}"
                            class="mb-2  btn btn-warning btn-sm" title="Ubah" title-pos="up"><i
                                class="icon-feather-edit-2"></i> Edit</a>
                        @endif
                        @if(auth()->user()->can('layanan.delete')&&$data->layanan&&!request()->edit&&!pegawaiBiasa()&&!$data->layanan->DeletedAt)
                        @if(!$data->layanan->NoTicket||($data->layanan->NoTicket&&auth()->user()->hasRole(['SuperUser','Admin Proses Bisnis', 'Admin Probis Layanan'])))
                        <a data-id="{{ $data->layanan->Id }}"
                            data-url="{{ url('layanan').'/'.$data->layanan->Id }} "
                            data-redirect="{{ url('/layanan') }}" class="mb-2  btn btn-danger btn-sm deleteData"
                            data-title="Data" title="Hapus" title-pos="up"><i class="trash"></i>
                            Delete</a>
                        @endif
                        @endif
                        @endif
                    @endif
                </span>
            </h4>
            <div class="card-body">
                @include('layanan.layanan.form-tiket-body')
            </div>
        </div>
    {{-- </div> --}}
    @if($data->layanan && $data->layanan->NoTicket )
    <div class="card" >
        <div class="card-header">
            <h4 class="form-header">Informasi Layanan</h4>
        </div>
        <div class="card-content">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label class="col-form-label col-sm-3" for=""> Informasi Layanan</label>
                            <div class=" col-sm-10 col-form-label">
                                {!! strip_tags(nl2br($data->layanan->PermintaanLayanan),"<p><br>") ??'' !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label class="col-form-label col-sm-3" for=""> Catatan Lain</label>
                            <div class=" col-sm-10 col-form-label">
                                {!! strip_tags(nl2br($data->layanan->KeteranganLayanan),"<p><br>") !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif

    @if( request()->user()->can('layanan.eskalasi'))
        @if($data->eskalasi && !request()->edit)
            @include('layanan.layanan._form-eskalasi')
            @if(($data->showForm))
            <div class="card" >
                <div class="card-content">
                    <div class="card-body text-center">
                        <button class="btn btn-primary" type="submit">
                            Submit</button>
                    </div>
                </div>
            </div>
            @endif
            @include('layanan.layanan._container-tl')
        @else
            @if(($data->showForm))
            <div class="card" >
                <div class="card-content">
                    <div class="card-body text-center">
                        <button class="btn btn-primary" type="submit">
                            Submit</button>
                    </div>
                </div>
            </div>
            @endif
        @endif
    @elseif(pegawaiBiasa())
        @include('layanan.layanan._container-tl')
    @endif
</form>

@if($data->layanan)
@include('layanan.layanan._form-aset')
@include('layanan.layanan._form-ba-perbaikan')
@include('layanan.layanan._form-ba-peminjaman')
@endif

@endsection

{{-- ba perbikan --}}
@include('core.modals.pegawai')
@include('core.modals.pegawai2')
@include('core.modals.pegawai3')
@include('core.modals.pegawai4')
@include('core.modals.pegawai5')

{{-- ba peminjaman --}}
@include('core.modals.pegawai6')
@include('core.modals.pegawai7')
@include('core.modals.pegawai8')
{{-- form aset --}}
@include('core.modals.pegawai9')

@include('core.modals.service_catalog')
@include('core.modals.sla')
@include('core.modals.template')
@include('layanan.layanan.modal.group_solver')
@include('layanan.layanan.modal.solver')
@include('layanan.layanan.modal.persediaan')
@include('layanan.layanan.modal.peminjaman')
@include('layanan.layanan.modal.aset')
@include('layanan.layanan.modal.layanan')
@include('layanan.layanan.modal.kategori')
@include('layanan.layanan.modal.layanan_aset')
@push('scripts')
@include('core._script-delete')
@if(request()->segment(1)<>'layanan-create-tiket')
@include('setting.aset._script')
@endif

@include('layanan.layanan._script-form')

<script>
    $( document ).ready(function() {
        $(document).on("click",'.pilih-service-catalog',function () {
            $('#ServiceCatalogId').val($(this).data('id'))
            $('#ServiceCatalogKode').val($(this).data('kode'))
            $('#ServiceCatalogNama').val($(this).data('nama'))
            $('#ServiceCatalog').html($(this).data('kode')+ ' '+$(this).data('nama'))
            $('#ServiceCatalogDetailId').val(null)
            $('#ServiceCatalogDetail').html(null)
            $('#serviceCatalog-Modal').modal('toggle');
        });
        $(document).on("click",'.pilih-sla',function () {
            $('#ServiceCatalogDetailId').val($(this).data('id'))
            $('#ServiceCatalogDetail').html($(this).data('nama'))
            $('#serviceCatalogDetail-Modal').modal('toggle');
        });
        $(document).on('click', '.mergeLayanan', function() {
            let urlMerge = $(this).data('url');

            swal({ title: 'Konfirmasi',
                text: "Kamu yakin akan Gabungkan Tiket ?",
                type:'warning',
                showCancelButton:true,
                cancelButtonColor:'#d33',
                confirmButtonColor:'#3085d6',
                confirmButtonText:'<i class="fa fa-check-circle"></i> Ya, Hapus ini',
                cancelButtonText: '<i class="fa fa-times-circle"></i> Batal'
            }).then(function () {
                window.location.replace(urlMerge)
            })
        })
    });

    $(document).on("click",'.lookup-layanan',function () {
        $('#layanan-dt').DataTable().ajax.url("{!! route('layanan.datatables') !!}?Nip="+$('#Nip').val()+'&Exclude='+$('#id').val()).load();
        $('#layanan-modal').modal('toggle');
    });
</script>

@if($data->eskalasi && request()->user()->can('layanan.eskalasi')||pegawaiBiasa())
@include('layanan.layanan._script-ba-perbaikan')
@include('layanan.layanan._script-ba-peminjaman')
@include('layanan.layanan._script-eskalasi')
@include('layanan.layanan._script-tl')
<script>
    $( document ).ready(function() {


        $(document).on("change",'#isPihakLuar',function () {
            if($(this).is(":checked")){
                $('.pihak1luar').show()
                $('#pihak1dalam').hide()
            } else {
                $('#pihak1dalam').show()
                $('.pihak1luar').hide()
            }
        });
        $(document).on('show.bs.collapse hide.bs.collapse', '.collapse', function(e) {
            e.stopPropagation();
            console.log('event triggered');
        });
        loadDataTL("{{ $data->layanan->Id ?? '' }}")
        showGroupSolver("{{ $data->layanan->Id ?? '' }}")
        showSolver("{{ $data->layanan->Id ?? '' }}")
    });
</script>
@endif
@endpush


@section('vendor-script')
<script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
<script src="{{ asset('vendors/js/editors/ckeditor/ckeditor.js') }}"></script>
@endsection
