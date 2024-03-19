<script>


    $(function() {

        @if (request()->TambahInformasi)
            $(".add-tl").click()
        @endif
        $(document).on("change",'.layananKhususCheckbox',function () {
            form = $(this).data('form')
            console.log(form);
            if($(this).is(":checked")){
                $('#'+form).show()
            } else {
                $('#'+form).hide()
            }
        });
    })
    let aset;
    $(document).on("click",'.lookup-aset',function () {
        tableAset!=undefined ? tableAset.destroy():''
        column = [
            {
                    data: 'Id',
                    className: "text-center" ,
                    width:'5%',
                    searchable:false
                },
                // { data: 'Id', name: 'Id' },
                {
                    width:'5%',
                    data: 'NoIkn1',
                    name: 'NoIkn1' ,
                    render: function(data, type, row, meta) {
                        return `${row.NoIkn1??row.no_ikn1} <br> ${row.NoIkn2??row.no_ikn2}`
                    },
                    searchable:false
                },
                {
                    data: 'sn',
                    width:'5%',
                    searchable:false
                },
                {
                    data: 'aset',
                    width:'65%',
                    searchable:false
                },
                {
                    width:'10%',
                    data: 'NipPengguna',
                    render: function(data, type, row, meta) {
                        return `${row.NipPengguna??(row.pengguna?.Nip ?? '-')} <br> ${row.pengguna?.NmPeg??'-'}`
                    }  ,
                    searchable:false
                },
                { data: 'pilih', name: 'pilih' , searchable:false, orderable:false, className: "text-center" ,width:'10%'},
                {
                    data: 'pengguna.NmPeg',
                    render: function(data, type, row, meta) {
                        return `${row.pengguna?.NmPeg??'-'}`
                    }, searchable:true ,visible:false
                },
                { data: 'SerialNumber', name: 'SerialNumber',visible:false,searchable:true },
                { data: 'NipPengguna', name: 'NipPengguna',visible:false,searchable:true },
                { data: 'NoIkn1', name: 'NoIkn1',visible:false,searchable:true },
                { data: 'NoIkn2', name: 'NoIkn2',visible:false,searchable:true }
        ]
        loadDatatableAset(column);
        $('#aset-dt').DataTable().ajax.url("{!! route('setting.aset.datatables') !!}").load();
        $('#aset-modal').modal('toggle');
        aset = [];
        $('#tableAset tbody tr').each(function() {
            aset.push($(this).find(".aset").html())
            aset.join(',')
        });
    });
    $(document).on("click",'.pilih-aset',function () {
        Id = $(this).data('id');
        Aset = $(this).data('aset');
        NoIKN = $(this).data('no_ikn');
        NoSerial = $(this).data('no_serial');
        IsAsetSMA = $(this).data('is_aset_sma');
        countAset = $('#tableAset tbody tr').length;
        countAset = countAset+1
        form = `
            <form>
                <div class="form-group row mb-3"><label class="col-form-label col-sm-3" for="">
                        Fisik</label>
                    <div class="col-sm-8"><textarea class="form-control ckeditor" rows="2" name="fisik[]"></textarea></div>
                </div>
                <div class="form-group row mb-3"><label class="col-form-label col-sm-3" for="">
                        Kelengkapan</label>
                    <div class="col-sm-8"><textarea class="form-control ckeditor" rows="2" name="kelengkapan[]"></textarea></div>
                </div>
                <div class="form-group row mb-3"><label class="col-form-label col-sm-3" for="">Data</label>
                    <div class="col-sm-8"><textarea class="form-control ckeditor" rows="2" name="data[]"></textarea></div>
                </div>
                <div class="form-group row mb-3"><label class="col-form-label col-sm-3" for="">No Box</label>
                    <div class="col-sm-8"><input class="form-control" placeholder="No Box" type="number" name="noBox[]"></div>
                </div>
            </form>`
        var tablePic = "<tr class='aset' id='row"+countAset+"'>\
                            <input type='hidden' name='layananAsetId[]' value=''/>\
                            <input type='hidden' name='asetId[]' value='" + Id + "'/>\
                            <input type='hidden' name='isAsetSMA[]' value='" + IsAsetSMA + "'/>\
                            <td class='aset' style='display:none'>" + Id + "</td>\
                            <td style='font-size:0.8rem ; vertical-align:top' colspan='4'> No IKN: " + NoIKN +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SN: "+ NoSerial+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Aset : "+ Aset+ " <br><br> "+form+"</td>\
                            <td style='font-size:0.8rem;vertical-align:top'>Keterangan Aset Lain <br><textarea class='form-control' rows='2' id='keteranganAset"+countAset+"' name='keteranganAset[]'></textarea></td>\
                            <td class='text-center ' style='vertical-align:top'><a style='padding:3px 3px;margin:0px' class= 'btn btn-danger btn-sm remove_aset' data-id='" + Id + "'  href='javascript:void(0)' title='Hapus'><svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path></svg></a></td>\
                        </tr>";
        aset.push(id)
        $("#tableAset tbody").append(tablePic)
        CKEDITOR.replace( 'keteranganAset'+countAset );
        $(this).closest('tr').hide('slow')
    });
    $(document).on('click', '.remove_aset', function(){
        $(this).closest('tr').remove();
    });
    let persediaan;
    $(document).on("click",'.lookup-persediaan',function () {
        $('#persediaan-dt').DataTable().ajax.url("{!! route('setting.persediaan.datatables') !!}").load();
        $('#persediaan-modal').modal('toggle');
        persediaan = [];
        $('#tablePersediaan tbody tr').each(function() {
            persediaan.push($(this).find(".persediaan").html())
            persediaan.join(',')
        });
    });
    $(document).on("click",'.pilih-persediaan',function () {
        Id = $(this).data('id');
        KdBrg = $(this).data('kd_brg');
        NmBrg = $(this).data('nm_brg');
        NmBrgLengkap = $(this).data('nm_brg_lengkap');
        Nama = KdBrg+'<br>'+NmBrg+'<br>'+NmBrgLengkap
        countPersediaan = $('#tablePersediaan tbody tr').length;
        countPersediaan = countPersediaan+1
        var tablePic = "<tr class='persediaan' id='row"+countPersediaan+"'>\
                            <input type='hidden' name='persediaanId[]' value=''/>\
                            <input type='hidden' name='mstPersediaanId[]' value='" + Id + "'/>\
                            <td style='font-size:0.8rem;vertical-align:top'>" + countPersediaan + "</td><td class='persediaan' style='display:none'>" + Id + "</td>\
                            <td style='font-size:0.8rem;vertical-align:top'>" + Nama + "</td>\
                            <td style='font-size:0.8rem;vertical-align:top'><input type='number' name='qtyPersediaan[]' value='' class='form-control'/></td>\
                            <td style='font-size:0.8rem'><textarea class='form-control' rows='2' name='keteranganPersediaan[]' id='keteranganPersediaan"+countPersediaan+"'></textarea></td>\
                            <td class='text-center'><a style='padding:3px 3px;margin:0px' class= 'btn btn-danger btn-sm remove_persediaan' data-id='" + Id + "'  href='javascript:void(0)' title='Hapus'><svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path></svg></a></td>\
                        </tr>";
        persediaan.push(id)
        $("#tablePersediaan tbody").append(tablePic)
        CKEDITOR.replace( 'keteranganPersediaan'+countPersediaan );
        $(this).closest('tr').hide('slow')
    });
    $(document).on('click', '.remove_persediaan', function(){
        $(this).closest('tr').remove();
    });

    let peminjaman;
    $(document).on("click",'.lookup-peminjaman',function () {
        $('#peminjaman-dt').DataTable().ajax.url("{!! route('setting.aset.datatables') !!}?peminjaman=1").load();
        $('#peminjaman-modal').modal('toggle');
        peminjaman = [];
        $('#tablePeminjaman tbody tr').each(function() {
            peminjaman.push($(this).find(".peminjaman").html())
            peminjaman.join(',')
        });
    });
    $(document).on("click",'.pilih-peminjaman',function () {
        Id = $(this).data('id');
        Aset = $(this).data('aset');
        NoIKN = $(this).data('no_ikn');
        NoSerial = $(this).data('no_serial');
        countPeminjaman = $('#tablePeminjaman tbody tr').length;
        countPeminjaman = countPeminjaman+1
        var tablePeminjaman = "<tr id='row"+countPeminjaman+"'>\
                            <input type='hidden' name='peminjamanId[]' value=''/>\
                            <input type='hidden' name='peminjamanDetailId[]' value=''/>\
                            <input type='hidden' name='asetLayananId[]' value='" + Id + "'/>\
                            <td style='font-size:0.8rem'>" + countPeminjaman + "</td><td class='peminjaman' style='display:none'>" + Id + "</td>\
                            <td style='font-size:0.8rem'>" + NoIKN + "</td>\
                            <td style='font-size:0.8rem'>" + NoSerial + "</td>\
                            <td style='font-size:0.8rem'>" + Aset + "</td>\
                            <td style='font-size:0.8rem'><textarea class='form-control' rows='2' id='keteranganPeminjaman"+countPeminjaman+"' name='keteranganPeminjaman[]'></textarea></td>\
                            <td class='text-center'><a style='padding:3px 3px;margin:0px' class= 'btn btn-danger btn-sm remove_aset' data-id='" + Id + "'  href='javascript:void(0)' title='Hapus'><svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path></svg></a></td>\
                        </tr>";
        peminjaman.push(id)
        $("#tablePeminjaman tbody").append(tablePeminjaman)
        CKEDITOR.replace( 'keteranganPeminjaman'+countPeminjaman );
        $(this).closest('tr').hide('slow')
    });
    let persediaanId;
    $(document).on("click",'.mappingPersediaan',function () {
        persediaanId  = $(this).data('id')
        var LayananId = $('#id').val();
        $('#layanan-aset-dt').DataTable().ajax.url("{!! route('layanan.layanan-aset.datatables') !!}?LayananId="+LayananId).load();
        $('#layanan-aset-modal').modal('toggle');
    });
    $(document).on("click",'.pilih-layanan-aset',function () {
        $(this).closest('tr').hide('slow')
        var LayananId = $('#id').val();
        aset_layanan_id = $(this).data('aset_layanan_id');
        aset_sma_id = $(this).data('aset_sma_id');
        var _token = $('meta[name="csrf-token"]').attr('content');
        data = new FormData();
        data.append('_token', _token);
        data.append('aset_layanan_id',aset_layanan_id);
        data.append('aset_sma_id',aset_sma_id);
        $.ajax({
            url: '/layanan-aset/'+persediaanId,
            type: "POST",
            data: data,
            contentType: false,
            processData: false,
            beforeSend: function (data){
                $('#loadingAnimation').addClass('lds-dual-ring');
            },
            success: function (data) {
                toastr.info(data.message);
                $('#layanan-aset-modal').modal('toggle');
                loadDataTL(LayananId)
                $('#loadingAnimation').removeClass('lds-dual-ring');

            },
            error : function () {
                alert('Terjadi kesalahan, silakan reload');
            }
        })
    });
    $(document).on('click', '.add-tl', function(){
        @if ($data->layanan && $data->layanan->StatusLayanan==6)
        $('#TL-RefStatusLayanan').val('3')
        $('#TL-RefStatusLayanan').select2().trigger('change');
        @endif
        $('.layananKhususCheckbox').prop('checked', false)
        $('.layananKhususCheckbox').prop('disabled', false)
        $('#persediaanForm').hide()
        $('#perbaikanForm').hide()
        $('#peminjamanForm').hide()
        $("#tablePersediaan tbody").html(null)
        $("#tableAset tbody").html(null)
        $("#tablePeminjaman tbody").html(null)
        // $('#TL-RefStatusLayanan').select2().trigger('change');
        CKEDITOR.instances.TLKeterangan.setData(null)
        $('#TL-FileAttachment').val(null)
        $('.tracker-editor').show();
        $('.tlcontent').hide();
        $('.containter-footer').hide();
        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        $('.save-tl').data('url',$(this).data('url'))
        $('.save-tl').data('method',$(this).data('method'))
    })
    $(document).on('click', '.cancel-tl', function(){
        $('.tracker-editor').hide();
        $('.tlcontent').show();
        $('.containter-footer').show();
    })
    $(document).on('click', '.save-tl', function(){
        var LayananId = $('#id').val();
        var Keterangan = CKEDITOR.instances.TLKeterangan.getData();
        var StatusLayanan = $('#TL-RefStatusLayanan').val();
        var urlSave = $(this).data('url');
        var method = $(this).data('method');
        var _token = $('meta[name="csrf-token"]').attr('content');
        data = new FormData();
        data.append('Keterangan', Keterangan);
        data.append('StatusLayanan', StatusLayanan);
        data.append('LayananId', LayananId);
        data.append('_method', method);
        $.each($("#TL-FileAttachment")[0].files, function(i, file) {
            data.append('FileAttachment[]', file);
        });
        $("input[name='persediaanId[]']").map(function(){
            data.append('persediaanId[]', $(this).val());
        }).get()
        $('textarea[name^="keteranganPersediaan"]').each(function(i, v) {
            data.append('keteranganPersediaan[]', CKEDITOR.instances[$(this).attr('id')].getData());
        });
        $("input[name='mstPersediaanId[]']").map(function(){
            data.append('mstPersediaanId[]', $(this).val());
        }).get()
        $("input[name='qtyPersediaan[]']").map(function(){
            data.append('qtyPersediaan[]', $(this).val());
        }).get()


        $("input[name='layananAsetId[]']").map(function(){
            data.append('layananAsetId[]', $(this).val());
        }).get()
        $('input[name^="asetId"]').each(function(i, v) {
            data.append('asetId[]', $(this).val());
        });
        $('textarea[name="keteranganAset[]"]').each(function(i, v) {
            data.append('keteranganAset[]', CKEDITOR.instances[$(this).attr('id')].getData());
        });
        $("input[name='isAsetSMA[]']").map(function(){
            data.append('isAsetSMA[]', $(this).val());
        }).get()
        $("textarea[name='fisik[]']").map(function(){
            data.append('fisik[]', $(this).val());
        }).get()
        $("textarea[name='kelengkapan[]']").map(function(){
            data.append('kelengkapan[]', $(this).val());
        }).get()
        $("textarea[name='data[]']").map(function(){
            data.append('data[]', $(this).val());
        }).get()
        $("input[name='noBox[]']").map(function(){
            data.append('noBox[]', $(this).val());
        }).get()


        $("input[name='peminjamanId[]']").map(function(){
            data.append('peminjamanId[]', $(this).val());
        }).get()
        $('input[name^="peminjamanDetailId"]').each(function(i, v) {
            data.append('peminjamanDetailId[]', $(this).val());
        });
        $('input[name^="asetLayananId"]').each(function(i, v) {
            data.append('asetLayananId[]', $(this).val());
        });
        $('textarea[name^="keteranganPeminjaman"]').each(function(i, v) {
            data.append('keteranganPeminjaman[]', CKEDITOR.instances[$(this).attr('id')].getData());
        });
        data.append('_token', _token);
        $.ajax({
            url: urlSave,
            type: "POST",
            data: data,
            contentType: false,
            processData: false,
            beforeSend: function (data){
                $('#loadingAnimation').addClass('lds-dual-ring');
            },
            success: function (data) {
                toastr.info(data.message);
                $('.tracker-editor').hide();
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                // loadDataLog(data.PenugasanId);
                $('#loadingAnimation').removeClass('lds-dual-ring');
                $('.tracker-editor').hide();
                $('.tlcontent').show();
                $('.containter-footer').show();
                $('#StatusHeader').html($("#TL-RefStatusLayanan option:selected").text());
                loadDataTL(LayananId)

            },
            error : function () {
                alert('Terjadi kesalahan, silakan reload');
            }
        })
    })
    $(document).on('click', '.edit-tl', function(){

        $('.title-form-tl').html('Edit Tindak Lanjut')
        $('.save-tl').data('url',$(this).data('url'))
        $('.save-tl').data('method',$(this).data('method'))
        $id = $(this).data('id');
        $this = $(this);
        $.ajax({
            url: "{{ url('/layanan-tl') }}/" + $id,
            type: "GET",
            beforeSend: function (data){
                $('#loadingAnimation').addClass('lds-dual-ring');
            },
            success : function (data){
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                CKEDITOR.instances.TLKeterangan.setData(data.data.Keterangan)
                $('#TL-RefStatusLayanan').val(data.data.StatusLayanan);
                $('#TL-RefStatusLayanan').select2().trigger('change');
                $('#TL-FileAttachment').val(null)
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                // loadDataLog(data.PenugasanId);
                $('#loadingAnimation').removeClass('lds-dual-ring');
                $('.tracker-editor').show();
                $('.tlcontent').hide();
                $('.containter-footer').hide();
                let persediaan = data.data.layanan_persediaan
                if(persediaan.length>0){
                    $('.layananKhususCheckbox[data-form="persediaanForm"]').prop('checked', true)
                    $('.layananKhususCheckbox[data-form="persediaanForm"]').prop('disabled', true)
                    $('#persediaanForm').show()
                }
                $("#tablePersediaan tbody").html(null)
                for (const property in persediaan) {
                    let countPersediaan = parseInt(property)+parseInt(1);
                    let Id = persediaan[property].Id
                    let MstPersediaanId = persediaan[property].MstPersediaanId
                    let Keterangan = persediaan[property].Keterangan
                    let Qty = persediaan[property].Qty
                    let KdBrg = persediaan[property].mst_persediaan.KdBrg
                    let NmBrg = persediaan[property].mst_persediaan.NmBrg
                    let NmBrgLengkap = persediaan[property].mst_persediaan.NmBrgLengkap
                    let Nama = KdBrg+'<br>'+NmBrg+'<br>'+NmBrgLengkap
                    var tablePic = "<tr class='persediaan' id='row"+countPersediaan+"'>\
                                        <input type='hidden' name='persediaanId[]' value='" + Id + "'/>\
                                        <input type='hidden' name='mstPersediaanId[]' value='" + MstPersediaanId + "'/>\
                                        <td style='font-size:0.8rem;vertical-align:top'>" + countPersediaan + "</td><td class='persediaan' style='display:none'>" + MstPersediaanId + "</td>\
                                        <td style='font-size:0.8rem;vertical-align:top'>" + Nama + "</td>\
                                        <td style='font-size:0.8rem;vertical-align:top'><input type='number' name='qtyPersediaan[]' value='"+Qty+"' class='form-control'/></td>\
                                        <td style='font-size:0.8rem'><textarea class='form-control' rows='2' id='keteranganPersediaan"+countPersediaan+"' name='keteranganPersediaan[]'></textarea></td>\
                                        <td class='text-center'><a style='padding:3px 3px;margin:0px' class= 'btn btn-danger btn-sm deleteData' data-url='/layanan-tl/persediaan/" + Id + "' data-id='" + Id + "' data-title='" + KdBrg + "'  href='javascript:void(0)' title='Hapus'><svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path></svg></a></td>\
                                    </tr>";
                    $("#tablePersediaan tbody").append(tablePic)
                    CKEDITOR.replace( 'keteranganPersediaan'+countPersediaan );
                    CKEDITOR.instances['keteranganPersediaan'+countPersediaan ].setData(Keterangan)
                }

                let aset = data.data.layanan_aset
                if(aset.length>0){
                    $('.layananKhususCheckbox[data-form="perbaikanForm"]').prop('checked', true)
                    $('.layananKhususCheckbox[data-form="perbaikanForm"]').prop('disabled', true)
                    $('#perbaikanForm').show()
                }
                $("#tableAset tbody").html(null)
                for (const property in aset) {
                    form = `
                        <form>
                            <div class="form-group row mb-3"><label class="col-form-label col-sm-3" for="">
                                    Fisik</label>
                                <div class="col-sm-8"><textarea class="form-control ckeditor" rows="2" name="fisik[]">${aset[property].Fisik}</textarea></div>
                            </div>
                            <div class="form-group row mb-3"><label class="col-form-label col-sm-3" for="">
                                    Kelengkapan</label>
                                <div class="col-sm-8"><textarea class="form-control ckeditor" rows="2" name="kelengkapan[]">${aset[property].Kelengkapan}</textarea></div>
                            </div>
                            <div class="form-group row mb-3"><label class="col-form-label col-sm-3" for="">Data</label>
                                <div class="col-sm-8"><textarea class="form-control ckeditor" rows="2" name="data[]">${aset[property].Data}</textarea></div>
                            </div>
                            <div class="form-group row mb-3"><label class="col-form-label col-sm-3" for="">No Box</label>
                                <div class="col-sm-8"><input class="form-control" placeholder="No Box" type="number" name="noBox[]" value="${aset[property].NoBox}"></div>
                            </div>
                        </form>`
                    let countAset = parseInt(property)+parseInt(1);
                    let Id = aset[property].Id
                    let AsetId = aset[property].AsetLayananId ?? aset[property].AsetSMAId
                    let IsAsetSMA = aset[property].AsetLayananId? 0:1;
                    let Keterangan = aset[property].Keterangan
                    let NomorIKN = aset[property].NomorIKN
                    let SN = aset[property].SN
                    let Aset = aset[property].AsetLayananId? `${aset[property].aset_layanan.JenisAset} ${aset[property].aset_layanan.TypeAset}  ${aset[property].aset_layanan.Nama}`:`${aset[property].aset_s_m_a.nm_brg}  ${aset[property].aset_s_m_a.nm_lgkp_brg}`;
                    var tablePic = "<tr class='aset' id='row"+countAset+"'>\
                                        <input type='hidden' name='layananAsetId[]' value='"+Id+"'/>\
                                        <input type='hidden' name='asetId[]' value='" + AsetId + "'/>\
                                        <input type='hidden' name='isAsetSMA[]' value='" + IsAsetSMA + "'/>\
                                        <td class='aset' style='display:none'>" + AsetId + "</td>\
                                        <td style='font-size:0.8rem ; vertical-align:top' colspan='4'>No IKN : " + NomorIKN +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SN : "+ SN+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Aset : "+ Aset+ " <br><br> "+form+"</td>\
                                        <td style='font-size:0.8rem'>Keterangan Aset Lain <br><textarea class='form-control' rows='2' id='keteranganAset"+countAset+"' name='keteranganAset[]'></textarea></td>\
                                        <td class='text-center'><a style='padding:3px 3px;margin:0px' class= 'btn btn-danger btn-sm deleteData' data-url='/layanan-tl/aset/" + Id + "' data-id='" + Id + "' data-title='" + Aset + "'  href='javascript:void(0)' title='Hapus'><svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path></svg></a></td>\
                                    </tr>";
                    $("#tableAset tbody").append(tablePic)
                    CKEDITOR.replace( 'keteranganAset'+countAset );
                    CKEDITOR.instances['keteranganAset'+countAset ].setData(Keterangan)
                }

                let peminjamanDetail = data.data.layanan_peminjaman.peminjaman_detail
                console.log(peminjamanDetail);
                if(peminjamanDetail.length>0){
                    $('.layananKhususCheckbox[data-form="peminjamanForm"]').prop('checked', true)
                    $('.layananKhususCheckbox[data-form="peminjamanForm"]').prop('disabled', true)
                    $('#peminjamanForm').show()
                }
                $("#tablePeminjaman tbody").html(null)
                for (const property in peminjamanDetail) {
                    let count = parseInt(property)+parseInt(1);
                    let PeminjamanId = peminjamanDetail[property].PeminjamanId
                    let Id = peminjamanDetail[property].Id
                    let AsetLayananId = peminjamanDetail[property].AsetLayananId
                    let KeteranganPeminjaman = peminjamanDetail[property].KeteranganPeminjaman
                    let NomorIKN = peminjamanDetail[property].NomorIKN
                    let SN = peminjamanDetail[property].SN ??''
                    let NamaBarang = peminjamanDetail[property].NamaBarang
                    var tablePic = "<tr class='aset' id='row"+count+"'>\
                                        <input type='hidden' name='peminjamanId[]' value='"+PeminjamanId+"'/>\
                                        <input type='hidden' name='peminjamanDetailId[]' value='" + Id + "'/>\
                                        <input type='hidden' name='asetLayananId[]' value='" + AsetLayananId + "'/>\
                                        <td style='font-size:0.8rem'>" + count + "</td><td class='peminjaman' style='display:none'>" + AsetLayananId + "</td>\
                                        <td style='font-size:0.8rem'>" + NomorIKN + "</td>\
                                        <td style='font-size:0.8rem'>" + SN + "</td>\
                                        <td style='font-size:0.8rem'>" + NamaBarang + "</td>\
                                        <td style='font-size:0.8rem'><textarea class='form-control' rows='2' id='keteranganPeminjaman"+count+"' name='keteranganPeminjaman[]'></textarea></td>\
                                        <td class='text-center'><a style='padding:3px 3px;margin:0px' class= 'btn btn-danger btn-sm deleteData' data-url='/layanan-tl/peminjaman-detail/" + Id + "' data-id='" + Id + "' data-title='" + NamaBarang + "'  href='javascript:void(0)' title='Hapus'><svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path></svg></a></td>\
                                    </tr>";
                    $("#tablePeminjaman tbody").append(tablePic)
                    CKEDITOR.replace( 'keteranganPeminjaman'+count );
                    CKEDITOR.instances['keteranganPeminjaman'+count ].setData(KeteranganPeminjaman)
                }
            }
        })
    })
    function loadDataTL(LayananId,idTL = null){
        //onlyshow
        if("{{ $data->method ?? '' }}" == 'PATCH'){
            $.ajax({
                url: '/layanan-tl?LayananId=' + LayananId+'&merge={{ request()->merge }}',
                type: 'GET',
                // cache: false,
                // dataType: 'html',
                beforeSend: function(){
                    // $('#log-content').html( '<div class="m-loader m-loader--brand" style="width: 30px; display: inline-block;"></div> Loading . . .')
                },
                success: function(data){
                    $('#log-content').html(data)
                    // var element = $(document)
                    // if(element) {
                    //     $("html, body").animate({ scrollTop: element.offset().top - 60 }, "slow");
                    // }
                },
                error: function(){
                    alert('Terjadi kesalahan, silahkan reload')
                }
            })
        }
    }

    $(document).on("click",'.pilih-template',function () {

        CKEDITOR.instances.TLKeterangan.setData($(this).data('template'))
        $('#template-modal').modal('toggle');
    })
</script>