@extends('layouts/contentLayoutMaster')
@section('title', 'Permission')

@section('button-right')
<a href="/core/permission/create" class="btn btn-primary"> <b>+</b> Tambah Permission</a>
@endsection

@section('content')

<div class="card">
    <div class="card-body">
        <p class="card-text float-left relative top-8">
            Data <code class="highlighter-rouge">Permission</code>
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
            <table class="table">
                <thead>
                    <tr>
                        <th>Permissions</th>
                        <th style="width:10em"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $permission)
                    <tr>
                        <td>{{ $permission->name }}</td>
                        <td class="text-right">
                            <a href="{{ route('core.permission.edit', $permission->id) }}" class="text-warning" title="Edit"><i data-feather='edit'></i></a>

                            <form action="/core/permission.destroy/{{ $permission->id }}" style="display: inline-block;">
                                <button type="submit" class="btn-delete text-danger" style="border: none;background: none;" title="Hapus"><i data-feather='trash-2'></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            <div class="row">
                <div class="col-10">
                    {{ $permissions->appends($_GET)->links() }}
                </div>
                <div class="col-2 text-right">
                    <b>{{ $permissions->count() }}</b> dari <b>{{ $permissions->total() }}</b> Baris
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('system.layouts._delete')
@endpush