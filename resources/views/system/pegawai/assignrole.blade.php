@push('scripts')
<script>
    $( document ).ready(function() {
    console.log( "ready!" );
    $('#sideMenu').addClass('compact-side-menu');
});
</script>
@endpush

{{--  --}}

@extends('layouts/contentLayoutMaster')

{{-- @section('title', 'Users') --}}

@section('content-button')
@endsection

@section('content')
<div class="card">
    <div class="card-body">

        <form method="POST" action="{{ url('/core/userrolestore') }}">
            <div class="form-group col-4">
                <label for="recipient-name" class="form-control-label">Nama User:</label>
                <input type="text" class="form-control" name="nama" readonly="readonly" value="{{ $user->name }}">
                <input type="hidden" class="form-control" name="userid" readonly="readonly" value="{{ $user->id }}">
            </div>
            <h5>Roles</h5>
            @csrf
            <div class="table-responsive">
                <table class="table table-borderless">
                    @foreach ($groupedRoles as $group)
                    <tr>
                        @foreach ($group as $role)
                        @php
                        $checked = ($user->hasRole($role->name)) ? 'checked' : '' ;
                        $isHide = '';
                        @endphp

                        <td>
                            <label class="custom-control custom-checkbox">
                                <input class="custom-control-input" name="roles[]" {{ $checked }} type="checkbox"
                                    value="{{ $role->id }}">
                                <span class="custom-control-label">{{ $role->name }}</span>
                            </label>
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </table>
            </div>
            <div class="form-buttons-w"><button class="btn btn-primary" type="submit"> Submit</button>
            </div>
        </form>

    </div>
</div>
@endsection