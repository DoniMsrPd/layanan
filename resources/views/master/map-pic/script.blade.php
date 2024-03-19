<script>

    var urlPegawai = "{{ url('master/map-pic/datatables/') }}";
    $(document).on('click', '.btnPIC', function() {
        regional_id= $(this).data('id')
        pegawai = $(".pegawai").map(function(){
            if ($(this).data('ref_regional_id')==regional_id) {
                return  $(this).val();
            }
        }).get()
        dtTableHonor = $('#table-pegawai').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            "drawCallback": function(settings) {
                feather.replace();
            },
            "createdRow": function (tr, tdsContent) {
                let checkRow = tdsContent.NIP
                if(pegawai.includes(checkRow)) {
                    $(tr).find('td').css('--bs-table-accent-bg', '#fff3f3')
                    $(tr).find('a').hide()
                }
            },
            ajax: {
                url: urlPegawai+'?RefRegionalId='+regional_id,
                method: 'POST',
            },
            columns: [
                { data: 'NIP' },
                { data: 'name' },
                {
                    data: 'pilih_',
                    searchable: false
                }
            ],
        });
    });
    $(document).on('click', '.pick-pegawai', function() {
        var that = $(this);
        ref_regional_id = $(this).data('ref_regional_id');
        nip = $(this).data('nip');


        var _token = $('meta[name="csrf-token"]').attr('content');
        data = new FormData();
        data.append('Nip', nip);
        data.append('RefRegionalId', ref_regional_id);
        data.append('_method', 'POST');
        data.append('_token', _token);
        $.ajax({
            url: '{{ url('master/map-pic') }}',
            type: "POST",
            data: data,
            contentType: false,
            processData: false,
            success: function (data) {
                toastr.info(data.message);
                location.reload()
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

    $(document).on("click", ".delete", function() {
        let that = $(this);
        let urlDelete = $(this).data('url');
        let type = $(this).data('type');
        let tableDelete = that.closest('table');
        let tableDeleteId = tableDelete.attr('id');
        $that = $(this);
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: "Anda Yakin menghapus data?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                let csrf_token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: urlDelete,
                    type: "POST",
                    data: {
                        '_token': csrf_token,
                        '_method': 'DELETE',
                    },
                    beforeSend: function() {
                        $('.swal2-confirm').prop('disabled', true);
                        $('.swal2-confirm').html('Loading...');
                        $that.closest('tr').css('background', '#f7f1f1')
                    },
                    success: function(data) {
                        toastr.info(data.message);
                        location.reload()
                    },
                    error: function(data) {
                        swalError(data.msg);
                    }
                });
            }
        })
    })
</script>