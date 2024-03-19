<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12 col-lg-8">
                <table class='table table-bordered'>
                    <tr><td>Nama</td><td>{{ $user->pegawai->NM_PEG }}</td></tr>
                    <tr><td>NIP</td><td>{{ $user->pegawai->NIP }}</td></tr>
                    <tr><td>Roles</td><td>{{ $user->roles->pluck('name')->implode(', ') }}</td></tr>
                    <tr><td>Permissions</td><td>{{ $user->permissions->pluck('name')->implode(', ') }}</td></tr>
                    <tr><td>Eselon 1</td><td>{{ $user->pegawai->NM_UNIT_ES1 }}</td></tr>
                    <tr><td>Eselon 2</td><td>{{ $user->pegawai->NM_UNIT_ES2 }}</td></tr>
                    <tr><td>Eselon 3</td><td>{{ $user->pegawai->NM_UNIT_ES3 }}</td></tr>
                </table>
            </div>
            <div class="col-sm-12 col-lg-4"><img src="http://foto.bpk.go.id/{{ $user->nip }}/lg.jpg"></div>
        </div>
    </div>
</div>
