<script>
    $(document).on('click', '#addBtn', function() {
        $("#Nama").val('')
        $('#FormModalLabel').html('Tambah Jenis Konseling')
        $('#formAsetTetap').attr('action', "{{ route('jenis-konseling.store') }}");
        $('#FormModal').modal('show')
    })
    $(document).on('click', '.editBtn', function() {
        $("[name='_method']").val('PUT')
        $("#Nama").val($(this).data('nama'))
        $('#FormModalLabel').html('Edit Jenis Konseling')
        $('#formAsetTetap').attr('action', "{{ url('master/jenis-konseling') }}/"+$(this).data('id'));
        $('#FormModal').modal('show')
    })
</script>