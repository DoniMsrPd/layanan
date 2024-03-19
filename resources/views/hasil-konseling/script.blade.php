<script>
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
                url: "{{ url('hasil-konseling/datatables') }}?konselingPegawaiId={{ $data->konseling->Id }}&monitoring={{ request()->monitoring }}",
                method: 'POST',
            },
            columns: [
                { data: 'TglKonselingRealisasi' },
                { data: 'Durasi' },
                { data: 'permasalahan.Nama',name: 'permasalahan.Nama' },
                { data: 'sub_masalah.Nama',name: 'sub_masalah.Nama' },
                { data: 'rujukan.Nama',name: 'rujukan.Nama' },
                { data: 'Catatan',
                    render: function(data, type, row, meta) {
                        return row.Catatan ? row.Catatan.replaceAll('\n','<br>') :''
                    },
                },
                { data: 'Files' },
                { data: 'konseling.status_rekomendasi.Nama',name: 'konseling.status_rekomendasi.Nama' },
                {
                    data: 'pilih_',
                    searchable: false
                }
            ],
        });
    })
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
    let getSubMasalah = (refPermasalahanId, refSubMasalahId) =>{
        $.ajax({
            url: '{{ url('hasil-konseling/get-sub-masalah') }}?refPermasalahanId='+refPermasalahanId,
            type: "GET",
            dataType: "json",
            success: function (response) {
                $('select[name="RefSubMasalahId"]').empty();
                $('select[name="RefSubMasalahId"]').append('<option value="">Pilih Sub Masalah</option>');
                $.each(response.data, function (key, value) {
                    isSelected = refSubMasalahId == value.Id ? 'selected' : '';
                    $('select[name="RefSubMasalahId"]').append('<option  '+isSelected+ ' value=" ' + value.Id + '">' + value.Nama + '</option>');
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
    $(document).on('change', '#RefPermasalahanId', function() {
        getSubMasalah($(this).val())
    })
    $('#form')[0].reset();
    $(document).on('click', '#addHasilKonseling', function() {
        $('#hasilKonselingContainer').hide()
        $('#hasilKonselingForm').show()
        $('#simpan').data('url',$(this).data('url'))
        $('#simpan').data('method',$(this).data('method'))
    })
    $(document).on('click', '#batal', function() {
        $('#hasilKonselingContainer').show()
        $('#hasilKonselingForm').hide()
        $('#form')[0].reset();
        $("#table-file").html('')
    })
    $(document).on('click', '.editHasilKonseling', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            url: "/hasil-konseling/"+id+"/edit",
            type: "GET",
            dataType: "json",
            success: function (response) {
                data = response.data
                $('#TglKonselingRealisasi').val(data.TglKonselingRealisasiOutput);
                $('#Jam').val(data.Jam);
                $('#Menit').val(data.Menit);
                $('#RefRujukanId').val(data.RefRujukanId);
                $('#RefStatusRekomendasiId').val(data.konseling.RefStatusRekomendasiId);
                $('#Catatan').val(data.Catatan);
                $('#RefPermasalahanId').val(data.RefPermasalahanId);
                getSubMasalah(data.RefPermasalahanId,data.RefSubMasalahId)

                $.each(data.files, function (key, value) {

                    rowFile = `
                        <tr>
                            <td><a href="/file/${value.Id}">${value.NmFileOriginal}</a></td>
                            <td width="10%">
                                <a href="#" class="text-danger delete" target="_blank" title="Hapus File" data-type="dokumenFile" data-id="${value.Id}" data-url="/file/${value.Id}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></td>
                        </tr>
                    `;
                    $("#table-file tbody").append(rowFile)
                })
                $('#simpan').data('url','{{ url('hasil-konseling') }}/'+data.Id)
                $('#simpan').data('method','PUT')
                $('#hasilKonselingContainer').hide()
                $('#hasilKonselingForm').show()
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

    })
    $(document).on('click', '#simpan', function(e) {
        e.preventDefault();
        var urlSave = $(this).data('url');
        var method = $(this).data('method');
        var _token = $('meta[name="csrf-token"]').attr('content');
        data = new FormData();
        data.append('TglKonselingRealisasi', $('#TglKonselingRealisasi').val());
        data.append('Jam', $('#Jam').val());
        data.append('Menit', $('#Menit').val());
        data.append('RefPermasalahanId', $('#RefPermasalahanId').val());
        data.append('RefSubMasalahId', $('#RefSubMasalahId').val());
        data.append('RefStatusRekomendasiId', $('#RefStatusRekomendasiId').val());
        data.append('RefRujukanId', $('#RefRujukanId').val());
        data.append('Catatan', $('#Catatan').val());
        data.append('KonselingPegawaiId', '{{ $data->konseling->Id }}');
        data.append('_method', method);
        data.append('_token', _token);
        $.each($("#files")[0].files, function(i, file) {
            data.append('files[]', file);
        });
        $.ajax({
            url: urlSave,
            type: "POST",
            data: data,
            contentType: false,
            processData: false,
            success: function (data) {
                toastr.info(data.message);
                location.reload()
                // $('#table').dataTable().api().ajax.reload()
                $('#hasilKonselingContainer').show()
                $('#hasilKonselingForm').hide()
                $('#form')[0].reset();
                $("#table-file").html('')
                // $.unblockUI();
            },
            beforeSend: function() {
                blockUI();
            },
            complete: function (data) {
                $.unblockUI();
            },
            error : function () {
                $.unblockUI();
                alert('Terjadi kesalahan, silakan reload');
            }
        })
    })
    // getSubMasalah($('#RefPermasalahanId').val())
</script>