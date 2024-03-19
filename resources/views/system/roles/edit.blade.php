@extends('layouts/contentLayoutMaster')

@section('content')



<style>
    .custom-control {
        position: relative;
        display: inline-block;
        min-height: 1.5rem;
        padding-left: 1.5rem;
    }

    .custom-control {
        position: relative;
        display: inline-block;
        min-height: 1.5rem;
        padding-left: 1.5rem;
    }
</style>
<div class="card">
    <!-- <h5 class="form-header">Assign Role</h5> -->

    <div class="card-body">
    <form method="POST" action="{{ route('core.role.update', $role->id) }}">
        <input type="hidden" name="id" value="{{ $role->id }}">
        <input name="_method" type="hidden" value="PUT">
        <div class="form-group col-4">
            <label for="recipient-name" class="form-control-label">Nama Role :</label>
            <input type="text" class="form-control" name="name" value="{{$role->name}}">
        </div>
        <h5>Assign Permissions</h5>
        @csrf
        <div class="table-responsive">
            <table class="table">

                @for ($i = 0; $i < count($menu) ; $i++) <tr>
                    <td>
                        @foreach ($permissions as $rk)
                        @php
                        $dots = explode('.', $rk->name);
                        $checked = ($role->hasPermissionTo($rk->name)) ? 'checked' : '';
                        @endphp
                        @if (count($dots)>1)
                        @if ($menu[$i]==$dots[0])
                        <label class="custom-control custom-checkbox" style="width: 23%;margin:0">
                            <input class="custom-control-input" name="permissions[]" {{$checked}} type="checkbox"
                                value="{{ $rk->id}}">
                            <span class="custom-control-label">{{ $rk->name }}</span>
                        </label>
                        @endif
                        @else
                        @if ($menu[$i]==$rk->name)
                        <label class="custom-control custom-checkbox" style="width: 23%;margin:0">
                            <input class="custom-control-input" name="permissions[]" {{$checked}} type="checkbox"
                                value="{{ $rk->id}}">
                            <span class="custom-control-label">{{ $rk->name }}</span>
                        </label>
                        @endif

                        @endif
                        @endforeach
                    </td>
                    </tr>
                    @endfor
            </table>
        </div>
        <div class="form-buttons-w"><button class="btn btn-primary" type="submit"> Submit</button>
        </div>
    </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $( document ).ready(function() {
    console.log( "ready!" );
    $('#sideMenu').addClass('compact-side-menu');
});
</script>
@endpush