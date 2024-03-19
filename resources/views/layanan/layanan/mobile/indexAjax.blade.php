@foreach($data->layanan as $layanan)
<div class="card">
    <div class="card-body mb-0">
        <div class="pi-controls">
            @php
            $id = $layanan->ParentId ?? $layanan->Id;
            $url = route('layanan.eskalasi', $id) ;
            if (pegawaiBiasa() && !$layanan->NoTicket && !$layanan->ParentId) {
            $url = route('layanan.edit', $id);
            }
            $NoTicket = '<a style="color:blue; font-style: italic;font-size:11px" href="' . $url . '">' .

                strtoupper($layanan->NoTicketRandom),' ' .$layanan->NoTicket. '</a>';
            @endphp
        </div>
        <table width="100%">
            <tr>
                <td> {!! $NoTicket !!} </td>
                <td style="float: right">
                    <span style="  color:chartreuse; font-style: italic;font-size:11px">{{ pegawaiBiasa() ?
                        ToDmyHi($layanan->CreatedAt) : ToDmy($layanan->TglLayanan) }}</span>
                </td>
            </tr>
        </table>
        <div class="pi-body">
            <div class="pi-info">
                <br>
                <table width='100%' border="0">
                    <tbody>
                        <tr valign="top">
                            <td colspan="3"><span>{!! strip_tags(nl2br($layanan->PermintaanLayanan),"<p><br>")
                                        !!}</span></td>
                        </tr>
                        <tr valign="top">
                            @php
                            $statusLayanan = $layanan->NamaStatusLayanan?? '-';
                            if(pegawaiBiasa()&&$layanan->StatusLayanan==6){
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
</div>
@endforeach
<div class="load-more-tickets text-center" style="width:50%; margin: 0 auto;">

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
            <a class="page-link" href="{{ $data->layanan->appends(request()->all())->previousPageUrl() }}" rel="prev"
                aria-label="« Previous">‹</a>
        </li>
        @endif


        <li class="page-item disabled"><a class="page-link" href="#">{{ "Page " . $data->layanan->currentPage() . " of "
                . $data->layanan->lastPage() }}</a></li>



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