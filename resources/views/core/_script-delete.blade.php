<script>
    $(document).on('click', '.deleteData', function() {
        let that = $(this);
        let urlDelete = that.data('url');
        let LayananId = that.data('layanan_id');
        let mapping = that.data('mapping');
        let redirect = that.data('redirect');
        let titleDelete = that.data('title');
        let groupSolver = that.data('group-solver');
        let tableDelete = that.closest('table');
        let listDelete = that.closest('li');
        let tableDeleteId = tableDelete.attr('id');
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: "Anda Yakin menghapus "+titleDelete+"?",
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
                    type : "POST",
                    data: {
                        '_method':'DELETE',
                        '_token':csrf_token
                    },
                    success: function (response) {
                        if(response.success){
                            if(mapping!=1){
                                that.closest('tr').remove();
                            }
                            if(groupSolver==1 && $('.groupSolver').length==0){
                                $('.lookup-solver').hide()
                            }
                            // $('#' + tableDeleteId).dataTable().api().ajax.reload();

                            if (tableDelete.hasClass('dataTable')) {
                                $('#' + tableDeleteId).dataTable().api().ajax.reload()
                                if(tableDeleteId=='tableTematik'){
                                    selectTematik ()
                                }
                            } else { //for file list
                                let inputFile = that.closest('div').find('.custom-file');
                                let liFile = that.closest('.list-file').find('li');
                                if(LayananId){
                                    loadDataTL(LayananId)
                                }
                                if(redirect){
                                    toastr.clear()
                                    toastr.success(response.message)
                                    window.location.replace(redirect)
                                    return false
                                }
                                // if(liFile.length == 1) {
                                //     inputFile.attr('required', true)
                                // } else {
                                //     inputFile.removeAttr('required')
                                // }
                                listDelete.remove();
                                // checkButtonDelete();
                            }
                            toastr.clear()
                            toastr.success(response.message)
                            $('#loadingAnimation').removeClass('lds-dual-ring');
                        }
                    },
                        beforeSend: function() {
                            listDelete.css('background', '#f7f1f1')
                            that.closest('tr').css('background', '#f7f1f1')
                            $('#loadingAnimation').addClass('lds-dual-ring');
                        },

                });
            }
        })
    })
</script>