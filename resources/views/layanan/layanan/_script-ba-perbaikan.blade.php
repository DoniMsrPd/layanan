<script>

    $(document).on("click",'.pilih-pegawai3',function () {
        $('#formBaAset [name="NipPihak2"]').val($(this).data('nip'))
        $('#formBaAset [name="KdUnitOrgPihak2"]').val($(this).data('kd_unit_org'))
        $('#formBaAset [name="NmUnitOrgPihak2"]').val($(this).data('nm_unit_org'))
        $('#formBaAset [name="NmJabatanPihak2"]').val($(this).data('nm_jabatan'))
        $('#formBaAset #NmPegawaiPihak2').val($(this).data('nip')+'-'+$(this).data('nama'))
        $('#formBaAset #NmJabatanPihak2').html($(this).data('nm_jabatan'))
        $('#formBaAset #NmUnitOrgPihak2').html($(this).data('nm_unit_org'))
        $('#pegawai-modal3').modal('toggle');
        $('#modalBaAset').modal('toggle');
    });
    $(document).on("click",'.pilih-pegawai4',function () {
        $('#formBaAset [name="NmJabatanPejabat"]').val($(this).data('nm_jabatan'))
        $('#formBaAset [name="NmPegTtdPejabat"]').val($(this).data('nama'))
        $('#formBaAset [name="NipTtdPejabat"]').val($(this).data('nip'))
        $('#formBaAset #Pejabat').val($(this).data('nip')+'-'+$(this).data('nama'))
        $('#formBaAset #NmJabatanPejabat').html($(this).data('nm_jabatan'))
        $('#pegawai-modal4').modal('toggle');
        $('#modalBaAset').modal('toggle');
    });
    $(document).on("click",'.pilih-pegawai5',function () {
        $('#formBaAset [name="KdUnitOrgPengembalian"]').val($(this).data('kd_unit_org'))
        $('#formBaAset [name="NipPengembalianAset"]').val($(this).data('nip'))
        $('#formBaAset #PegawaiPengembalian').val($(this).data('nip')+'-'+$(this).data('nama'))
        $('#formBaAset #NmJabatanPengembalian').html($(this).data('nm_jabatan'))
        $('#formBaAset #NmUnitOrgPengembalian').html($(this).data('nm_unit_org'))
        $('#pegawai-modal5').modal('toggle');
        $('#modalBaAset').modal('toggle');
    });
    $(document).on("click",'.formBaAset',function () {
        id = $(this).data('id')
        jenis = $(this).data('jenis')
        $('#modal-title-ba').html($(this).data('title'))
        if($(this).data('pengembalian')==1){
            $('#pengembalianForm').show()
            $('#TglBA').prop('readonly', true);
            $('#Ruang').prop('readonly', true);
            $('button.lookup-pegawai3,button.lookup-pegawai4').hide()
            $('#NmPegawaiPihak2').removeClass("lookup-pegawai3");
            $('#Pejabat').removeClass("lookup-pegawai4");
        } else {
            $('#pengembalianForm').hide()
            $('#TglBA').prop('readonly', false);
            $('#Ruang').prop('readonly', false);
            $('button.lookup-pegawai3,button.lookup-pegawai4').show()
            $('#NmPegawaiPihak2').addClass("lookup-pegawai3");
            $('#Pejabat').addClass("lookup-pegawai4");
        }
        $('#idAset').val(id)
        $.ajax({
            url: "{{ url('/ba-perbaikan') }}/" + id,
            type: "GET",
            beforeSend: function (data){
                $('#loadingAnimation').addClass('lds-dual-ring');
            },
            success : function (response){
                $('[name="jenisBA"]').val(jenis)
                layanan_aset = response.data;
                if(jenis=='terima'){
                    NoBA = layanan_aset.NoBA ?? layanan_aset.NoBABaru;
                    TglBA = layanan_aset.TglBA ?? '{{ date('Y-m-d') }}'
                    $('#ruangForm').show()
                    $('#NmPegForm').show()
                    $('#PegawaiPengembalianForm').hide()
                    $('#keteranganForm').hide()
                    KdUnitOrgPihak2 = layanan_aset.KdUnitOrgPihak2 ?? '{{ auth()->user()->pegawai->KdUnitOrg }}'
                    NmUnitOrgPihak2 = layanan_aset.NmUnitOrgPihak2 ?? '{{ auth()->user()->pegawai->NmUnitOrg }}'
                    NmJabatanPihak2 = layanan_aset.NmJabatanPihak2 ?? '{{ auth()->user()->pegawai->NmJabatan }}'
                    NipPihak2 = layanan_aset.NipPihak2 ??'{{ auth()->user()->NIP }} '
                    NmPegawaiPihak2 = layanan_aset.NipPihak2 ? layanan_aset.NipPihak2+' - '+layanan_aset.pihak2?.NmPeg :'{{ auth()->user()->NIP }} - {{ auth()->user()->pegawai->NmPeg }}'
                    NmUnitOrgPihak2 = layanan_aset.NmUnitOrgPihak2 ??'{{ auth()->user()->pegawai->NmUnitOrg }}'
                    NmJabatanPihak2 = layanan_aset.NmJabatanPihak2 ??'{{ auth()->user()->pegawai->NmJabatan }}'
                    form = `
                                <div class="form-group row mb-3"><label class="col-form-label col-sm-3" for="">
                                        Fisik</label>
                                    <div class="col-sm-8"><textarea class="form-control" rows="2" name="fisikPengembalian">${layanan_aset.Fisik??''}</textarea></div>
                                </div>
                                <div class="form-group row mb-3"><label class="col-form-label col-sm-3" for="">
                                        Kelengkapan</label>
                                    <div class="col-sm-8"><textarea class="form-control" rows="2" name="kelengkapanPengembalian">${layanan_aset.Kelengkapan??''}</textarea></div>
                                </div>
                                <div class="form-group row mb-3"><label class="col-form-label col-sm-3" for="">Data</label>
                                    <div class="col-sm-8"><textarea class="form-control" rows="2" name="dataPengembalian">${layanan_aset.Data??''}</textarea></div>
                                </div>
                                <div class="form-group row mb-3"><label class="col-form-label col-sm-3" for="">No Box</label>
                                    <div class="col-sm-8"><input class="form-control" placeholder="No Box" type="number" name="noBoxPengembalian" value="${layanan_aset.NoBox}"></div>
                                </div>
                                <div class="form-group row mb-3"><label class="col-form-label col-sm-3" for="">Keterangan Aset Lain</label>
                                    <div class="col-sm-8"><textarea class="form-control" rows="2" id="keteranganAsetLain" name="keteranganAsetLain">${layanan_aset.Keterangan??''}</textarea></div>
                                </div>`
                    $('#dataTambahan').html(form);
                    CKEDITOR.replace( 'keteranganAsetLain');
                    CKEDITOR.instances['keteranganAsetLain'].setData(layanan_aset.Keterangan??'')
                } else {
                    NoBA = layanan_aset.NoBAPengembalian ?? layanan_aset.NoBAPengembalianBaru;
                    TglBA = layanan_aset.TglKembali ?? '{{ date('Y-m-d') }}'
                    $('#ruangForm').hide()
                    $('#NmPegForm').hide()
                    $('#PegawaiPengembalianForm').show()
                    $('#keteranganForm').show()
                    KdUnitOrgPihak2 = layanan_aset.KdUnitOrgPengembalianPihak2 ?? '{{ auth()->user()->pegawai->KdUnitOrg }}'
                    NmUnitOrgPihak2 = layanan_aset.NmUnitOrgPengembalianPihak2 ?? '{{ auth()->user()->pegawai->NmUnitOrg }}'
                    NmJabatanPihak2 = layanan_aset.NmJabatanPengembalianPihak2 ?? '{{ auth()->user()->pegawai->NmJabatan }}'
                    NipPihak2 = layanan_aset.NipPengembalianAsetPihak2 ??'{{ auth()->user()->NIP }} '
                    NmPegawaiPihak2 = layanan_aset.NipPengembalianAsetPihak2 ? layanan_aset.NipPengembalianAsetPihak2+' - '+layanan_aset.pihak2pengembalian?.NmPeg :'{{ auth()->user()->NIP }} - {{ auth()->user()->pegawai->NmPeg }}'
                    NmUnitOrgPihak2 = layanan_aset.NmUnitOrgPengembalianPihak2 ??'{{ auth()->user()->pegawai->NmUnitOrg }}'
                    NmJabatanPihak2 = layanan_aset.NmJabatanPengembalianPihak2 ??'{{ auth()->user()->pegawai->NmJabatan }}'
                    form = `
                                <div class="form-group row mb-3"><label class="col-form-label col-sm-3" for="">
                                        Fisik</label>
                                    <div class="col-sm-8">${layanan_aset.Fisik}</div>
                                </div>
                                <div class="form-group row mb-3"><label class="col-form-label col-sm-3" for="">
                                        Kelengkapan</label>
                                    <div class="col-sm-8">${layanan_aset.Kelengkapan}</div>
                                </div>
                                <div class="form-group row mb-3"><label class="col-form-label col-sm-3" for="">Data</label>
                                    <div class="col-sm-8">${layanan_aset.Data}</div>
                                </div>
                                <div class="form-group row mb-3"><label class="col-form-label col-sm-3" for="">No Box</label>
                                    <div class="col-sm-8">${layanan_aset.NoBox}</div>
                                </div>
                                <div class="form-group row mb-3"><label class="col-form-label col-sm-3" for="">Keterangan Aset Lain</label>
                                    <div class="col-sm-8">${layanan_aset.Keterangan}</div>
                                </div>`
                    $('#dataTambahan').html(form);
                }
                $('#formBaAset [name="NoBA"]').val(NoBA)
                $('#formBaAset [name="TglBA"]').val(TglBA)
                $('#formBaAset [name="Ruang"]').val(layanan_aset.Ruang)
                $('#NmPeg').html(layanan_aset.layanan.Nip+'-' +layanan_aset.layanan.NmPeg)

                // pihak2
                $('#formBaAset [name="KdUnitOrgPihak2"]').val(KdUnitOrgPihak2)
                $('#formBaAset [name="NmUnitOrgPihak2"]').val(NmUnitOrgPihak2)
                $('#formBaAset [name="NmJabatanPihak2"]').val(NmJabatanPihak2)
                $('#formBaAset [name="NipPihak2"]').val(NipPihak2)
                $('#formBaAset #NmPegawaiPihak2').val(NmPegawaiPihak2)
                $('#NmUnitOrgPihak2').html(NmUnitOrgPihak2)
                $('#NmJabatanPihak2').html(NmJabatanPihak2)

                // pejabat
                $('#formBaAset [name="NmJabatanPejabat"]').val(layanan_aset.NmJabatanPejabat ?? 'Kepala {{ pejabatTTD()->NmUnitOrg }}')
                $('#formBaAset [name="NmPegTtdPejabat"]').val(layanan_aset.NmPegTtdPejabat ?? '{{ pejabatTTD()->NmPeg }}')
                $('#formBaAset [name="NipTtdPejabat"]').val(layanan_aset.NipTtdPejabat ??'{{ pejabatTTD()->Nip }}')
                $('#formBaAset #Pejabat').val( layanan_aset.NipTtdPejabat ? layanan_aset.NipTtdPejabat+' - '+layanan_aset.NmPegTtdPejabat :'{{ pejabatTTD()->Nip }} - {{ pejabatTTD()->NmPeg }}')

                    // pihak 1 pengembalian
                CKEDITOR.instances.KeteranganPengembalian.setData(layanan_aset.KeteranganPengembalian??layanan_aset.KeteranganDefault)
                $('#formBaAset [name="KdUnitOrgPengembalian"]').val(layanan_aset.KdUnitOrgPengembalian ?? layanan_aset.layanan.pelapor.KdUnitOrg)
                $('#formBaAset [name="NipPengembalianAset"]').val(layanan_aset.NipPengembalianAset ?? layanan_aset.layanan.pelapor.Nip)
                $('#formBaAset #PegawaiPengembalian').val( layanan_aset.NipPengembalianAset ? layanan_aset.NipPengembalianAset+' - '+layanan_aset.pengembali?.NmPeg :layanan_aset.layanan.pelapor.Nip+' - '+layanan_aset.layanan.pelapor.NmPeg)

                // table aset
                $('#NomorIKN').html(layanan_aset.NomorIKN)
                $('#NamaAset').html(layanan_aset.NamaBarang)
                $('#SerialNumber').html(layanan_aset.SN)
                $('#keteranganAset').html(layanan_aset.Keterangan)

                $('#modalBaAset').modal('toggle');
                $('#loadingAnimation').removeClass('lds-dual-ring');
            }
        })
    })
    $(document).on("submit",'#formBaAset',function (event) {
        event.preventDefault();
        var LayananId = $('#id').val();
        var _token = $('meta[name="csrf-token"]').attr('content');
        console.log($('#formBaAset').serialize());
        $.ajax({
            url: '{{ route('ba-perbaikan.store') }}',
            type: "POST",
            data: $('#formBaAset').serialize(),
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
                $('#modalBaAset').modal('toggle');

            },
            error : function () {
                alert('Terjadi kesalahan, silakan reload');
            }
        })
    });
</script>