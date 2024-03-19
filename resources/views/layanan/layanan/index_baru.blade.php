@extends('layouts/contentLayoutMaster')

@section('title', 'Layanan')
@section('content')
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
</style>
<div class="element-wrapper">
    <div class="element-box">

        <h5 class="form-header">Layanan Baru
            <span class="btn-group" role="group" style=" float: right;">
                @can('layanan.create-tiket')
                <a class="btn btn-sm btn-primary" style=" float: right;"
                    href="{{ route('layanan.create-tiket') }}">Pembuatan Layanan</a>
                @endcan
            </span>
        </h5>
        <br>
        <div class="table-responsive">
            <table class="table table-lightfont" id="table">
                <thead>
                    <tr>
                        <th>No Tiket</th>
                        <th>Tanggal</th>
                        <th>User</th>
                        <th>Unit Org</th>
                        <th>Deskripsi</th>
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
                url : "{!! route('layanan.datatables') !!}?layananBaru=1",
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

        $('#table_filter').html('                    <div class="form-group" style="    width: 50%;float: right;"><div class="input-group" >\
                        <input class="form-control form-filterss" placeholder="Search" id="mySearchText" style="margin-right: 0px;">\
                        <div class="input-group-append">\
                            <button class="btn btn-outline-secondary" type="button" id="mySearchButton"><i\
                                    class="icon-feather-search"></i></button>\
                        </div>\
                    </div></div>')
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
        // $(document).on('click', '.apply', function(){
        //     $('#table').DataTable().ajax.url("{!! route('layanan.datatables') !!}?"+$('.form-filters').serialize()).load();
        // })
        // $(document).on('click', '.clear', function(){
        //     $("#solver").select2("val", "0");
        //     $("#groupSolver").select2("val", "0");
        //     $("#statusLayanan").select2("val", "0");
        //     $('#table').DataTable().ajax.url("{!! route('layanan.datatables') !!}?"+$('.form-filters').serialize()).load();
        // })
    })
</script>

@include('core._script-delete')
@endpush
