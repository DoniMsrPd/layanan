@extends('layouts/contentLayoutMaster')

@section('title', 'Add User')

@push('head')

@endpush

@section('content')

{{ Form::open(['route' => 'core.user.store', 'method' => 'POST']) }}

<div class="row">
    <div class="col-sm-12">
        <label>Pegawai</label>
        <select class="select2" id="pegawai" name="nip"></select>
    </div>
</div>

<h4>Roles</h4>
<table class="table table-borderless">
    @foreach ($groupedRoles as $group)
    <tr>
        @foreach ($group as $role)
        <td>
            <label class="custom-control custom-checkbox">
                {{Form::checkbox('roles[]', $role->id, [], ['class' => 'custom-control-input']) }}
                <span class="custom-control-label">{{ $role->name }}</span>
            </label>
        </td>
        @endforeach
    </tr>
    @endforeach
</table>

{{ Form::submit('Add', ['class' => 'btn btn-lg btn-primary']) }}

{{ Form::close() }}

@endsection

@push('scripts')
<script>
    $('#pegawai').select2({
        width: '100%',
        minimumInputLength: 3,
        ajax: {
            url: '{{ route("api.system.pegawai.index") }}',
            cache: true,
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: $.map(data.data, function(item) {
                        var es_bawah = item.nm_unit_es4 ? item.nm_unit_es4 : item.nm_unit_es3;
                        return {
                            id: item.nip,
                            text: item.nip + ' - ' + item.nm_peg + ' (' + es_bawah + ', ' + item.nm_unit_org_sarpras + ')'
                        }
                    })
                };
            }
        }
    });
</script>
@endpush