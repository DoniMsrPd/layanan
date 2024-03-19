@foreach ($data->MstUnitOrgLayananOwner as $item)
<div class="col-lg-3 col-6 mb-0">
    <div class="card">
        <div class="card-body text-left">
            <a class="element-box el-tablo "
                href="{{ route('layanan.index') }}?KdUnitOrgOwnerLayanan={{ $item->KdUnitOrgOwnerLayanan }}"
                style="cursor: pointer;" data-id="2">
                <small style="float: left" class="label text-primary"> {{ $item->NmUnitOrgOwnerLayanan }} </small>
                <small style="float: right">
                    @if ($item->PathIcon!=null)
                    <img src="{{ url('core/'.$item->PathIcon) }}" alt="" width="20px" height="20px">
                    @endif
                </small>
            </a>
            <br>
            <table class="table">
                <thead>
                    <tr>
                        <td style="padding: 0px" class="text-left">Status</td>
                        <td style="padding: 0px" class="text-center">Jumlah</td>
                    </tr>
                </thead>
                @foreach ($data->tickets->where('KdUnitOrgOwnerLayanan',$item->KdUnitOrgOwnerLayanan) as $ticket)
                <tr>
                    <td style="padding: 0px">{{ $ticket->Name }}</td>
                    <td style="padding: 0px" class="text-center">

                        <a href="{{ route('layanan.index') }}?KdUnitOrgOwnerLayanan={{ $item->KdUnitOrgOwnerLayanan }}&statusLayanan[]={{ $ticket->StatusLayanan }}">{{ $ticket->Jumlah }}</a>

                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endforeach