<script>

    $(document).on('click', '#addBtn', function() {
        $("#Nama").val('')
        $('#FormModalLabel').html('Tambah Permasalahan')
        $('#formAsetTetap').attr('action', "{{ route('permasalahan.store') }}");
        $('#FormModal').modal('show')
    })
    $(document).on('click', '.editBtn', function() {
        $("[name='_method']").val('PUT')
        $("#Nama").val($(this).data('nama'))
        $('#FormModalLabel').html('Edit Permasalahan')
        $('#formAsetTetap').attr('action', "{{ url('master/permasalahan') }}/"+$(this).data('id'));
        $('#FormModal').modal('show')
    })
</script>