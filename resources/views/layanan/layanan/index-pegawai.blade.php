@extends('layouts/contentLayoutMaster')

{{-- @section('title', 'Layanan') --}}

@section('content')
<style>
    /* .card .card-header {
        justify-content:left !important;
    } */

    .blink_me {
        animation: blinker 1s linear infinite;
    }

    @keyframes blinker {
        50% {
            opacity: 0;
        }
    }

    tr td p {
        margin: 0px
    }

    .dropdown-submenu {
        position: relative;
    }

    .dropdown-submenu a::after {
        transform: rotate(-90deg);
        position: absolute;
        right: 6px;
        top: .8em;
    }

    .dropdown-submenu .dropdown-menu {
        top: 0;
        left: 100%;
        margin-left: .1rem;
        margin-right: .1rem;
    }

    .dropdown-menu-right {
        right: 0;
        left: auto;
    }
</style>
<div class="row">
</div>
<div class="card">
    <h5 class="card-header">
        @if(request()->pending)
        Pending Set SLA
        @else
        Layanan

        <span class="btn-group" role="group" style=" float: right;">
            @can('layanan.create-tiket')
            <a class="btn btn-primary" style=" float: right;" href="{{ route('layanan.create-tiket') }}">Pembuatan
                Layanan</a>
            @endcan
            @can('layanan.create')
            @if (request()->KdUnitOrgOwnerLayanan)
                <a class="btn btn-success" style=" float: right;" href="{{ route('layanan.create') }}?KdUnitOrgOwnerLayanan={{ request()->KdUnitOrgOwnerLayanan }}">Permintaan
                    Layanan</a>
            @else


            <div class="dropdown">
                <a class="btn btn-success dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Permintaan Layanan </a>
                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                    @foreach ($data->MstUnitOrgLayananOwner as $item)
                    <a class="dropdown-item" href="{{ route('layanan.create') }}?KdUnitOrgOwnerLayanan={{ $item->KdUnitOrgOwnerLayanan }}">{{ $item->NmUnitOrgOwnerLayanan }}</a>
                    @endforeach
                </ul>
            </div>
            @endif
            @endcan
        </span>
        @endif

    </h5>
    <div class="card-body">
        <form action="" method="get" id="form">
            <div class="row">
                <div class="col-sm-4">
                    @if(auth()->user()->hasRole(['Operator', 'SuperUser', 'Admin Proses Bisnis', 'Admin Probis Layanan','Pejabat Struktural']))
                    <div class="form-group row"><label class="col-form-label col-sm-4" for=""> Group Solver</label>
                        <div class="col-sm-8">
                            <select class="form-control select2 form-filters" multiple="true" id="groupSolver"
                                name="groupSolver[]">
                                @foreach ($data->groupSolver as $item)
                                <option @if(is_array(request()->groupSolver) &&
                                    in_array($item->Id,request()->groupSolver) || $item->Id==request()->groupSolver)
                                    selected @endif value="{{ $item->Id }}">{{
                                    $item->Kode }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if(request()->pending<>1)
                        <div class="form-group row"><label class="col-form-label col-sm-4" for=""> Solver</label>
                            <div class="col-sm-8">
                                <select class="form-control select2 form-filters" multiple="true" id="solver"
                                    name="solver[]">
                                    @foreach ($data->solver as $item)
                                    <option @if(is_array(request()->solver) && in_array($item->Nip,request()->solver) ||
                                        $item->Nip==request()->solver) selected @endif value="{{ $item->Nip }}">{{
                                        $item->NmPeg }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        @endif
                        <div class="form-group row"><label class="col-form-label col-sm-4" for=""> Jenis Layanan</label>
                            <div class="col-sm-8">
                                <select class="form-control select2 form-filters" id="KdUnitOrgOwnerLayanan"
                                    name="KdUnitOrgOwnerLayanan">
                                    <option value="">Semua</option>
                                    @foreach ($data->MstUnitOrgLayananOwner as $item)
                                    <option @if($item->KdUnitOrgOwnerLayanan==request()->KdUnitOrgOwnerLayanan)
                                        selected @endif value="{{ $item->KdUnitOrgOwnerLayanan }}">{{ $item->NmUnitOrgOwnerLayanan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row"><label class="col-form-label col-sm-4" for=""> Status
                                Layanan</label>
                            <div class="col-sm-8">
                                <select class="form-control select2 form-filters" multiple="true" id="statusLayanan"
                                    name="statusLayanan[]">
                                    @foreach ($data->statusLayanan as $item)
                                    <option @if(is_array(request()->statusLayanan) &&
                                        in_array($item->Id,request()->statusLayanan) ||
                                        $item->Id==request()->statusLayanan)
                                        selected @endif value="{{ $item->Id }}">{{ $item->Nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row"><label class="col-form-label col-sm-4" for=""> Tgl Layanan</label>
                            <div class="col-sm-4">
                                <input class="form-control form-filters" placeholder="Tanggal Start" value=""
                                    name="tglStart" id="tglStart">
                            </div>
                            <div class="col-sm-4">
                                <input class="form-control form-filters" placeholder="Tanggal End" value=""
                                    name="tglEnd" id="tglEnd">
                            </div>
                        </div>
                        <div class="form-buttons-w"><button class="btn btn-primary apply"> Apply</button><a
                                href="{{ route('layanan.index') }}" class="btn btn-danger clear"
                                style="margin-left: 10px"> Clear</a></div>
                </div>
                @if(request()->pending<>1)
                    <div class="col-sm-4">

                        @if(!pegawaiBiasa())
                        <div class="form-group row"><label class="col-form-label col-sm-4" for=""> Prioritas
                                Layanan</label>
                            <div class="col-sm-8">
                                <select class="form-control select2 form-filters" multiple="true" id="prioritasLayanan"
                                    name="prioritasLayanan[]">
                                    @foreach ($data->prioritasLayanan as $item)
                                    <option @if(is_array(request()->prioritasLayanan) &&
                                        in_array(strtolower($item->Id),request()->prioritasLayanan) ||
                                        strtolower($item->Id)==request()->prioritasLayanan) selected @endif value="{{
                                        strtolower($item->Id) }}">{{ $item->Id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row"><label class="col-form-label col-sm-4" for=""> ITSM</label>
                            <div class="col-sm-8">
                                <select class="form-control select2 form-filters" multiple="true" id="serviceCatalog"
                                    name="serviceCatalog[]">
                                    <option @if(is_array(request()->serviceCatalog) &&
                                        in_array("undefined",request()->serviceCatalog) ||
                                        "undefined"==request()->serviceCatalog) selected @endif value="undefined">ITSM
                                        KOSONG</option>
                                    @foreach ($data->serviceCatalog as $item)
                                    <option @if(is_array(request()->serviceCatalog) &&
                                        in_array($item->Kode,request()->serviceCatalog) ||
                                        $item->Kode==request()->serviceCatalog) selected @endif value="{{ $item->Kode
                                        }}">{{
                                        $item->Kode }} {{ $item->Nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row"><label class="col-form-label col-sm-4" for=""> Tematik</label>
                            <div class="col-sm-8">
                                <select class="form-control select2 form-filters" multiple="true" id="tematik"
                                    name="tematik[]">
                                    @foreach ($data->tematik as $item)
                                    <option @if(is_array(request()->tematik) && in_array($item->Id,request()->tematik)
                                        ||
                                        $item->Id==request()->tematik) selected @endif value="{{ $item->Id }}">{{
                                        $item->Tema }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row"><label class="col-form-label col-sm-4" for=""> SLA</label>
                            <div class="col-sm-8">
                                <select class="form-control select2 form-filters" multiple="true" id="sla" name="sla[]">
                                    <option @if(is_array(request()->sla) &&
                                        in_array('mendekati_deadline',request()->sla) ||
                                        'mendekati_deadline'==request()->sla) selected @endif
                                        value="mendekati_deadline">Mendekati Deadline</option>
                                    <option @if(is_array(request()->sla) && in_array('melewati',request()->sla) ||
                                        'melewati'==request()->sla) selected @endif value="melewati">Melewati</option>
                                    <option @if(is_array(request()->sla) && in_array('tidak_melewati',request()->sla) ||
                                        'tidak_melewati'==request()->sla) selected @endif value="tidak_melewati">Tidak
                                        Melewati</option>
                                </select>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
            </div>
            <input type="hidden" name="assignToMe" value="{{ request()->assignToMe }}">
            <input type="hidden" name="updatedByMe" value="{{ request()->updatedByMe }}">
            <input type="hidden" name="createdByMe" value="{{ request()->createdByMe }}">
            <input type="hidden" name="pending" value="{{ request()->pending }}">
        </form>
        <br>

        <form action="" id="form" class="form-inline justify-content-sm-end" style="margin-bottom: 5px;">
            <table width="100%">
                <tr>
                    <td colspan="3"></td>
                    <td colspan="3" width="25%">

                        <input value="{{ request()->q }}" placeholder="Cari Layanan ...." id="cari" autofocus="true"
                            class="form-control form-control-sm rounded bright form-filters" name="q">
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <br>
</div>
<div id="hasilpencarian">
    @foreach($data->layanan as $layanan)


    @php
    $statusLayanan = $layanan->NamaStatusLayanan?? '-';
    if(pegawaiBiasa()&&isset($layanan->StatusLayanan) &&$layanan->StatusLayanan==6){
    $statusLayanan = "<span class='blink_me' style='color:red;font-weight: bold;'> {$layanan->NamaStatusLayanan} <i
            class='os-icon os-icon-mail-12'></i></span>";

    }
    @endphp
    @php
    $id = $layanan->ParentId ?? $layanan->Id;
    $url = route('layanan.eskalasi', $id) ;
    if (pegawaiBiasa() && !$layanan->NoTicket && !$layanan->ParentId) {
    $url = route('layanan.edit', $id);
    }
    $NoTicket = '<a style="color:blue; font-style: italic;" href="' . $url . '">'
        .
        $layanan->NoTicket . ' &nbsp; &nbsp;:: &nbsp; &nbsp; ' . strtoupper($layanan->NoTicketRandom) . '</a>';
    @endphp
    <div class="card mb-3">
        <div class="card-header" >
            <span style="justify-content:left !important;">
                {!! $statusLayanan !!} &nbsp; &nbsp;:: &nbsp; &nbsp; {!! $NoTicket !!} <span
                    style="float: right;color: grey"> &nbsp; &nbsp; {{
                    pegawaiBiasa() ?
                    ToDmyHi($layanan->CreatedAt) : ToDmy($layanan->TglLayanan)
                    }}</span>
            </span>

            <span style="justify-content:right !important;">
                {{ $layanan->owner->NmUnitOrgOwnerLayanan ?? '' }}
            </span>
        </div>
        <div class="card-body" style="padding-bottom: 6px;padding-top: 10px;border-top: 1px solid rgba(0,0,0,.125);">
            <div class="ur-kegitan">
                {!! strip_tags(nl2br($layanan->PermintaanLayanan),"<p><br>") ??'' !!}
            </div>

        </div>
        @if (count($layanan->tl) >0)
        <div class="card-body" style="padding-top: 10px;border-top: 1px solid rgba(0,0,0,.125);">
            <div class="ur-kegitan">
                <table width="100%">
                    @foreach ($layanan->tl as $tl)
                    <tr>
                        <td style="padding: 5px" valign="top"> <span style="color: blue"> {{ ToDmyHi($tl->CreatedAt)
                                }}</span><br>
                                @if ($layanan->KdUnitOrgOwnerLayanan == '100205000000')
                                <span >
                                    @foreach ($tl->status as $status)
                                    Status changed @if($status->statusAwal) from @endif {{ optional($status->statusAwal)->Nama }} to {{
                                    optional($status->statusAkhir)->Nama }}
                                    <br>
                                    @endforeach
                                </span>
                                @endif
                                <br>
                            <div style="padding-left: 20px;color:black" >{!! $tl->Keterangan !!}</div>

                        </td>
                    </tr>
                    @if ($tl->files->count() > 0)
                    <tr>
                        <td style="padding-left: 20px">
                            <ul class="pt-1 mb-0 list-file" style="padding: 20px; width: 50%;">
                                @foreach ($tl->files as $key => $file)
                                <li>
                                    <a href="/core/{{ $file->PathFile }}" class="f-16" target="_blank">
                                        <span class="mdi mdi-file-pdf"></span> {{
                                        \Illuminate\Support\Str::limit($file->NmFileOriginal, 60) }}
                                    </a>
                                    @if((auth()->user()->can('layanan.tl.delete-all')||(auth()->user()->NIP==$file->createdBy))&&!$tl->layanan->DeletedAt)
                                    <span style="cursor: pointer;" data-id="{{ $file->Id }}"
                                        data-title="{{ $file->NmFileOriginal }}"
                                        data-url="/core/storage/{{ $file->Id }}"
                                        class="text-danger deleteData float-right"><i
                                            class="icon-feather-trash-2"></i></span>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    @endif
                    @if ($tl->filesOld->count() > 0)
                    <tr>
                        <td style="padding-left: 20px">
                            <ul class="pt-1 mb-0 list-file" style="padding: 20px; width: 50%;">
                                @foreach ($tl->filesOld as $key => $file)
                                <li>
                                    <a href="/core/{{ $file->PathFile }}&NmFile={{ $file->NmFile }}" class="f-16"
                                        target="_blank">
                                        <span class="mdi mdi-file-pdf"></span> {{
                                        \Illuminate\Support\Str::limit($file->NmFileOriginal, 60) }}
                                    </a>
                                    @if((auth()->user()->can('layanan.tl.delete-all')||(auth()->user()->NIP==$file->createdBy))&&!$tl->layanan->DeletedAt)
                                    <span style="cursor: pointer;" data-id="{{ $file->Id }}"
                                        data-title="{{ $file->NmFileOriginal }}"
                                        data-url="/core/storage/{{ $file->Id }}"
                                        class="text-danger deleteData float-right"><i
                                            class="icon-feather-trash-2"></i></span>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                    @php
                    $start = new DateTime();
                    $end = new DateTime($layanan->TglSolved);
                    $day = $start->diff($end)->format('%d');
                    @endphp
                    @if ($day == 0 && $layanan->StatusLayanan ==4 && ($layanan->KdUnitOrgOwnerLayanan == '100205000000'))
                    <tr>
                        <td><a class="btn btn-primary selesaikan" style=" float: right;color:white"
                                data-url="{{ route('layanan-tl.selesaikan',$layanan->Id) }}">Selesaikan Layanan
                                Ini</a></td>
                    </tr>
                    @elseif ($layanan->StatusLayanan ==6)
                        <tr>
                            <td><a href="{{ route("layanan.eskalasi",$layanan->Id) }}?TambahInformasi=1" class="btn btn-primary" style=" float: right;color:white">Tambah Informasi</a></td>
                        </tr>
                    @endif

                </table>


            </div>

        </div>
        @endif

    </div>
    @endforeach
    Showing {{ $data->layanan->firstItem() }} to {{ $data->layanan->lastItem() }} of {{ $data->layanan->total() }}
    {{
    $data->layanan->appends(request()->all())->links()
    }}
    <br><br>
</div>

<div class="modal fade" id="news" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-lgg" role="document" style="max-width: 550px; max-height: 450px;">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <h5 class="modal-title" id="exampleModalLabel">Daftar Entitas Luar</h5> --}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="text-align: center;">
                <img src="/Merah Bergambar Linimasa Infografik.jpg" style="width: 50%;">
            </div>
        </div>
    </div>
</div>
@endsection
@include('layanan.layanan.modal.group_solver')
@include('layanan.layanan.modal.solver')
@push('scripts')
@include('layanan.layanan._script-eskalasi')
<script>
    $(function() {

        $(document).on("click",'.dropdown-toggle',function () {
            $(this).parent().toggleClass('show')
        })
        @if (session()->has('flash'))
            // $('#news').modal('toggle');
        @endif
        $(document).on('click', '.selesaikan', function() {
            let that = $(this);
            let urlDelete = that.data('url');
            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin akan menyelesaikan permintaan layanan ini ?",
                type:'warning',
                showCancelButton:true,
                cancelButtonColor:'#d33',
                confirmButtonColor:'#3085d6',
                confirmButtonText:'<i class="fa fa-check-circle"></i> Ya,',
                cancelButtonText: '<i class="fa fa-times-circle"></i> Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let csrf_token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: urlDelete,
                        type : "POST",
                        data: {
                            '_token':csrf_token
                        },
                        success: function (response) {
                            if(response.success){
                                location.reload()
                            }
                        },
                    });
                }
            })
        })
        $(document).on("click",'.update-status-layanan',function () {

        LayananId = $(this).data('layanan_id');
        var StatusLayanan = $(this).data('status_layanan');
        var urlSave = '/layanan-tl';
        var _token = $('meta[name="csrf-token"]').attr('content');
        data = new FormData();
        data.append('StatusLayanan', StatusLayanan);
        data.append('LayananId', LayananId);
        data.append('_token', _token);
        $.ajax({
            url: urlSave,
            type: "POST",
            data: data,
            contentType: false,
            processData: false,
            beforeSend: function (data){
                $('#loadingAnimation').addClass('lds-dual-ring');
            },
            success: function (data) {
                toastr.info(data.message);
                $('#table').DataTable().ajax.url("{!! route('layanan.datatables') !!}?"+$('#form').serialize()+'&pending={{ request()->pending }}').load();
                $('#loadingAnimation').removeClass('lds-dual-ring');
            },
            error : function () {
                alert('Terjadi kesalahan, silakan reload');
                $('#loadingAnimation').removeClass('lds-dual-ring');
            }
        })
        })
        $(document).on("click",'.dropdown-menu a.dropdown-toggle',function () {
            if (!$(this).next().hasClass('show')) {
                $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
            }
            var $subMenu = $(this).next(".dropdown-menu");
            $subMenu.toggleClass('show');


            $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
                $('.dropdown-submenu .show').removeClass("show");
            });


            return false;
        });

        @if(request()->tglStart)
            $('#tglStart').flatpickr({
                dateFormat: 'd M Y',
                defaultDate: new Date('{{ request()->tglStart }}')
            });
            $('#tglEnd').flatpickr({
                dateFormat: 'd M Y',
                defaultDate: new Date('{{ request()->tglEnd }}')
            });
        @else
            $('#tglStart').flatpickr({
                dateFormat: 'd M Y',
                defaultDate: new Date('{{ date("Y-m-d",  strtotime('first day of january this year')) }}')
            });
            $('#tglEnd').flatpickr({
                dateFormat: 'd M Y',
                defaultDate: new Date()
            });
        @endif
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                method: 'POST',
                url : "{!! route('layanan.datatables') !!}?"+$('#form').serialize()+'&pending={{ request()->pending }}',
            },
            columns: [
                // { data: 'Id', name: 'Id' },
                {
                    data: 'NoTicket',
                    name: 'NoTicket' ,
                    width:'5%',
                },
                {
                    data: 'TglLayanan',
                    width:'4%',
                    searchable:false
                },
                {
                    data: 'ServiceCatalogKode',
                    render: function(data, type, row, meta) {
                        return `${row.ServiceCatalogKode ??'-'} <br> ${row.ServiceCatalogNama ??''}`
                    },
                    width:'12%',
                },
                {
                    data: 'Nip',
                    render: function(data, type, row, meta) {
                        return `${row.Nip??'-'} <br> ${row.NmPeg??'-'}`
                    }  ,
                    width:'10%',
                },
                {
                    data: 'KdUnitOrg',
                    render: function(data, type, row, meta) {
                        return `${row.NmUnitOrg??'-'} <br> ${row.NmUnitOrgInduk!=row.NmUnitOrg ? row.NmUnitOrgInduk:''}`
                    }  ,
                    width:'12%',
                },
                {
                    data: 'PermintaanLayanan',
                    width:'35%',
                },
                {
                    data: 'PrioritasLayanan',
                    searchable:false,
                    width:'5%',
                },
                {
                    data: 'StatusLayanan',
                    searchable:false,
                    width:'5%',
                },
                {
                    data: 'operator_open',
                    render: function(data, type, row, meta) {
                        return `${row.NipOperatorOpen??'-'} <br> ${row.operator_open?.NmPeg??'-'}`
                    }  ,
                    searchable:false,
                    width:'10%',
                },
                {
                    data: 'Id',
                    render: function(data, type, row, meta) {
                        return `<span style="color:blue">${row.AllGroupSolver??'-'} </span><br><span style="color:green"> ${row.AllSolver??'-'}</span>`
                    }  ,
                    searchable:false,
                    width:'10%',
                },
                {
                    data: 'UpdatedAt',
                    searchable:false,
                    width:'5%',
                },
                {
                    data: 'NmPeg',
                    visible:false,
                },
                {
                    data: 'NoTicketRandom',
                    visible:false,
                },
                {
                    data: 'NmUnitOrg',
                    visible:false,
                },
                {
                    data: 'NmUnitOrgInduk',
                    visible:false,
                },
            ],
            rowCallback: function( row, data, index ) {
                if(data.DeletedAt!=null){
                    $('td', row).closest('tr').css({
                        'color' : 'red',
                        'text-decoration' : 'line-through',
                    });
                    $('td', row).find('.pick').prop('checked',true);
                    $('td', row).find('button').attr('disabled',true);
                    $('td', row).find('button').css('cursor','no-drop');
                    $('#checkall').prop('checked',false);
                }
            }
        });

        $('#table_filter').html(`
            <div class="form-inline" style="float:right">
                @if(auth()->user()->hasRole(['Solver','Solver LataLati']))
                <span class="mr-3">
                    <input type="checkbox" id="createdByMe" value="1" @if(request()->createdByMe) checked @endif
                    class="mr-2">
                    Created By Me
                </span>
                <span class="mr-3">
                    <input type="checkbox" id="updatedByMe" value="1" @if(request()->updatedByMe) checked @endif
                    class="mr-2">
                    Updated By Me
                </span>
                <span class="mr-3">
                    <input type="checkbox" id="assignToMe" value="1" @if(request()->assignToMe) checked @endif
                    class="mr-2">
                    Assign To Me
                </span>
                @endif
                <div class="input-group"> <input class="form-control form-filterss" placeholder="Search" id="mySearchText"
                        style="margin-right: 0px;">
                    <div class="input-group-append"> <button class="btn btn-outline-secondary" type="button" id="mySearchButton"><i
                                class="icon-feather-search"></i></button> <a class="btn btn-success" id="exportBtn"><img
                                src="/svg/excel.PNG" width="23px;"></a> </div>
                </div>
            </div>`)
        $('#mySearchButton').on( 'keyup click', function () {
            table.search($('#mySearchText').val()).draw();
        } );

        $('#mySearchText').on( 'keyup', function (e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                table.search($('#mySearchText').val()).draw();
            }
        } );
        @if(!auth()->user()->hasAnyRole('Pejabat Struktural', 'Operator','Admin Proses Bisnis', 'Admin Probis Layanan','SuperUser','Solver'))
            var column = table.column(2);
            column.visible( ! column.visible() );
            var column = table.column(6);
            column.visible( ! column.visible() );
            var column = table.column(8);
            column.visible( ! column.visible() );
            var column = table.column(9);
            column.visible( ! column.visible() );
        @endif

        $('#assignToMe').on('change', function () {
            assignToMe = $('#assignToMe').prop('checked')?1:0;
            $('[name="assignToMe"]').val(assignToMe)
            $('form#form').submit();
        })
        $('#updatedByMe').on('change', function () {
            updatedByMe = $('#updatedByMe').prop('checked')?1:0;
            $('[name="updatedByMe"]').val(updatedByMe)
            $('form#form').submit();
        })
        $('#createdByMe').on('change', function () {
            createdByMe = $('#createdByMe').prop('checked')?1:0;
            $('[name="createdByMe"]').val(createdByMe)
            $('form#form').submit();
        })
        $('#exportBtn').on('click', function () {
            window.open("{{ route('layanan.export') }}?"+$('#form').serialize()+'&pending={{ request()->pending }}')
        })
    })
</script>
@include('core._script-delete')
@endpush
