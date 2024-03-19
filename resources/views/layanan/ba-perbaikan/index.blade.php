@extends('core.layouts.master')

@section('content')
<div class="element-wrapper">
    <div class="element-box">

        <h5 class="form-header">Berita Acara Perbaikan Aset
        </h5>
        <form action="" method="get" id="form">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group row"><label class="col-form-label col-sm-4" for=""> Status Layanan</label>
                        <div class="col-sm-8">
                            <select class="form-control select2 form-filters" multiple="true" id="statusLayanan"
                                name="statusLayanan[]">
                                @foreach ($data->statusLayanan as $item)
                                <option @if(is_array(request()->statusLayanan) &&
                                    in_array($item->Id,request()->statusLayanan) || $item->Id==request()->statusLayanan)
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
                            <input class="form-control form-filters" placeholder="Tanggal End" value="" name="tglEnd"
                                id="tglEnd">
                        </div>
                    </div>
                    <div class="form-buttons-w"><button class="btn btn-primary apply"> Apply</button><a
                            href="{{ route('ba-perbaikan.index') }}" class="btn btn-danger clear"> Clear</a></div>
                </div>
                <div class="col-sm-4">

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
                    <div class="form-group row"><label class="col-form-label col-sm-4" for="">  SLA</label>
                        <div class="col-sm-8">
                            <select class="form-control select2 form-filters" multiple="true" id="sla"
                                name="sla[]">
                                <option @if(is_array(request()->sla) && in_array('melewati',request()->sla) ||
                                    'melewati'==request()->sla) selected @endif value="melewati">Melewati</option>
                                <option @if(is_array(request()->sla) && in_array('tidak_melewati',request()->sla) ||
                                    'tidak_melewati'==request()->sla) selected @endif value="tidak_melewati">Tidak Melewati</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <br>
            <div class="table-responsive">
                <table class="table table-striped table-lightfont" id="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Ticket</th>
                            <th>Status Layanan</th>
                            <th>Ruang</th>
                            <th>Perbaikan</th>
                            <th>Pengembalian</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
    </div>
</div>
@include('layanan.layanan._form-ba-perbaikan')
@include('core.modals.pegawai3')
@include('core.modals.pegawai4')
@include('core.modals.pegawai5')
@endsection
@push('scripts')
<script>
    $(function() {
        @if(request()->tglStart)
            $('#tglStart').datepicker({ dateFormat: 'dd M yy'}).datepicker("setDate", new Date('{{ request()->tglStart }}'));
            $('#tglEnd').datepicker({ dateFormat: 'dd M yy'}).datepicker("setDate", new Date('{{ request()->tglEnd }}'));
        @else
            $('#tglStart').datepicker({ dateFormat: 'dd M yy'}).datepicker("setDate", new Date('{{ date('Y') }}-01-01'));
            $('#tglEnd').datepicker({ dateFormat: 'dd M yy'}).datepicker("setDate", new Date());
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
                url : "{!! route('ba-perbaikan.datatables') !!}?"+$('#form').serialize(),
            },
            columns: [
                {
                    data: 'Id',
                    className: "text-center" ,
                    width:'5%',
                },
                {
                    data: 'Ticket',
                },
                { data: 'StatusLayanan', name: 'StatusLayanan'},
                {
                    data: 'Ruang',
                    name: 'Ruang'
                },
                // { data: 'Id', name: 'Id' },
                {
                    data: 'Perbaikan',
                    name: 'Perbaikan' ,
                },
                {
                    data: 'Pengembalian',
                    name: 'Pengembalian' ,
                },
                { data: 'layanan.NoTicket', name: 'layanan.NoTicket' , searchable:true ,visible:false},
                { data: 'layanan.NoTicketRandom', name: 'layanan.NoTicketRandom' , searchable:true,visible:false },
                { data: 'layanan.Nip', name: 'layanan.Nip' , searchable:true ,visible:false},
                { data: 'layanan.NmPeg', name: 'layanan.NmPeg' , searchable:true,visible:false },
            ],
            rowCallback: function( row, data, index ) {
                $('td', row).css('vertical-align', 'top');
            }
        });
        table.on('draw.dt', function () {
            var info = table.page.info();
            table.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + info.start;
            });
        });
        $('#table_filter').html(`
            <form class="form-inline" style="float:right">
                <div class="input-group"> <input class="form-control form-filterss" placeholder="Search" id="mySearchText"
                        style="margin-right: 0px;">
                    <div class="input-group-append"> <button class="btn btn-outline-secondary" type="button" id="mySearchButton"><i
                                class="icon-feather-search"></i></button> <a class="btn btn-success" id="exportBtn"><img
                                src="/svg/excel.PNG" width="23px;"></a> </div>
                </div>
            </form>`)
        $('#mySearchButton').on( 'keyup click', function () {
            table.search($('#mySearchText').val()).draw();
        } );

        $('#mySearchText').on( 'keyup', function (e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                table.search($('#mySearchText').val()).draw();
            }
        } );

        $('#exportBtn').on('click', function () {
            window.open("{{ route('ba-perbaikan.export') }}?"+$('#form').serialize())
        })
    })
</script>

@include('layanan.layanan._script-ba-perbaikan')
@include('core._script-delete')
@endpush