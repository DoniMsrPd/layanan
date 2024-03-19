
<script>
    $( document ).ready(function() {
        $(document).on('click', '.hapusPegawai', function() {
            let that = $(this);
            let urlDelete = that.data('url');

            swal({ title: 'Konfirmasi',
                text: "Kamu yakin akan menghapus Pengguna?",
                type:'warning',
                showCancelButton:true,
                cancelButtonColor:'#d33',
                confirmButtonColor:'#3085d6',
                confirmButtonText:'<i class="fa fa-check-circle"></i> Ya, Hapus ini',
                cancelButtonText: '<i class="fa fa-times-circle"></i> Batal'
            }).then(function () {

                let csrf_token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: urlDelete,
                    type : "POST",
                    data: {
                        '_method':'DELETE',
                        '_token':csrf_token
                    },
                    success: function (response) {
                        $('#NipPengguna').val('');
                        toastr.clear()
                        toastr.success(response.message)
                        $('#loadingAnimation').removeClass('lds-dual-ring');
                    },
                    beforeSend: function() {
                        $('#loadingAnimation').addClass('lds-dual-ring');
                    },

                });
            })
        })
        $(document).on("click",'.pilih-pegawai9',function () {
            $('#NipPengguna').val($(this).data('nip') +'/'+ $(this).data('nama'))
            $('#KdUnitOrgPengguna').val($(this).data('kd_unit_org'))
            $('#pegawai-modal9').modal('toggle');
            $('#modalFormAsetTI').modal('toggle');

        });

        $('#RefJnsAsetId').on('change', function() {
            var RefJnsAsetId = $(this).val();
            selectType(RefJnsAsetId)
        })
        selectType = (RefJnsAsetId, value = null) => {
            $.ajax({
                    url: "{{ url('/setting/type-aset/select') }}",
                    type: "GET",
                    data: {
                        RefJnsAsetId,
                        value: value,
                    },
                    beforeSend: function(data) {
                        $("#RefTypeAsetId").attr('disabled', true)
                    },
                    success: function(data) {
                        $("#RefTypeAsetId").attr('disabled', false)
                        $("#RefTypeAsetId").html(data)
                        $("#RefTypeAsetId").select2()
                    },
                    error: function() {
                        alert('Terjadi kesalahan, silakan reload');
                    }
                })
        }
        @if(($data->aset))
            @if($data->aset->jenisAset->IsSpesifikasi==1)
                $('.spesifikasi').show()
            @endif

            $('#MasaGaransi').datepicker( {
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'M yy',
                onClose: function(dateText, inst) {
                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    $(this).datepicker('setDate', new Date(year, month, 1));
                }
            }).datepicker("setDate", new Date({{$data->aset->MasaGaransiTahun}},{{$data->aset->MasaGaransiBulan-1}},1));
        @else
            $('#MasaGaransi').datepicker( {
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'M yy',
                onClose: function(dateText, inst) {
                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    $(this).datepicker('setDate', new Date(year, month, 1));
                }
            }).datepicker("setDate", new Date());
        @endif

        $(document).on('change', '#RefJnsAsetId', function(){
            if($("#RefJnsAsetId").select2().find(":selected").data("spesifikasi")==1){
                $('.spesifikasi').show()
            }else {
                $('.spesifikasi').hide()
            }
        });
        $('#HargaPerolehan').on('input', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            let text = $(this).val();
            let result = text.replace(",", "");
            // loadForm($('#diskon').val(), $(this).val());

            var selection = window.getSelection().toString();
            if (selection !== '') {
                return;
            }
            // When the arrow keys are pressed, abort.
            if ($.inArray(event.keyCode, [38, 40, 37, 39]) !== -1) {
                return;
            }
            var $this = $(this);
            // Get the value.
            var input = $this.val();
            input = input.replace(/[\D\s\._\-]+/g, "");
            input = input?parseInt(input, 10):0;
            $this.val(function () {
                return (input === 0)?"":input.toLocaleString("id-ID");
            });

        }).focus(function () {
            $(this).select();
        });
    });
</script>