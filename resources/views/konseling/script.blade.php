<script>
    @if ($data->method=='POST' && auth()->user()->hasRole('Staf Konseling'))
    $(function() {
        $('#RefStatusId').val('2').trigger('change')
    });
    @endif
@if ($data->method=='PUT'&& $data->hasilKonseling==1)
$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    table = $('#table').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        "drawCallback": function(settings) {
            feather.replace();
        },
        ajax: {
            url: "{{ url('hasil-konseling/datatables') }}?konselingPegawaiId={{ $data->konseling->Id }}&isView={{ request()->isView }}",
            method: 'POST',
        },
        columns: [
            { data: 'TglKonselingRealisasi' },
            { data: 'Durasi' },
            { data: 'permasalahan.Nama',name: 'permasalahan.Nama' },
            { data: 'sub_masalah.Nama',name: 'sub_masalah.Nama' },
            { data: 'rujukan.Nama',name: 'rujukan.Nama' },
            { data: 'Catatan' },
            { data: 'Files' },
            { data: 'konseling.status_rekomendasi.Nama',name: 'konseling.status_rekomendasi.Nama' },
        ],
    });
})
@endif
    $(function() {
        getHubunganPegawaiOption();
    })
    var urlPegawai = "{{ url('konseling/modalpegawai/') }}";
    $(document).on('click', '#btn-pegawai', function() {
        dtTableHonor = $('#table-pegawai').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            "drawCallback": function(settings) {
                feather.replace();
            },
            ajax: {
                url: urlPegawai,
                method: 'POST',
            },
            columns: [
                { data: 'Nip' },
                { data: 'NmPeg' },
                { data: 'nmunitorg_' },
                {
                    data: 'pilih_',
                    searchable: false
                }
            ],
        });
    });
    $(document).on('click', '.pick-pegawai', function() {
        var that = $(this);
        nip = $(this).data('nip');
        nmpeg = $(this).data('nmpeg');
        nokonseli = $(this).data('nokonseli');
        kdunitorg = $(this).data('kdunitorg');
        $(`#Pegawai`).html(nip +' '+nmpeg)
        $(`#Nip`).val(nip)
        $(`#NmPeg`).val(nmpeg)
        $(`#NmPegawai`).val(nmpeg)
        $(`#NoKonseli`).val(nokonseli)
        $(`#KdUnitOrg`).val(kdunitorg)
        $("#PegawaiModal .close").click()

        $('#RefHubunganKeluargaId').val(0).trigger('change');
        $('select[name="NmPeg"]').empty();
        $('select[name="NmPeg"]').append('<option selected  value="'+$(`#NmPegawai`).val()+'">'+$(`#NmPegawai`).val()+'</option>');
        getHubunganPegawaiOption()
    })
    var urlJadwalKonseling = "{{ url('master/jadwal-konseling/modal/') }}";
    $(document).on('click', '#btn-jadwal-konseling', function() {
        dtTableHonor = $('#table-jadwal-konseling').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            "drawCallback": function(settings) {
                feather.replace();
            },
            ajax: {
                url: urlJadwalKonseling,
                method: 'POST',
            },
            columns: [
                { data: 'Regional',
                    searchable: false },
                { data: 'Lokasi',
                    searchable: false },
                { data: 'Tanggal',
                    searchable: false },
                { data: 'Konselor',
                    searchable: false },
                { data: 'Sesi' ,
                    searchable: false},
                {
                    data: 'pilih_',
                    searchable: false
                }
            ],
            order: [[2, 'asc']]
        });
    });
    $(document).on('click', '.pick-jadwal-konseling', function() {
        var that = $(this);
        id = $(this).data('id');
        nip = $(this).data('nip');
        nama = $(this).data('nama');
        mstkonselorid = $(this).data('mstkonselorid');
        refregionalid = $(this).data('refregionalid');
        reflokasiid = $(this).data('reflokasiid');
        regional = $(this).data('regional');
        lokasi = $(this).data('lokasi');
        tanggal = $(this).data('tanggal');
        $(`#RegionalKonseling`).html(regional)
        $(`#LokasiKonseling`).html(lokasi)
        $(`#Konselor`).html(nip +' '+nama)
        $(`#MstKonselorId`).val(mstkonselorid)
        $(`#RefRegionalId`).val(refregionalid)
        $(`#RefLokasiId`).val(reflokasiid)
        $(`#JadwalKonselingId`).val(id)
        $(`#TglKonselingUsulan`).val(tanggal)
        $(`#TglKonselingUsulan2`).html(tanggal)
        $("#JadwalKonselingModal .close").click()
    })
    $(document).on('change', '#RefPelaksanaanId', function() {
        if ($(this).val()==2) {
            $('.LinkOnline').show()
            $("#LinkOnline").prop('required',true);
        } else{
            $('.LinkOnline').hide()
            $("#LinkOnline").prop('required',false);
        }
    })
    $("#TglRujukan").flatpickr({
        dateFormat: "d F Y",
        allowInput: true
    });
    $(document).on('change', '#RefStatusId', function() {
        if ($(this).val()==2) {
            $('.rujukan').show()
            $("#NDRujukan").prop('required',true);
            $("#files").prop('required',true);
            $("#TglRujukan").prop('required',true);
        } else{
            $('.rujukan').hide()
            $("#NDRujukan").prop('required',false);
            $("#files").prop('required',false);
            $("#TglRujukan").prop('required',false);
        }
    })
    $(document).on('change', '#RefTahapanId', function() {
        if ($(this).val()==11) {
            $('.konfirmasi-perubahan').show()
            $('.konfirmasi-pembatalan').hide()
            $('.konfirmasi-penolakan').hide()
        }else if($(this).val()==13){
            $('.konfirmasi-pembatalan').show()
            $('.konfirmasi-perubahan').hide()
            $('.konfirmasi-penolakan').hide()
        }else if($(this).val()==10){
            $('.konfirmasi-penolakan').show()
            $('.konfirmasi-perubahan').hide()
            $('.konfirmasi-pembatalan').hide()
        } else{
            $('.konfirmasi-perubahan').hide()
            $('.konfirmasi-pembatalan').hide()
            $('.konfirmasi-penolakan').hide()
        }
    })
    $(document).on('change', '#RefHubunganKeluargaId', function() {
        if ($(this).val()!=0) {
            getHubunganPegawai($(this).val())
            $('.NmPeg').show()
            $("#NmPeg").prop('required',true);
        } else{
            $("#NmPeg").prop('required',false);
            $('.NmPeg').hide()
            $('select[name="NmPeg"]').empty();
            $('select[name="NmPeg"]').append('<option selected  value="'+$(`#NmPegawai`).val()+'">'+$(`#NmPegawai`).val()+'</option>');
        }
    })

    let getHubunganPegawai = (refHubunganPegawaiId) =>{
        $.ajax({
            url: '{{ url('konseling/get-hubungan-pegawai') }}?refHubunganPegawaiId='+refHubunganPegawaiId+'&nip='+$(`#Nip`).val(),
            type: "GET",
            dataType: "json",
            success: function (response) {
                console.log(response.data.length);
                $('select[name="NmPeg"]').empty();
                if (response.data.length!=1) {
                    $('select[name="NmPeg"]').append('<option value="">Pilih Nama</option>');
                }
                $.each(response.data, function (key, value) {
                    $('select[name="NmPeg"]').append('<option  value=" ' + value.NAMA + '">' + value.NAMA + '</option>');
                })
            },
            beforeSend: function() {
                blockUI();
            },
            error : function(response) {
                $.unblockUI();
            },
            complete: function (data) {
                $.unblockUI();
            }
        })
    }
    let getHubunganPegawaiOption = () =>{
        $.ajax({
            url: '{{ url('konseling/get-hubungan-pegawai-option') }}/'+$(`#Nip`).val(),
            type: "GET",
            dataType: "json",
            success: function (response) {
                $("#RefHubunganKeluargaId option").show();
                $.each(response.data, function (key, value) {
                    if (value<1) {
                        $("#RefHubunganKeluargaId option[value="+key+"]").hide();
                    }
                })
            },
            beforeSend: function() {
                blockUI();
            },
            error : function(response) {
                $.unblockUI();
            },
            complete: function (data) {
                $.unblockUI();
            }
        })
    }
    function blockUI() {
        $.blockUI({
            message: `<div class="spinner-grow" style="width: 3rem; height: 3rem" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>`,
            overlayCSS: {
                backgroundColor: '#FFF',
                opacity: 0.8,
                cursor: 'wait'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });
    }
</script>