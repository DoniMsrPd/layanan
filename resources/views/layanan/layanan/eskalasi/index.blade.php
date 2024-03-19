@extends('layouts/contentLayoutMaster')

@section('title', 'Layanan')

@section('content')
<style>
    .blink_me {
        animation: blinker 1s linear infinite;
    }

    @keyframes blinker {
        50% {
            opacity: 0;
        }
    }

    tr td p {
        margin: 0px
    }
</style>
<div class="card">
    <div class="card-body mb-2">
        Layanan <br>
        <span class="btn-group" role="group" style=" float: right;">
            @can('layanan.create-tiket')
            <a class="btn btn-sm btn-primary" style=" float: right;"
                href="{{ route('layanan.create-tiket') }}">Pembuatan Layanan</a>
            @endcan
            @can('layanan.create')
            <a class="btn btn-sm btn-success pl-1" style=" float: right;" href="{{ route('layanan.create') }}">Permintaan
                Layanan</a>
            @endcan
        </span>
    </div>
    @if(isTablet())
    <div class="pipeline-item" style="margin-bottom: 5px;">

        <div class="pi-body">
            <div class="pi-info">


                <form action="" method="get" id="form">
                    <div class="row">
                        <div class="col-sm-6">
                            @if(!pegawaiBiasa())
                            <div class="form-group row"><label class="col-form-label col-sm-4" for=""> Group
                                    Solver</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2 form-filters" multiple="true" id="groupSolver"
                                        name="groupSolver[]">
                                        @foreach ($data->groupSolver as $item)
                                        <option @if(is_array(request()->groupSolver) &&
                                            in_array($item->Id,request()->groupSolver) ||
                                            $item->Id==request()->groupSolver)
                                            selected @endif value="{{ $item->Id }}">{{
                                            $item->Kode }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if(request()->pending<>1)
                                <div class="form-group row"><label class="col-form-label col-sm-4" for="">
                                        Solver</label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2 form-filters" multiple="true" id="solver"
                                            name="solver[]">
                                            @foreach ($data->solver as $item)
                                            <option @if(is_array(request()->solver) &&
                                                in_array($item->Nip,request()->solver) ||
                                                $item->Nip==request()->solver) selected @endif value="{{ $item->Nip
                                                }}">{{
                                                $item->NmPeg }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                                @endif
                                <div class="form-group row"><label class="col-form-label col-sm-4" for=""> Status
                                        Layanan</label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2 form-filters" multiple="true"
                                            id="statusLayanan" name="statusLayanan[]">
                                            @foreach ($data->statusLayanan as $item)
                                            <option @if(is_array(request()->statusLayanan) &&
                                                in_array($item->Id,request()->statusLayanan) ||
                                                $item->Id==request()->statusLayanan)
                                                selected @endif value="{{ $item->Id }}">{{ $item->Nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row"><label class="col-form-label col-sm-4" for=""> Tgl
                                        Layanan</label>
                                    <div class="col-sm-4">
                                        <input type="text" id="TglStart" value="" name="tglStart"
                                            class="form-control datetimepick form-filters" placeholder="Tanggal Awal"
                                            value="{{ request()->tglStart ?? date('Y').'-01-01' }}">
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" id="TglEnd" value="" name="tglEnd"
                                            class="form-control datetimepick form-filters" placeholder="Tanggal Akhir"
                                            value="{{ request()->tglEnd ?? date('Y-m-d') . ' 23:59:59' }}">
                                    </div>
                                </div>
                                <div class="form-group row"><label class="col-form-label col-sm-4" for=""> </label>
                                    <div class="col-sm-8">
                                        <input value="{{ request()->q }}" placeholder="Cari Layanan ...." id="cari" autofocus="true"
                                            class="form-control form-control-sm rounded bright form-filters" name="q">
                                    </div>
                                </div>
                        </div>
                        @if(request()->pending<>1)
                            <div class="col-sm-6">

                                @if(!pegawaiBiasa())
                                <div class="form-group row"><label class="col-form-label col-sm-4" for=""> Prioritas
                                        Layanan</label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2 form-filters" multiple="true"
                                            id="prioritasLayanan" name="prioritasLayanan[]">
                                            @foreach ($data->prioritasLayanan as $item)
                                            <option @if(is_array(request()->prioritasLayanan) &&
                                                in_array(strtolower($item->Id),request()->prioritasLayanan) ||
                                                strtolower($item->Id)==request()->prioritasLayanan) selected @endif
                                                value="{{
                                                strtolower($item->Id) }}">{{ $item->Id }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row"><label class="col-form-label col-sm-4" for=""> ITSM</label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2 form-filters" multiple="true"
                                            id="serviceCatalog" name="serviceCatalog[]">
                                            <option @if(is_array(request()->serviceCatalog) &&
                                                in_array("undefined",request()->serviceCatalog) ||
                                                "undefined"==request()->serviceCatalog) selected @endif
                                                value="undefined">ITSM
                                                KOSONG</option>
                                            @foreach ($data->serviceCatalog as $item)
                                            <option @if(is_array(request()->serviceCatalog) &&
                                                in_array($item->Kode,request()->serviceCatalog) ||
                                                $item->Kode==request()->serviceCatalog) selected @endif value="{{
                                                $item->Kode }}">{{
                                                $item->Kode }} {{ $item->Nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row"><label class="col-form-label col-sm-4" for="">
                                        Tematik</label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2 form-filters" multiple="true" id="tematik"
                                            name="tematik[]">
                                            @foreach ($data->tematik as $item)
                                            <option @if(is_array(request()->tematik) &&
                                                in_array($item->Id,request()->tematik) ||
                                                $item->Id==request()->tematik) selected @endif value="{{ $item->Id
                                                }}">{{
                                                $item->Tema }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row"><label class="col-form-label col-sm-4" for=""> SLA</label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2 form-filters" multiple="true" id="sla"
                                            name="sla[]">
                                            <option @if(is_array(request()->sla) && in_array('melewati',request()->sla)
                                                ||
                                                'melewati'==request()->sla) selected @endif value="melewati">Melewati
                                            </option>
                                            <option @if(is_array(request()->sla) &&
                                                in_array('tidak_melewati',request()->sla) ||
                                                'tidak_melewati'==request()->sla) selected @endif
                                                value="tidak_melewati">Tidak Melewati</option>
                                        </select>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif
                    </div>

                    <input type="hidden" name="assignToMe" value="{{ request()->assignToMe }}">
                    <input type="hidden" name="updatedByMe" value="{{ request()->updatedByMe }}">
                    <input type="hidden" name="pending" value="{{ request()->pending }}">
                </form>
            </div>
        </div>
    </div>
    @else
    <form action="" id="form" class="form-inline justify-content-sm-end" style="margin-bottom: 5px;">
        <table width="100%">
            <tr>
                <td colspan="3">

                    <input value="{{ request()->q }}" placeholder="Cari Layanan ...." id="cari" autofocus="true"
                        class="form-control form-control-sm rounded bright form-filters" name="q">
                </td>
            </tr>
            <tr>
                <td style="width: 120px">
                    <input type="text" id="TglStart" value="" name="tglStart" class="form-control datetimepick form-filters"
                        placeholder="Tanggal Awal" value="{{ request()->tglStart ?? date('Y').'-01-01' }}">
                </td>
                <td>
                    <center>-</center>
                </td>
                <td style="width: 120px">
                    <input type="text" id="TglEnd" value="" name="tglEnd" class="form-control datetimepick form-filters"
                        placeholder="Tanggal Akhir" value="{{ request()->tglEnd ?? date('Y-m-d') . ' 23:59:59' }}">
                </td>
            </tr>
        </table>
    </form>
    @endif
    <div id="hasilpencarian">
        @foreach($data->layanan as $layanan)
        <div class="pipeline-item" style="margin-bottom: 5px;">
            <div class="pi-controls">
                @php
                $id = $layanan->ParentId ?? $layanan->Id;
                $url = route('layanan.eskalasi', $id) ;
                if (pegawaiBiasa() && !$layanan->NoTicket && !$layanan->ParentId) {
                $url = route('layanan.edit', $id);
                }
                $NoTicket = '<a style="color:blue; font-style: italic;font-size:11px" href="' . $url . '">' .
                    $layanan->NoTicket . '<br>' . strtoupper($layanan->NoTicketRandom) . '</a>';
                @endphp
                <span> {!! strip_tags(nl2br($NoTicket),"<p><br>") !!} </span>,
                <span style="  color:green; font-style: italic;font-size:11px">{{ pegawaiBiasa() ?
                    ToDmyHi($layanan->CreatedAt) : ToDmy($layanan->TglLayanan)
                    }}</span>
            </div>
            <div class="pi-body">
                <div class="pi-info">
                    <br>
                    <table width='100%' border="0">
                        <tbody>
                            <tr valign="top">
                                <td colspan="3"><span>{!! strip_tags(nl2br($layanan->PermintaanLayanan),"<p><br>") !!}</span></td>
                            </tr>
                            <tr valign="top">
                                @php
                                $statusLayanan = $layanan->NamaStatusLayanan?? '-';
                                if(pegawaiBiasa()&&isset($layanan->StatusLayanan) &&$layanan->StatusLayanan==6){
                                $statusLayanan = "<span class='blink_me'>{$layanan->NamaStatusLayanan} <i
                                        class='os-icon os-icon-mail-12'></i></span>";

                                }
                                @endphp
                                <td width="80px">Status</td>
                                <td>:</td>
                                <td><span class="pi-sub">{{ $statusLayanan }}</span></td>
                            </tr>
                            <tr valign="top">
                                <td>Updated At</td>
                                <td>:</td>
                                <td><span class="pi-sub">{{ $layanan->UpdatedAt }} </span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach
        <div class="load-more-tickets" style="width:50%; margin: 0 auto;">
            @if(isTablet())
            {{
            $data->layanan->appends(request()->all())->links()
            }}
            @else
                @if ($data->layanan->hasPages())
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($data->layanan->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true" aria-label="« Previous">
                            <span class="page-link" aria-hidden="true">‹</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $data->layanan->appends(request()->all())->previousPageUrl() }}" rel="prev" aria-label="« Previous">‹</a>
                        </li>
                    @endif


                    <li class="page-item disabled"><a class="page-link" href="#">{{ "Page " . $data->layanan->currentPage() . "  of  " . $data->layanan->lastPage() }}</a></li>



                    {{-- Next Page Link --}}
                    @if ($data->layanan->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $data->layanan->appends(request()->all())->nextPageUrl() }}" rel="next">></a>
                        </li>
                    @else
                    <li class="page-item disabled" aria-disabled="true" aria-label="Next »">
                        <span class="page-link" aria-hidden="true">›</span>
                    </li>
                    @endif
                </ul>
            @endif
            @endif
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body mb-2">
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(function() {
        $('#TglStart').datepicker({ dateFormat: 'dd M yy'}).datepicker("setDate", new Date("{{ request()->tglStart ?? date('Y').'-01-01'}}"));
        $('#TglEnd').datepicker({ dateFormat: 'dd M yy'}).datepicker("setDate", new Date("{{ request()->tglEnd ?? date('Y-m-d') . ' 23:59:59' }}"));
        var minlength = 3;
        var searchRequest = null;
        $('.form-filters').on('change', function() {
            value = $('#cari').val();
            TglStart = $('#TglStart').val();
            TglEnd = $('#TglEnd').val();
            v_url = "{{ route('layanan.index') }}";
            if (value.length >= minlength || value.length == 0) {
                $.ajax({
                    type: "GET",
                    url: v_url,
                    data: $(".form-filters").serialize(),
                    cache: false,
                    dataType: "html",
                    success: function(data) {
                        console.log(data)
                        $('#hasilpencarian').html(data);
                    },
                    error: function(e) {

                        swal({
                            type: 'warning',
                            text: "sistem error",
                            timer: 1500,
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });

                    }
                });
            }
        });
    })
</script>
@include('core._script-delete')
@endpush