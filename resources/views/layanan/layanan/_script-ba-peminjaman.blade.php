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
    $(document).on("click",'.formBaPeminjaman',function () {
        id = $(this).data('id')
        jenis = $(this).data('jenis')
        $('#formPeminjaman [name="id"]').val(id)
        url = "{{ url('/ba-peminjaman') }}/" + id
        $('#modal-title-baPeminjaman').val('Generate BA Peminjaman')
        $('#jenisBAPeminjaman').val('pinjam')
        if(jenis=='kembali'){
            url = "{{ url('/ba-peminjaman') }}/" + id+"/showPengembalian"
            $('#modal-title-baPeminjaman').html('Generate BA Pengembalian')
            $('#jenisBAPeminjaman').val('kembali')
        }
        if(jenis=='persediaan'){
            url = "{{ url('/ba-persediaan') }}/" + id
            $('#modal-title-baPeminjaman').html('Generate BA Persediaan')
            $('#jenisBAPeminjaman').val('persediaan')
        }
        $.ajax({
            url: url,
            type: "GET",
            beforeSend: function (data){
                $('#loadingAnimation').addClass('lds-dual-ring');
            },
            success : function (response){
                console.log(response);
                peminjaman = response.data;
                NoBA = peminjaman.NoBA ?? peminjaman.NoBABaru;
                TglBA = peminjaman.TglBA ?? '{{ date('Y-m-d') }}'
                KdUnitOrgPihak2 = peminjaman.KdUnitOrgPihak2 ?? '{{ auth()->user()->pegawai->KdUnitOrg }}'
                NmUnitOrgPihak2 = peminjaman.NmUnitOrgPihak2 ?? '{{ auth()->user()->pegawai->NmUnitOrg }}'
                NmJabatanPihak2 = peminjaman.NmJabatanPihak2 ?? '{{ auth()->user()->pegawai->NmJabatan }}'
                NipPihak2 = peminjaman.NipPihak2 ??'{{ auth()->user()->NIP }} '
                NmPegawaiPihak2 = peminjaman.NipPihak2 ? peminjaman.NipPihak2+' - '+peminjaman.pihak2?.NmPeg :'{{ auth()->user()->NIP }} - {{ auth()->user()->pegawai->NmPeg }}'
                NmUnitOrgPihak2 = peminjaman.NmUnitOrgPihak2 ??'{{ auth()->user()->pegawai->NmUnitOrg }}'
                NmJabatanPihak2 = peminjaman.NmJabatanPihak2 ??'{{ auth()->user()->pegawai->NmJabatan }}'
                $('#formPeminjaman [name="NoBA"]').val(NoBA)
                $('#formPeminjaman [name="TglBA"]').val(TglBA)
                $('#formPeminjaman [name="Ruang"]').val(peminjaman.Ruang)

                // pihak2
                $('#formPeminjaman [name="KdUnitOrgPihak2"]').val(KdUnitOrgPihak2)
                $('#formPeminjaman [name="NmUnitOrgPihak2"]').val(NmUnitOrgPihak2)
                $('#formPeminjaman [name="NmJabatanPihak2"]').val(NmJabatanPihak2)
                $('#formPeminjaman [name="NipPihak2"]').val(NipPihak2)
                $('#formPeminjaman [name="PegawaiPihak2"]').val(NmPegawaiPihak2)
                $('#NmUnitOrgPihak2').html(NmUnitOrgPihak2)
                $('#NmJabatanPihak2').html(NmJabatanPihak2)

                // pejabat
                $('#formPeminjaman [name="NmJabatanPejabat"]').val(peminjaman.NmJabatanPejabat ?? 'Kepala {{ pejabatTTD()->NmUnitOrg }}')
                $('#formPeminjaman [name="NmPegTtdPejabat"]').val(peminjaman.NmPegTtdPejabat ?? '{{ pejabatTTD()->NmPeg }}')
                $('#formPeminjaman [name="NipTtdPejabat"]').val(peminjaman.NipTtdPejabat ??'{{ pejabatTTD()->Nip }}')
                $('#formPeminjaman [name="Pejabat"]').val( peminjaman.NipTtdPejabat ? peminjaman.NipTtdPejabat+' - '+peminjaman.NmPegTtdPejabat :'{{ pejabatTTD()->Nip }} - {{ pejabatTTD()->NmPeg }}')

                    // pihak 1 dalam
                NipPihak1 =jenis=='pinjam'? peminjaman.NipPengembalianAset ?? peminjaman.layanan.pelapor.Nip : peminjaman.NipPihak1 ?? peminjaman.layanan?.pelapor?.Nip

                $('#formPeminjaman [name="KdUnitOrgPihak1"]').val(peminjaman.KdUnitOrgPihak1 ?? peminjaman.layanan?.pelapor?.KdUnitOrg)
                $('#formPeminjaman [name="NmPihak1"]').val(peminjaman.NmPihak1 ?? peminjaman.layanan?.pelapor?.NmPeg)
                $('#formPeminjaman [name="NipPihak1"]').val(NipPihak1)
                $('#formPeminjaman [name="PegawaiPihak1"]').val( peminjaman.NipPihak1 ? peminjaman.NipPihak1+' - '+peminjaman.NmPihak1 :peminjaman.layanan?.pelapor?.Nip+' - '+peminjaman.layanan?.pelapor?.NmPeg)
                // pihak 1 luar
                $('#isPihakLuar').prop('checked', false);
                if(peminjaman.NipPihak1Luar){
                    $('#isPihakLuar').prop('checked', true);
                }
                $('#formPeminjaman [name="NipPihak1Luar"]').val(peminjaman.NipPihak1Luar)
                $('#formPeminjaman [name="NmPihak1Luar"]').val(peminjaman.NmPihak1Luar)
                $('#formPeminjaman [name="KdUnitOrgPihak1Luar"]').val(peminjaman.KdUnitOrgPihak1Luar)
                // aset
                if(jenis=='persediaan'){
                    let persediaan =  response.data.persediaan
                    console.log(persediaan);
                    $("#AsetPersediaan tbody").html(null)
                    $("#AsetPersediaan").show()
                    $("#AsetPeminjaman").hide()
                    $('#formPeminjaman .ruangForm').hide()
                    for (const property in persediaan) {
                        let Keterangan = persediaan[property].Keterangan??''
                        let NamaBarang = persediaan[property].NamaBarang ??''
                        let Qty = persediaan[property].Qty ??''
                        var tablePic = "<tr>\
                                            <td style='font-size:0.8rem'>" + NamaBarang + "</td>\
                                            <td style='font-size:0.8rem'>" + Qty + "</td>\
                                            <td style='font-size:0.8rem'>"+Keterangan +"</td>\
                                        </tr>";
                        $("#AsetPersediaan tbody").append(tablePic)
                    }

                } else {
                    let peminjamanDetail = response.data.peminjaman_detail ?? response.data.persediaan
                    $("#AsetPeminjaman tbody").html(null)
                    $("#AsetPersediaan").hide()
                    $('#formPeminjaman .ruangForm').show()
                    $("#AsetPeminjaman").show()
                    for (const property in peminjamanDetail) {
                        let KeteranganPeminjaman = peminjamanDetail[property].KeteranganPeminjaman??''
                        let NomorIKN = peminjamanDetail[property].NomorIKN
                        let SN = peminjamanDetail[property].SN ??''
                        let NamaBarang = peminjamanDetail[property].NamaBarang
                        var tablePic = "<tr>\
                                            <td style='font-size:0.8rem'>" + NomorIKN + "</td>\
                                            <td style='font-size:0.8rem'>" + SN + "</td>\
                                            <td style='font-size:0.8rem'>" + NamaBarang + "</td>\
                                            <td style='font-size:0.8rem'>"+KeteranganPeminjaman +"</td>\
                                        </tr>";
                        $("#AsetPeminjaman tbody").append(tablePic)
                    }
                }
                if(response.data.NipPihak1Luar){
                    $('#pihak1dalam').hide()
                    $('.pihak1luar').show()
                } else {
                    $('#pihak1dalam').show()
                    $('.pihak1luar').hide()
                }
                $('#modalBaPeminjaman').modal('toggle');
                $('#loadingAnimation').removeClass('lds-dual-ring');
            }
        })
    })
    $(document).on("submit",'#formPeminjaman',function (event) {
        event.preventDefault();
        var LayananId = $('#id').val();
        var _token = $('meta[name="csrf-token"]').attr('content');
        console.log($('#formPeminjaman').serialize());
        url ='{{ route('ba-peminjaman.store') }}'
        if($('#jenisBAPeminjaman').val()=='kembali'){
            url = '{{ route('ba-peminjaman.updatePengembalian') }}'
        }
        if($('#jenisBAPeminjaman').val()=='persediaan'){
            url = '{{ route('ba-persediaan.store') }}'
        }
        $.ajax({
            url: url,
            type: "POST",
            data: $('#formPeminjaman').serialize(),
            beforeSend: function (data){
                $('#loadingAnimation').addClass('lds-dual-ring');
            },
            success: function (data) {
                toastr.info(data.message);
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                if (typeof loadDataTL === "function"){
                    loadDataTL(LayananId)
                } else{
                    $('#table').DataTable().ajax.reload()
                }
                $('#loadingAnimation').removeClass('lds-dual-ring');
                $('#modalBaPeminjaman').modal('toggle');

            },
            error : function () {
                alert('Terjadi kesalahan, silakan reload');
            }
        })
    });
</script>