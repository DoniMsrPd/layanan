@extends('layouts/contentLayoutMaster')

@section('title', 'Edit User')


@section('button-right')
<a class="btn btn-primary" href="/user">Kembali</a>
@endsection

@section('content')

<div class="card">
    <div class="card-body">
        {{ Form::model($user, ['route' => ['user.update', $user->id], 'method' => 'PUT']) }}

        <div class="row">
            <div class="form-group col-sm-6">
                {{ Form::label('nip', 'NIP') }}
                {{ Form::text('nip', null, ['class' => 'form-control', 'readonly' => 'readonly']) }}
                <span id="emailHelp" class="form-text text-muted">{{ $user->pegawai->NmPeg . ' / ' . $user->pegawai->NmUnitOrg }}.</span>
            </div>
        </div>

        <h4>Roles</h4>
        <table class="table table-borderless">
            @foreach ($groupedRoles as $group)
            <tr>
                @foreach ($group as $role)
                <td>
                    <label class="custom-control custom-checkbox">
                        {{Form::checkbox('roles[]', $role->id, $user->roles, ['class' => 'custom-control-input']) }}
                        <span class="custom-control-label">{{ $role->name }}</span>
                    </label>
                </td>
                @endforeach
            </tr>
            @endforeach
        </table>

        <hr />

        <h4>Permissions</h4>

        <table class="table table-borderless">
            @foreach ($groupedPermissions as $group)
            <tr>
                @foreach ($group as $permission)
                <td>
                    <label class="custom-control custom-checkbox">
                        {{Form::checkbox('permissions[]', $permission->id, $user->permissions, ['class' => 'custom-control-input']) }}
                        <span class="custom-control-label">{{ $permission->name }}</span>
                    </label>
                </td>
                @endforeach
            </tr>
            @endforeach
        </table>

        {{ Form::submit('Update', ['class' => 'btn btn-lg btn-primary']) }}

        {{ Form::close() }}
    </div>
</div>

@endsection