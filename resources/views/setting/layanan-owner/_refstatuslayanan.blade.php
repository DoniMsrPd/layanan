@extends('layouts/contentLayoutMaster')
@section('content')
<div class="card">
    <h5 class="card-header">
        Status Layanan {{ $unitOrg->NmUnitOrgOwnerLayanan }}

        <a class="btn btn-sm btn-primary" style=" float: right;"
            href="{{ route('setting.layanan-owner.index') }}">
            < Kembali
        </a>
        {{-- @can('master-layanan-owner.create')
        @endcan --}}
    </h5>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-lightfont" id="table">
                <thead>
                    <tr>

                        <th width="5%">No</th>
                        <th >Nama</th>
                        <th>Kd Unit Org Owner</th>
                        {{-- <th width="10%">Aksi</th> --}}
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
            paging: false,
            ajax: {
                method: 'POST',
                url : "/setting/ref-status-layanan/datatable/{{ $unitOrg->KdUnitOrgOwnerLayanan }}",
            },
            columns: [
                {
                    data: 'No',
                    className: "text-center" ,
                },
                {
                    data: 'Nama',
                },
                {
                    data: 'KdUnitOrgOwnerLayanan',
                    className: "text-left" ,
                },
            ],
            order: [[0, 'asc']]
        });
    })
</script>

@include('core._script-delete')
@endpush

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/extensions/moment.min.js')) }}"></script>
@endsection
