@foreach ($data as $key => $groupSolver)
<tr>
    <td>{{ $key+1 }}</td>
    <td class="groupSolver" style="display: none">{{ $groupSolver->MstGroupSolverId
        }}</td>
    <td>{{ optional($groupSolver->mstGroupSolver)->Kode }}</td>
    <td class="text-center">
        @if(!$groupSolver->layanan->DeletedAt && (request()->user()->can('layanan.eskalasi.all') || auth()->user()->hasRole('Pejabat Struktural')))
        <a style='padding:3px 3px;margin:0px' class='btn btn-danger btn-sm deleteData' data-group-solver="1" data-id='{{ $groupSolver->Id }}'
            data-url="/eskalasi/group-solver/{{ $groupSolver->Id }}"
            data-title="{{ optional($groupSolver->mstGroupSolver)->Nama }}" href='javascript:void(0)' title='Hapus'>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
            @endif
        </a>
    </td>
</tr>
@endforeach
<script>
    if({{ count($data) }} > 0){
        $('.lookup-solver').show()
    } else {
        $('.lookup-solver').hide()
    }
</script>
