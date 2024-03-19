@extends('layouts/contentLayoutMaster')

@section('title', 'Users')

@section('content-button')
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <p class="card-text float-left relative top-8">
            Data <code class="highlighter-rouge">Pengguna</code>
        </p>

        <form method="GET" id="form-filter">
            <div class="form-group row mb-2">
                <label for="staticEmail" class="col-sm-2 col-form-label">Cari Berdasar Role</label>
                <div class="col-sm-10">
                    <select name="role" id="role" class="select2">
                        <option value=""></option>
                        @foreach(getRole() as $r)
                        <option value="{{ $r->id }}" {{ (isset(request()->role) && request()->role == $r->id) ? 'selected' : '' }}>{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row mb-2">
                <label for="inputPassword" class="col-sm-2 col-form-label">Nama / NIP</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="q" name="q" value="{{ request()->q ?? '' }}" placeholder="Nama / NIP">
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-top">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>NIP</th>
                        <th>Email</th>
                        <th>User Roles</th>
                        <th>Operations</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td> <span class="avatar"><img class="round img-fluid" src="http://foto.bpk.go.id/{{ $user->nip }}/sm.jpg" alt="Avatar" height="40" width="40"></span>{{ $user->name }}</td>
                        <td>{{ $user->nip }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{!! $user->roles->pluck('name')->implode('<br> ') !!}</td>
                        <td>
                            <a href="{{ route('user.show', $user) }}" class="text-secondary"><i class="mdi mdi-eye"></i> </a>
                            <a href="{{ route('user.edit', $user) }}" class="text-warning" title="Edit"><i data-feather='edit'></i> </a>

                            <form action="/user/destroy/{{ $user->id }}" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete text-danger delete" style="border: none;background: none;" title="Hapus"><i data-feather='trash-2'></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            <div class="row">
                <div class="col-10">
                    {{ $users->appends($_GET)->links() }}
                </div>
                <div class="col-2 text-right">
                    <b>{{ $users->count() }}</b> dari <b>{{ $users->total() }}</b> Baris
                </div>
            </div>
        </div>

    </div>
</div>
{{-- <div style="float:left">
    {{ Form::open(['method' => 'GET', 'class' => 'form-inline']) }}
<div class="form-group">
    <input class="form-control" type="text" id="q" name="q" value="{{ request()->get('q') }}" placeholder="Nama/NIP">
</div>
<div class="form-group">
    <input type="text" style="width: 400px !important;" class="form-control" name="role" id="role" value="{{ request()->get('role') }}" placeholder="Cari Berdasarkan Role">
</div>
<button type="submit" class="btn btn-primary mb-2">Cari</button>
{{ Form::close() }}
</div> --}}



@endsection

@push('scripts')
<script>
    $(function() {
        src = "{{ route('role.autocomplete') }}";
        $("#role").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: src,
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            }
        });
    })
    $(".select2").select2({
        width: "400px",
        allowClear: true,
        placeholder: 'Pilih peran'
    });

    $('#q, #role').on('keyup', function(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            $(this).closest('form').submit()
        }
    })

    $("#role").on('change', function() {
        $(this).closest('form').submit()
    })
</script>

@include('system.layouts._delete')

@endpush