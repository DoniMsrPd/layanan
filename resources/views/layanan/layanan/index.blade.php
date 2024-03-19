@extends('layouts/contentLayoutMaster')

{{-- @section('title', 'Layanan')

@section('button-right')


@endsection --}}

@section('content')
<div class="card">
    <h5 class="card-header">
        @if(request()->pending)
        Pending Set SLA
        @else
        Layanan
        @endif
            <span class="btn-group" role="group" style=" float: right;">
                @can('layanan.create')

                <div class="dropdown">
                    <a class="btn btn-success dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Permintaan Layanan </a>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                        @foreach ($data->MstUnitOrgLayananOwner as $item)
                        <a class="dropdown-item" href="{{ route('layanan.create') }}?KdUnitOrgOwnerLayanan={{ $item->KdUnitOrgOwnerLayanan }}">{{ $item->NmUnitOrgOwnerLayanan }}</a>
                        @endforeach
                    </ul>
                  </div>
                @endcan
                @can('layanan.create-tiket')
                <a class="btn btn-sm btn-primary" style=" float: right;margin-left:10px"
                    href="{{ route('layanan.create-tiket') }}">Pembuatan Layanan</a>
                @endcan
            </span>
        </h5>
    <div class="card-body">
        <h5 class="form-header">

        </h5>

        <form action="" method="get" id="form">
            <div class="row">
                <div class="col-sm-4">
                    @if(auth()->user()->hasRole(['Operator', 'SuperUser', 'Admin Proses Bisnis', 'Admin Probis Layanan','Pejabat Struktural']))
                    <div class="form-group row mb-1"><label class="col-form-label col-sm-4" for=""> Group Solver</label>
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
                        <div class="form-group row mb-1"><label class="col-form-label col-sm-4" for=""> Solver</label>
                            <div class="col-sm-8">
                                <select class="form-control select2 form-filters" multiple="true" id="solver"
                                    name="solver[]">
                                    @foreach ($data->solver as $item)
                                    <option @if(is_array(request()->solver) && in_array($item['Nip'],request()->solver) ||
                                        $item['Nip']==request()->solver) selected @endif value="{{ $item['Nip'] }}">{{
                                        $item['NmPeg'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        @endif
                        <div class="form-group row mb-1"><label class="col-form-label col-sm-4" for=""> Status
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
                        <div class="form-group row mb-1"><label class="col-form-label col-sm-4" for=""> Tgl Layanan</label>
                            <div class="col-sm-4">
                                <input class="form-control form-filters" placeholder="Tanggal Start" value=""
                                    name="tglStart" id="tglStart">
                            </div>
                            <div class="col-sm-4">
                                <input class="form-control form-filters" placeholder="Tanggal End" value=""
                                    name="tglEnd" id="tglEnd">
                            </div>
                        </div>
                        <div class="form-buttons-w">
                            <button class="btn btn-sm btn-primary apply"> Apply</button>
                            <a href="{{ route('layanan.index') }}" class="btn btn-sm btn-danger clear" style="margin-left: 10px"> Clear</a>
                        </div>
                </div>
                @if(request()->pending<>1)
                    <div class="col-sm-4">

                        @if(!pegawaiBiasa())
                        <div class="form-group row mb-1"><label class="col-form-label col-sm-4" for=""> Prioritas
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
                        <div class="form-group row mb-1"><label class="col-form-label col-sm-4" for=""> ITSM</label>
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
                        @if (!$data->bukanTI)
                        <div class="form-group row mb-1"><label class="col-form-label col-sm-4" for=""> Tematik</label>
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
                        @endif
                        <div class="form-group row mb-1"><label class="col-form-label col-sm-4" for=""> SLA</label>
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

        <div class="table-responsive">
            <table class="table table-striped table-lightfont" id="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Tiket</th>
                        <th>Tanggal</th>
                        <th>ITSM</th>
                        <th>User</th>
                        <th>Unit Org</th>
                        <th>Deskripsi</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Operator</th>
                        <th>Solver</th>
                        <th>Updated At</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

    </div>
</div>
@include('layanan.layanan.modal.group_solver')
@include('layanan.layanan.modal.solver')
@endsection

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
@endsection
@section('page-style')
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">
@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection

@push('scripts')
<script>
    function toogleMore(id) {
        const all = document.getElementsByClassName("all"+id);
        if (all[0].style.display === "none") {
            all[0].style.display = "inline";
            $('.less'+id).hide()
            $('#more'+id).hide()
            // btnText.innerHTML = "Read more";
            // moreText[0].style.display = "none";
        }
    }
    $(function() {
        $(document).on("click",'.dropdown-toggle',function () {
            $(this).parent().toggleClass('show')
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
                { data: 'Id', name: 'Id' },
                {
                    data: 'NoTicket',
                    name: 'NoTicket' ,
                    width:'5%',
                    orderable:false
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
                    orderable:false
                },
                {
                    data: 'Nip',
                    render: function(data, type, row, meta) {
                        return `${row.Nip??'-'} <br> ${row.NmPeg??'-'}`
                    }  ,
                    width:'10%',
                    orderable:false
                },
                {
                    data: 'KdUnitOrg',
                    render: function(data, type, row, meta) {
                        return `${row.NmUnitOrg??'-'} <br> ${row.NmUnitOrgInduk!=row.NmUnitOrg ? row.NmUnitOrgInduk:''}`
                    }  ,
                    width:'12%',
                    orderable:false
                },
                {
                    data: 'PermintaanLayanan',
                    render: function(data, type, row, meta) {
                        less = `<span class="less${row.Id}"> ${row.PermintaanLayanan2.length < 200 ? row.PermintaanLayanan2 :getStringWithOutTags(row.PermintaanLayanan2)}</span> <br>`
                        button = `<span class="btn btn-primary btn-sm" id="more${row.Id}" onclick="toogleMore('${row.Id}')" id="myBtn"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="8 12 12 16 16 12"></polyline><line x1="12" y1="8" x2="12" y2="16"></line></svg></span>
                        `
                        all = `<span class="all${row.Id}" style="display:none"> ${data}</span>`
                        return `${less} ${all} ${less.length > 208 ? button : ''}  `;
                    }  ,
                    width:'35%',
                    orderable:false,
                },
                {
                    data: 'PrioritasLayanan',
                    searchable:false,
                    width:'5%',
                    orderable:false,
                },
                {
                    data: 'StatusLayanan',
                    searchable:false,
                    width:'5%',
                    orderable:false,
                },
                {
                    data: 'operator_open',
                    render: function(data, type, row, meta) {
                        return `${row.NipOperatorOpen??'-'} <br> ${row.operator_open?.NmPeg??'-'}`
                    }  ,
                    searchable:false,
                    orderable:false,
                    width:'10%',
                },
                {
                    data: 'Id',
                    render: function(data, type, row, meta) {
                        return `<span style="color:blue">${row.AllGroupSolver??'-'} </span><br><span style="color:green"> ${row.AllSolver??'-'}</span>`
                    }  ,
                    searchable:false,
                    orderable:false,
                    width:'10%',
                },
                {
                    data: 'UpdatedAt',
                    searchable:false,
                    orderable:false,
                    width:'5%',
                },
                {
                    data: 'NmPeg',
                    visible:false,
                    orderable:false,
                },
                {
                    data: 'NoTicketRandom',
                    visible:false,
                    orderable:false,
                },
                {
                    data: 'NmUnitOrg',
                    visible:false,
                    orderable:false,
                },
                {
                    data: 'NmUnitOrgInduk',
                    visible:false,
                    orderable:false
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
        table.on('draw.dt', function () {
            var info = table.page.info();
            table.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + info.start;
            });
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
                <div class="input-group"> <input class="form-control form-filterss mb-1" placeholder="Search" id="mySearchText"
                        style="margin-right: 0px;">
                    <div class="input-group-append mb-1">
                        <button class="btn btn-outline-secondary" type="button" id="mySearchButton" style="margin-left: 20px;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></button> <a style="margin-left: 10px;" class="btn btn-sm btn-success" id="exportBtn">
                            <img src="/svg/excel.PNG" width="23px;"></a> </div>
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
    function getStringWithOutTags(str){
        while(true){
            var start = str.indexOf("<");
            if(start < 0){
                break;
            }
            var end = str.indexOf(">", start);
            var temp = str.substring(start, end+1);
            str = str.replace(temp, "");
        }
        var output = (str.length > 200) ? str.substring(0,200) : str;
        return output;
    }
</script>
@include('layanan.layanan._script-eskalasi')
@include('core._script-delete')
@endpush

@push('css')
<style>
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
<style>
    .table-responsive {
        overflow-y: hidden;
    }

    .table td {
        vertical-align: top !important;
        font-size: 14px;
    }
</style>
@endpush
