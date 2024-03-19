<script>
    $( document ).ready(function() {
        @if ($isEksternal)
            $('.eksternal').show()
        @else
        $('.eksternal').hide()
        @endif
    });
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
        $(`#Pegawai`).html(nip +' '+nmpeg)
        $(`#NIP`).val(nip)
        $(`#Nama`).val(nmpeg)
        $("#PegawaiModal .close").click()
    })
    $(document).on('change','#RefRegionalId',function () {
        regional = $(this).val()
        $(`#RefLokasiId option[data-regional=${regional}]`).show();
        $(`#RefLokasiId option[data-regional!=${regional}]`).hide();
    })
    $(document).on('change', '#changePassword', function() {

        if ($(this).is(':checked')) {
            $('.changePassword').show()
            $("#old_password").prop('required',true)
            $("#new_password").prop('required',true)
            $("#new_password_confirmation").prop('required',true)
        } else{
            $('.changePassword').hide()
            $("#old_password").prop('required',false)
            $("#new_password").prop('required',false)
            $("#new_password_confirmation").prop('required',false)
        }
    })
    $(document).on('change', '#isEksternal', function() {
        if ($(this).is(':checked')) {
            $('.eksternal').show()
            $('.internal').hide()
            $(`#NIP`).val('')
            $("#NIK").prop('required',true)
            $("#NIP").prop('required',false)
            $("#Nama").prop('required',true)
            $("#Username").prop('required',true)
            $("#password").prop('required',true)
            $("#password_confirmation").prop('required',true)
        } else{
            $('.eksternal').hide()
            $('.internal').show()
            $(`#NIK`).val('')
            $(`#Pegawai`).html('')
            $("#NIP").prop('required',true)
            $("#NIK").prop('required',false)
            $("#Nama").prop('required',false)
            $("#Username").prop('required',false)
            $("#password").prop('required',false)
            $("#password_confirmation").prop('required',false)
        }
        $(`#Nama`).val('')
    })
</script>