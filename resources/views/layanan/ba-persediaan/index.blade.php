@extends('core.layouts.master')

@section('content')
<div class="element-wrapper">
    <div class="element-box">

        <h5 class="form-header">Berita Acara Persediaan
        </h5>

        <form action="" method="get" id="form">
            <div class="row">
                <div class="col-sm-4">
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
                            href="{{ route('ba-persediaan.index') }}" class="btn btn-danger clear"> Clear</a></div>
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
                            <th>No BA</th>
                            <th>Tgl BA</th>
                            <th>Pihak 1</th>
                            <th>Pihak 2</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
    </div>
</div>
@include('layanan.layanan._form-ba-peminjaman')
@include('core.modals.pegawai6')
@include('core.modals.pegawai7')
@include('core.modals.pegawai8')
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
        $(document).on("change",'#isPihakLuar',function () {
            if($(this).is(":checked")){
                $('.pihak1luar').show()
                $('#pihak1dalam').hide()
            } else {
                $('#pihak1dalam').show()
                $('.pihak1luar').hide()
            }
        });
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
                url : "{!! route('ba-persediaan.datatables') !!}?"+$('#form').serialize(),
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
                // { data: 'Id', name: 'Id' },
                {
                    data: 'NoBA',
                    name: 'NoBA' ,
                    render: function(data, type, row, meta) {
                        return `<span>${row.NoBA??"-"} </span> <br> <span style="color:green">${row.NoBAPengembalian??'-'}</span>`
                    }
                },
                {
                    data: 'TglBA',
                    name: 'TglBA' ,
                    render: function(data, type, row, meta) {
                        return `<span>${row.TglBA??"-"} </span> `
                    }
                },
                {
                    data: 'TglBA',
                    render: function(data, type, row, meta) {
                        return `<span>${row.NipPihak1 ?? row.NipPihak1Luar} <br> ${row.NmPihak1 ?? row.NmPihak1Luar} </span>`
                    }
                },
                {
                    data: 'NipPihak2',
                    render: function(data, type, row, meta) {
                        return `<span>${row.NipPihak2} <br> ${row.pihak2?.NmPeg} </span>`
                    }
                },
                { data: 'action', name: 'action' , searchable:false, orderable:false, className: "text-center" ,width:'15%'},
                { data: 'NipPihak1', name: 'NipPihak1' , searchable:true,visible:false },
                { data: 'NmPihak1', name: 'NmPihak1' , searchable:true,visible:false },
                { data: 'NipPihak1Luar', name: 'NipPihak1Luar' , searchable:true,visible:false },
                { data: 'NmPihak1Luar', name: 'NmPihak1Luar' , searchable:true,visible:false },
            ]
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
            window.open("{{ route('ba-persediaan.export') }}?"+$('#form').serialize())
        })
    })
</script>

@include('layanan.layanan._script-ba-peminjaman')
@include('core._script-delete')
@endpush