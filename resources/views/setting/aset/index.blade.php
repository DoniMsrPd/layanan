@extends('core.layouts.master')

@section('content')
<div class="element-wrapper">
    <div class="element-box">

        <h5 class="form-header">ASET TI
            @can('aset.create')
                <a class="btn btn-sm btn-success" style=" float: right;" href="{{ route('setting.aset.create') }}">Tambah</a>
            @endcan
        </h5>
            <div class="table-responsive">
                <table class="table table-striped table-lightfont" id="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No IKN</th>
                            <th>Jenis</th>
                            <th>Merk</th>
                            <th>Type</th>
                            <th>Spesifikasi</th>
                            <th>Masa Garansi</th>
                            <th>Tahun</th>
                            <th>Harga Perolehan</th>
                            <th>Pengguna</th>
                            <th>Aksi</th>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(function() {
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
                url : "{!! route('setting.aset.datatables') !!}",
            },
            columns: [
                {
                    data: 'Id',
                    className: "text-center" ,
                    width:'5%',
                    searchable:false
                },
                // { data: 'Id', name: 'Id' },
                {
                    data: 'NoIkn1',
                    name: 'NoIkn1' ,
                    render: function(data, type, row, meta) {
                        return `${row.NoIkn1} <br> ${row.NoIkn2}`
                    }
                },
                {
                    data: 'JenisAset',
                },
                {
                    data: 'TypeAset',
                },
                {
                    data: 'Nama',
                },
                {
                    data: 'Nama',
                    render: function(data, type, row, meta) {
                        return `${row.Processor??'-'} <br> ${row.Hdd??'-'} <br> ${row.Memory??'-'}`
                    }
                },
                {
                    data: 'Nama',
                    render: function(data, type, row, meta) {
                        if((row.MasaGaransiTahun)&&(row.MasaGaransiBulan)){
                            var date = new Date(row.MasaGaransiTahun, row.MasaGaransiBulan-1, 1);  // 2009-11-10
                            var month = date.toLocaleString('id-ID', { month: 'short' });
                            var garansi  =  `${month} ${row.MasaGaransiTahun}`
                        } else {
                            garansi = '-'
                        }
                        return garansi
                    }
                },
                {
                    data: 'Tahun',
                },
                {
                    data: 'HargaPerolehan',
                    render: function (data) {
                        return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");;
                    },
                    className: "text-right" ,
                },
                {
                    data: 'NipPengguna',
                    render: function(data, type, row, meta) {
                        return `${row.NipPengguna??'-'} <br> ${row.pengguna?.NmPeg??'-'}`
                    }
                },
                { data: 'action', name: 'action' , searchable:false, orderable:false, className: "text-center" ,width:'15%'},
                {
                    data: 'pengguna.NmPeg',
                    render: function(data, type, row, meta) {
                        return `${row.pengguna?.NmPeg??'-'}`
                    }, searchable:true ,visible:false
                },
            ]
        });
        table.on('draw.dt', function () {
            var info = table.page.info();
            table.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + info.start;
            });
        });
    })
</script>

@include('core._script-delete')
@endpush