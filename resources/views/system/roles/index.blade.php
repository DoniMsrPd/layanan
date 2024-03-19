@extends('layouts/contentLayoutMaster')
@section('title', 'Roles')

@section('button-right')
@can('role.read')
    <a href="/core/role/create" class="btn btn-flat-primary btn-effect-primary waves-effect"> <b>+</b> Tambah Role</a>
@endcan
@endsection


@section('content')

<div class="card">
    <div class="card-body">
        <p class="card-text float-left relative top-8">
            Data <code class="highlighter-rouge">Roles</code>
        </p>

        <form method="GET">
            <div class="form-group row mb-2">
                <label for="inputPassword" class="col-sm-2 col-form-label">Masukkan kata kunci</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="q" name="q" value="{{ request()->q ?? '' }}" placeholder="Masukkan kata kunci">
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="min-width:20em">Role</th>
                        <th>Permissions</th>
                        <th style="width:10em">Operations</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->permissions->pluck('name')->implode(', ') }}</td>
                        <td class="text-right">
                            <a href="{{ route('core.role.show', $role->id) }}" class="text-default" title="Show"><i data-feather='users'></i></a>
                            @can('role.read')
                                <a href="{{ route('core.role.edit', $role->id) }}" class="text-warning" title="Edit"><i data-feather='edit'></i></a>
                                <form action="{{ route('core.role.destroy', $role->id) }}" style="display: inline-block;" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn-delete text-danger" style="border: none;background: none;" title="Hapus"><i data-feather='trash-2'></i></button>
                                </form>
                            @endcan

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            <div class="row">
                <div class="col-10">
                    {{ $roles->appends($_GET)->links() }}
                </div>
                <div class="col-2 text-right">
                    <b>{{ $roles->count() }}</b> dari <b>{{ $roles->total() }}</b> Baris
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
@include('system.layouts._delete')
@endpush
