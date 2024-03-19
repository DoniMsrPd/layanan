@extends('layouts/contentLayoutMaster')

{{-- @section('title', 'Create Permission') --}}

@section('content-button')
<a class="btn btn-flat-primary btn-effect-primary" href="/permission">
    <span><i class="mdi mdi-arrow-left"></i> Kembali</span>
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-body">
                {{ Form::open(array('route' => 'core.permission.store')) }}

                <div class="form-group">
                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('name', '', array('class' => 'form-control')) }}
                </div>
                <br>

                @if(!$roles->isEmpty())

                <h4>Assign Permission to Roles</h4>

                <table class="table table-borderless">
                    @foreach ($groupedRoles as $group)
                    <tr>
                        @foreach ($group as $role)
                        <td>
                            <label class="custom-control custom-checkbox">
                                {{ Form::checkbox('roles[]',  $role->id, null, ['class' => 'custom-control-input']) }}
                                <span class="custom-control-label">{{ $role->name }}</span>
                            </label>
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </table>
                @endif

                {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

@endsection