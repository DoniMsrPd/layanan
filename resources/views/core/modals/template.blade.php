@push('scripts')

<style>
    table#template-dt tbody td {
        padding: 2px
    }
</style>
<div class="modal fade" id="template-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Daftar Template Penyelesaian</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- <span id="type"></span> -->
                <table class="table table-bordered table-lg table-v2 table-striped" id="template-dt" width="100%">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Template</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    var NipSelected;
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table1 = $('#template-dt').DataTable({
            processing: true,
            serverSide: true,
            deferLoading: 0,
            ajax: {
                method: 'POST',
                url : "{!! route('setting.template-penyelesaian.datatables') !!}",
            },
            columns: [
                { data: 'Nama', name: 'Nama' },
                { data: 'Template', name: 'Template' },
                { data: 'pilih', name: 'pilih' , searchable:false, orderable:false, className: "text-center" },
                { data: 'mobile', name: 'mobile' },
            ],
        });

        var dataArr = [];
        @if (isMobile())
            table1.column(0).visible(false);
            table1.column(1).visible(false);
            table1.column(2).visible(false);
            table1.column(3).visible(true);

        @else
            table1.column(0).visible(true);
            table1.column(1).visible(true);
            table1.column(2).visible(true);
            table1.column(3).visible(false);
        @endif
        $(document).on("click",'.lookup-template',function () {
            $('#template-dt').DataTable().ajax.url("{!! route('setting.template-penyelesaian.datatables') !!}").load();
            $('#template-modal').modal('toggle');
        });
    });
</script>

@endpush