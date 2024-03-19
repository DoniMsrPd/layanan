<script>
    $(document).on('click', '#addBtn', function() {
        $("#Nama").val('')
        $('#FormModalLabel').html('Tambah Jenis Rujukan')
        $('#formAsetTetap').attr('action', "{{ route('jenis-rujukan.store') }}");
        $('#FormModal').modal('show')
    })
    $(document).on('click', '.editBtn', function() {
        $("[name='_method']").val('PUT')
        $("#Nama").val($(this).data('nama'))
        $('#FormModalLabel').html('Edit Jenis Rujukan')
        $('#formAsetTetap').attr('action', "{{ url('master/jenis-rujukan') }}/"+$(this).data('id'));
        $('#FormModal').modal('show')
    })
</script>