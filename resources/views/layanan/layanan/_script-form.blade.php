<script>
    var kategori = [],deleteKategori=[];
    $( document ).ready(function() {
        @if(($data->layanan)&&$data->layanan->Nip!=$data->layanan->NipLayanan)
            $('.layanan').show()
        @endif
        $(document).on("click",'.pilih-pegawai',function () {
            $('#Nip').val($(this).data('nip'))
            $('#NomorKontak').val($(this).data('no_hp'))
            $('#KdUnitOrg').val($(this).data('kd_unit_org'))
            $('#NmUnitOrg').val($(this).data('nm_unit_org'))
            $('#NmUnitOrgInduk').val($(this).data('nm_unit_org_induk'))
            $('#NmPeg').val($(this).data('nama'))
            $('#NmUnitOrgPelapor').html($(this).data('nm_unit_org'))
            $('#NmUnitOrgIndukPelapor').html('')
            if($(this).data('nm_unit_org')!=$(this).data('nm_unit_org_induk'))
                $('#NmUnitOrgIndukPelapor').html($(this).data('nm_unit_org_induk'))
            $('#NmPegawai').val($(this).data('nip')+'-'+$(this).data('nama'))
            $('#NmJabatan').html($(this).data('nm_jabatan'))
            $('#pegawai-modal').modal('toggle');
        });
        $(document).on("click",'.pilih-pegawai2',function () {
            $('#NipLayanan').val($(this).data('nip'))
            $('#KdUnitOrgLayanan').val($(this).data('kd_unit_org'))
            $('#NmUnitOrgLayanan').html($(this).data('nm_unit_org'))
            $('#NmUnitOrgIndukLayanan').html('')
            if($(this).data('nm_unit_org')!=$(this).data('nm_unit_org_induk'))
                $('#NmUnitOrgIndukLayanan').html($(this).data('nm_unit_org_induk'))
            $('#NmPegLayanan').html($(this).data('nip')+'-'+$(this).data('nama'))
            $('#NmJabatanLayanan').html($(this).data('nm_jabatan'))
            $('#pegawai-modal2').modal('toggle');
        });
        $(document).on("change",'#pegawaiLain',function () {
            if($(this).is(":checked")){
                $('.layanan').show()
            } else {
                $('.layanan').hide()
            }
        });
        $(document).on("click",'.pilih-kategori',function () {
            let id = $(this).data('id')
            $("#tableKategori tbody tr.nulldata").remove()
            countKategori = $('#tableKategori tbody tr').length;
            countKategori = countKategori+1
            @if (isMobile())
            tableKategori = "<tr id='row"+countKategori+"'>\
                                <input type='hidden' name='Kategori[]' value='" + id + "'>\
                                <td class='kategori' style='display:none'>" + id + "</td>\
                                <td >" + $(this).data('nama') + '<br>' + $(this).data('keterangan') + "<br><a style='padding:3px 3px;margin:0px' class= 'btn btn-danger btn-sm remove_kategori' data-id='" + id + `' href='javascript:void(0)' title='Hapus'><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a></td>\
                                </tr>`;
            @else
            tableKategori = "<tr id='row"+countKategori+"'>\
                                <input type='hidden' name='Kategori[]' value='" + id + "'>\
                                <td >" + countKategori + "</td>\
                                <td class='kategori' style='display:none'>" + id + "</td>\
                                </td><td >" + $(this).data('nama') + "</td>\
                                <td >" + $(this).data('keterangan') + "</td>\
                                <td class='text-center'><a style='padding:3px 3px;margin:0px' class= 'btn btn-danger btn-sm remove_kategori' data-id='" + id + `' href='javascript:void(0)' title='Hapus'><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a></td></tr>`;
            @endif
            kategori.push(id)
            $("#tableKategori tbody").append(tableKategori)
            $(this).closest('tr').hide('slow')
        });

        $(document).on("click",'.lookup-kategori',function () {
            $('#kategori-dt').DataTable().ajax.url("{!! route('setting.kategori.datatables') !!}?KdUnitOrgOwnerLayanan={{ request()->KdUnitOrgOwnerLayanan??$data->layanan->KdUnitOrgOwnerLayanan ?? null }}").load();
            $('#kategori-modal').modal('toggle');
            kategori = [];
            $('#tableKategori tbody tr').each(function() {
                kategori.push($(this).find(".kategori").html())
                kategori.join(',')
            });
        });
        $(document).on('click', '.remove_kategori', function(){
            $(this).closest('tr').remove();
            var remove = $(this).data('id');
            kategori = $.grep(kategori, function(value) {
                return value != remove;
            });
        });
    });


    $(document).on("submit",'#formAsetTI',function (event) {
        event.preventDefault();
        var LayananId = $('#id').val();
        var _token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '{{ route('setting.aset.store') }}',
            type: "POST",
            data: $('#formAsetTI').serialize(),
            beforeSend: function (data){
                $('#loadingAnimation').addClass('lds-dual-ring');
            },
            success: function (data) {
                toastr.info(data.message);
                $('#modalFormAsetTI').modal('toggle');
                $('#aset-modal').modal('toggle');
                $('#aset-dt').DataTable().ajax.url("{!! route('setting.aset.datatables') !!}").load();
                $('#loadingAnimation').removeClass('lds-dual-ring');
            },
            error : function () {
                alert('Terjadi kesalahan, silakan reload');
            }
        })
    });
</script>