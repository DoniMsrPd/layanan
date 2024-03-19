<script>
    $(document).on('click', '.deleteData', function() {
        let that = $(this);
        let urlDelete = that.data('url');
        let titleDelete = that.data('title');
        let tableDelete = that.closest('table');
        let listDelete = that.closest('li');
        let tableDeleteId = tableDelete.attr('id');
        let biaya_pegawai = that.data('biaya_pegawai');
        Swal.fire({
            title: 'Konfirmasi',
            text: "Kamu yakin akan menghapus " + titleDelete + "?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya, hapus!'
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
                        listDelete.css('background', '#f7f1f1')
                        that.closest('tr').css('background', '#f7f1f1')
                    },
                    success: function(data) {
                        that.closest('tr').hide('slow');

                        $('.swal2-confirm').prop('disabled', false);
                        $('.swal2-confirm').html('Ya, hapus!');
                        if (tableDelete.hasClass('dataTable')) {
                            $('#' + tableDeleteId).dataTable().api().ajax.reload()
                        } else { //for file list
                            let inputFile = that.closest('div').find('.custom-file-input');
                            let liFile = that.closest('.list-file').find('li');
                            if (liFile.length == 1) {
                                inputFile.attr('required', true)
                            } else {
                                inputFile.removeAttr('required')
                            }
                            listDelete.remove();
                            checkButtonDelete();
                        }
                        if(biaya_pegawai==1){
                            that.closest('tr').remove();
                            Total()
                            showTblPerjadin()
                        }
                    },
                    error: function(data) {
                        swalError(data.msg);
                    }
                });
            }
        })
    })

    $(document).ready(function() {
        // checkButtonDelete();
    })

    let checkButtonDelete = function() {
        let that = $('.deleteData');
        let liFile = that.closest('.list-file').find('li');
        if (liFile.length == 1) {
            that.hide();
        } else {
            that.show();
        }
    }

    // uoload file limit max 1MB
    var uploadField = document.getElementById("files");
    if (uploadField) {
        uploadField.onchange = function() {
            $this = $(this);
            console.log(this.files[0].size);
            if (this.files[0].size > 10000163) {
                $this.closest('div').find('label').addClass('error')
                $('<span id="errorFiles" style="font-size: 11px; color: red;" class="error">Ukuran file maks 10MB.</span>').insertAfter($this);
                this.value = "";
            } else {
                $this.closest('div').find('label').removeClass('error')
                $this.closest('div').find('#errorFiles').remove()
            }
        };
    }

</script>

<style>
    label.error {
        border: 1px solid #bb4b4b !important;
    }

    .table > :not(caption) > * > * {
        padding: 0.72rem 1rem !important;
    }

    /* .deleteData  {
        display: none;
    } */
</style>
