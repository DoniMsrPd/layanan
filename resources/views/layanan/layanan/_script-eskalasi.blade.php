<script>
    var groupSolver = [],deleteGroupSolver=[]; let LayananId;
    $(document).on("click",'.pilih-group-solver',function () {
        Id = $(this).data('id');
        var _token = $('meta[name="csrf-token"]').attr('content');
        data = new FormData();
        data.append('Id', Id);
        data.append('LayananId', LayananId);
        data.append('_token', _token);
        that = $(this);
        $.ajax({
            url: '/eskalasi/group-solver',
            type: "POST",
            data: data,
            contentType: false,
            processData: false,
            beforeSend: function(data) {
                $("#tableGroupSolver tbody").html('<tr><td colspan="3" class="text-center">Loading</td></tr>')
                $('#loadingAnimation').addClass('lds-dual-ring');
            },
            success: function(data) {
                if($('#tableGroupSolver').length){
                    showGroupSolver(LayananId)
                }else{
                    $('#table').DataTable().ajax.url("{!! route('layanan.datatables') !!}?"+$('#form').serialize()+'&pending={{ request()->pending }}').load();
                }
                toastr.success(data.message)
                that.closest('tr').hide()
                $('#loadingAnimation').removeClass('lds-dual-ring');
            },
            error : function () {
                alert('Terjadi kesalahan, silakan reload');
                $('#loadingAnimation').removeClass('lds-dual-ring');
            }
        })
    });

    $(document).on("click",'.lookup-group-solver',function () {
        groupSolver = [];
        if($('#tableGroupSolver').length){
            LayananId = "{{ $data->layanan->Id ?? '' }}";
            $('#tableGroupSolver tbody tr').each(function() {
                groupSolver.push($(this).find(".groupSolver").html())
                groupSolver.join(',')
            });
            $('#group-solver-dt').DataTable().ajax.url("{!! route('setting.group-solver.datatables') !!}").load();
            $('#group-solver-modal').modal('toggle');
        } else {
            LayananId = $(this).data('layanan_id');
            $.ajax({
                url: '/eskalasi/group-solver/'+LayananId+'?getData=1',
                type: "GET",
                beforeSend: function(data) {
                    $('#loadingAnimation').addClass('lds-dual-ring');
                },
                success: function(data) {
                    groupSolver = data.toString().split(',')
                    $('#loadingAnimation').removeClass('lds-dual-ring');
                    $('#group-solver-dt').DataTable().ajax.url("{!! route('setting.group-solver.datatables') !!}").load();
                    $('#group-solver-modal').modal('toggle');
                },
                error : function () {
                    alert('Terjadi kesalahan, silakan reload');
                    $('#loadingAnimation').removeClass('lds-dual-ring');
                }
            })
        }
    });
    var solver = [],deleteSolver=[];
    $(document).on("click",'.lookup-solver',function () {
        groupSolver = [];
        solver = [];
        excludeGroup = $('#excludeGroup').prop('checked')?1:0;
        if($('#tableGroupSolver').length){
            LayananId = "{{ $data->layanan->Id ?? '' }}";
            $('#tableGroupSolver tbody tr').each(function() {
                groupSolver.push($(this).find(".groupSolver").html())
                groupSolver.join(',')
            });
            $('#tableSolver tbody tr').each(function() {
                solver.push($(this).find(".solver").html())
                solver.join(',')
            });
            $('#solver-dt').DataTable().ajax.url("{!! route('setting.solver.datatables') !!}?groupSolver="+groupSolver+'&excludeGroup='+excludeGroup+'&solver='+solver).load();
            $('#solver-modal').modal('toggle');
        } else {
            LayananId = $(this).data('layanan_id');

            $.ajax({
                url: '/eskalasi/group-solver/'+LayananId+'?getData=1',
                type: "GET",
                beforeSend: function(data) {
                    $('#loadingAnimation').addClass('lds-dual-ring');
                },
                success: function(data) {
                    groupSolver = data.toString().split(',')

                    $.ajax({
                        url: '/eskalasi/solver/'+LayananId+'?getData=1',
                        type: "GET",
                        beforeSend: function(data) {
                            $('#loadingAnimation').addClass('lds-dual-ring');
                        },
                        success: function(data) {
                            solver = data.toString().split(',')
                            $('#loadingAnimation').removeClass('lds-dual-ring');
                            $('#solver-dt').DataTable().ajax.url("{!! route('setting.solver.datatables') !!}?groupSolver="+groupSolver+'&excludeGroup='+excludeGroup+'&solver='+solver).load();
                            $('#solver-modal').modal('toggle');
                        },
                        error : function () {
                            alert('Terjadi kesalahan, silakan reload');
                            $('#loadingAnimation').removeClass('lds-dual-ring');
                        }
                    })
                },
                error : function () {
                    alert('Terjadi kesalahan, silakan reload');
                    $('#loadingAnimation').removeClass('lds-dual-ring');
                }
            })
        }
    });
    $(document).on("click",'.pilih-solver',function () {
        that = $(this);
        Nip = $(this).data('nip');
        var _token = $('meta[name="csrf-token"]').attr('content');
        data = new FormData();
        data.append('Nip', Nip);
        data.append('LayananId', LayananId);
        data.append('_token', _token);
        that = $(this);
        $.ajax({
            url: '/eskalasi/solver',
            type: "POST",
            data: data,
            contentType: false,
            processData: false,
            beforeSend: function(data) {
                $("#tableSolver tbody").html('<tr><td colspan="4" class="text-center">Loading</td></tr>')
                $('#loadingAnimation').addClass('lds-dual-ring');
            },
            success: function(data) {

                if($('#tableSolver').length){
                    showSolver(LayananId)
                }else{
                    $('#table').DataTable().ajax.url("{!! route('layanan.datatables') !!}?"+$('#form').serialize()+'&pending={{ request()->pending }}').load();
                }
                toastr.success(data.message)
                that.closest('tr').hide()
                $('#loadingAnimation').removeClass('lds-dual-ring');
            },
            error : function () {
                alert('Terjadi kesalahan, silakan reload');
                $('#loadingAnimation').removeClass('lds-dual-ring');
            }
        })
    });

    showGroupSolver = (LayananId, value = null) => {
        $.ajax({
            url: "{{ url('/eskalasi/group-solver/') }}/"+LayananId,
            type: "GET",
            beforeSend: function(data) {
                $("#tableGroupSolver tbody").html('<tr><td colspan="3" class="text-center">Loading</td></tr>')
            },
            success: function(data) {
                $("#tableGroupSolver tbody").html(data)
            },
            error: function() {
                alert('Terjadi kesalahan, silakan reload');
            }
        })
    }
    showSolver = (LayananId, value = null) => {
        $.ajax({
            url: "{{ url('/eskalasi/solver/') }}/"+LayananId,
            type: "GET",
            beforeSend: function(data) {
                $("#tableSolver tbody").html('<tr><td colspan="4" class="text-center">Loading</td></tr>')
            },
            success: function(data) {
                console.log('proses');
                $("#tableSolver tbody").html(data)
            },
            error: function() {
                alert('Terjadi kesalahan, silakan reload');
            }
        })
    }

    $(document).on("change",'.catatanSolver',function () {
        var _token = $('meta[name="csrf-token"]').attr('content');
        data = new FormData();
        data.append('Catatan', $(this).val());
        data.append('_token', _token);
        data.append('_method', 'patch');
        $.ajax({
            url: `/eskalasi/${$(this).data('id')}/solver`,
            type: "POST",
            data: data,
            contentType: false,
            processData: false,
            beforeSend: function(data) {
                $('#loadingAnimation').addClass('lds-dual-ring');
            },
            success: function(data) {
                toastr.success(data.message)
                $('#loadingAnimation').removeClass('lds-dual-ring');
            },
            error : function () {
                alert('Terjadi kesalahan, silakan reload');
                $('#loadingAnimation').removeClass('lds-dual-ring');
            }
        })
    })
</script>