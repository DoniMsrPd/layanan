@foreach ($data as $key => $solver)
<tr>
    @if (isMobile())
    <td class="solver" style="display: none">{{ $solver->Nip }}</td>
    <td>
        {{ $solver->pegawai->Nip }}  {{ $solver->pegawai->NmPeg }} <br>
        <textarea name="catatan" rows="2" class="form-control catatanSolver" data-id='{{ $solver->Id }}'>{{ $solver->Catatan }}</textarea>
        <br>
        @if(!$solver->layanan->DeletedAt && (request()->user()->can('layanan.eskalasi.all') || auth()->user()->hasRole('Pejabat Struktural')))
        <a style='padding:3px 3px;margin:0px' class='btn btn-danger btn-sm deleteData'
            data-id='{{ $solver->Id }}' data-url="/eskalasi/solver/{{ $solver->Id }}" href='javascript:void(0)'
            data-title="{{ $solver->pegawai->NmPeg }}" title='Hapus'>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
        </a>
        @endif
    </td>

    @else
    <td class="text-center">{{ $key+1 }}</td>
    <td class="solver" style="display: none">{{ $solver->Nip }}</td>
    <td>{{ $solver->pegawai->Nip }}</td>
    <td>{{ $solver->pegawai->NmPeg }}</td>
    <td><textarea name="catatan" rows="2" class="form-control catatanSolver" data-id='{{ $solver->Id }}'>{{ $solver->Catatan }}</textarea></td>
    <td class="text-center">
        @if(!$solver->layanan->DeletedAt && (request()->user()->can('layanan.eskalasi.all') || auth()->user()->hasRole('Pejabat Struktural')))
        <a style='padding:3px 3px;margin:0px' class='btn btn-danger btn-sm deleteData'
            data-id='{{ $solver->Id }}' data-url="/eskalasi/solver/{{ $solver->Id }}" href='javascript:void(0)'
            data-title="{{ $solver->pegawai->NmPeg }}" title='Hapus'>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
        </a>
        @endif
    </td>
    @endif
</tr>

@endforeach
