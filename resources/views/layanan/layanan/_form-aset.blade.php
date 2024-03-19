<div class="modal fade" id="modalFormAsetTI" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" id="modal-title">
                Form Aset
            </div>
            <div class="modal-body">
                <form method="POST" action="#" id="formAsetTI" enctype="multipart/form-data">
                    @include('setting.aset._form')
                    <div class="text-center form-buttons-w">

                        <button type="button" class="btn btn-default btn-sm tambahAset">Batal</button>
                        <button class="btn btn-primary btn-sm" type="submit">
                            Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>