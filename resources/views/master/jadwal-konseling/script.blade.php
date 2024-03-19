<script>
    var urlPegawai = "{{ url('master/konselor/modalkonselor/') }}";
    $(document).on('click', '#btn-konselor', function() {
        dtTableHonor = $('#table-konselor').DataTable({
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
                { data: 'Konselor' },
                {
                    data: 'pilih_',
                    searchable: false
                }
            ],
        });
    });
    $(document).on('click', '.pick-konselor', function() {
        var that = $(this);
        id = $(this).data('id');
        nip = $(this).data('nip');
        nama = $(this).data('nama');
        lokasikonseling = $(this).data('lokasikonseling');
        regionalkonseling = $(this).data('regionalkonseling');
        $(`#RegionalKonseling`).html(regionalkonseling)
        $(`#LokasiKonseling`).html(lokasikonseling)
        $(`#Konselor`).html(nip +' '+nama)
        $(`#MstKonselorId`).val(id)
        $("#KonselorModal .close").click()
    })

    $(".flatpickr-time").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });
    $(".flatpickr-multi").flatpickr({
        mode: "multiple",
        dateFormat: "Y-m-d",
    });
</script>