<table class="table table-borderless">
    @foreach ($groupedPermissions as $key => $group)
        @foreach ($group->chunk(2) as $permissions)
            <tr class="{{ $loop->parent->iteration % 2 == 0? 'even': 'odd' }} {{ $key }}">
                @foreach($permissions as $permission)
                <td>
                    <label class="custom-control custom-checkbox">
                        {{Form::checkbox('permissionHas[]',  $permission->id, ($type == 'has') ? $role->permissions : null, ['class' => 'custom-control-input uncheck']) }}
                        <span class="custom-control-label">{{ $permission->name }}</span>
                    </label>
                </td>
                @endforeach
                @if (count($permissions) < 2)
                    <td></td>
                @endif
            </tr>
        @endforeach
        <tr class="tr-sep">
            <td></td>
            <td></td>
        </tr>
    @endforeach
</table>
