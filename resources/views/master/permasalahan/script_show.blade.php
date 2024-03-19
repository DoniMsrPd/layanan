<script>

    $(document).on('click', '#addBtn', function() {
        $("#Nama").val('')
        $('#FormModalLabel').html('Tambah Sub Masalah')
        $('#formAsetTetap').attr('action', "{{ route('sub-masalah.store') }}");
        $('#FormModal').modal('show')
    })
    $(document).on('click', '.editBtn', function() {
        $("[name='_method']").val('PUT')
        $("#Nama").val($(this).data('nama'))
        $('#FormModalLabel').html('Edit Sub Masalah')
        $('#formAsetTetap').attr('action', "{{ url('master/sub-masalah') }}/"+$(this).data('id'));
        $('#FormModal').modal('show')
    })
</script>