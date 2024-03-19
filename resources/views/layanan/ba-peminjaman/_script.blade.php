<script>

    $(document).on("click",'.pilih-pegawai6',function () {
        $('#formPeminjaman [name="NipPihak2"]').val($(this).data('nip'))
        $('#formPeminjaman [name="KdUnitOrgPihak2"]').val($(this).data('kd_unit_org'))
        $('#formPeminjaman [name="NmUnitOrgPihak2"]').val($(this).data('nm_unit_org'))
        $('#formPeminjaman [name="NmJabatanPihak2"]').val($(this).data('nm_jabatan'))
        $('#formPeminjaman [name="PegawaiPihak2"]').val($(this).data('nip')+'-'+$(this).data('nama'))
        $('#pegawai-modal6').modal('toggle');
        $('#modalBaPeminjaman').modal('toggle');
    });
    $(document).on("click",'.pilih-pegawai7',function () {
        $('#formPeminjaman [name="NmJabatanPejabat"]').val($(this).data('nm_jabatan'))
        $('#formPeminjaman [name="NmPegTtdPejabat"]').val($(this).data('nama'))
        $('#formPeminjaman [name="NipTtdPejabat"]').val($(this).data('nip'))
        $('#formPeminjaman [name="Pejabat"]').val($(this).data('nip')+'-'+$(this).data('nama'))
        $('#pegawai-modal7').modal('toggle');
        $('#modalBaPeminjaman').modal('toggle');
    });
    $(document).on("click",'.pilih-pegawai8',function () {
        $('#formPeminjaman [name="KdUnitOrgPihak1"]').val($(this).data('kd_unit_org'))
        $('#formPeminjaman [name="NmPihak1"]').val($(this).data('nama'))
        $('#formPeminjaman [name="NipPihak1"]').val($(this).data('nip'))
        $('#formPeminjaman [name="PegawaiPihak1"]').val($(this).data('nip')+'-'+$(this).data('nama'))
        $('#pegawai-modal8').modal('toggle');
        $('#modalBaPeminjaman').modal('toggle');
    });
    $(document).on("submit",'#formPeminjaman',function (event) {
        event.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{{ route('ba-peminjaman.storePengembalian') }}',
            type: "POST",
            data: $('#pengembalianForm, #formPeminjaman').serialize(),
            beforeSend: function (data){
                $('#loadingAnimation').addClass('lds-dual-ring');
            },
            success: function (data) {
                toastr.info(data.message);
                window.location.replace('{{ route('ba-peminjaman.index') }}')
                $('#loadingAnimation').removeClass('lds-dual-ring');
                $('#modalBaPeminjaman').modal('toggle');

            },
            error : function () {
                alert('Terjadi kesalahan, silakan reload');
            }
        })
    });
</script>