@extends('layouts/contentLayoutMaster')

@section('title', 'Dashboard')

@section('vendor-style')
{{-- vendor css files --}}
<link rel="stylesheet" href="{{ asset(mix('vendors/css/charts/apexcharts.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
@endsection
@section('page-style')
{{-- Page css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/dashboard-ecommerce.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/charts/chart-apex.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">
<style>
  .value {
    font-size: 2.43rem;
    font-weight: 500;
    font-family: "Avenir Next W01", "Proxima Nova W01", "Rubik", -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    letter-spacing: 1px;
    line-height: 1.2;
    display: inline-block;
    vertical-align: middle;
  }

  .label {
    display: block;
    font-size: 0.63rem;
    text-transform: uppercase;
    color: rgba(0, 0, 0, 0.4);
    letter-spacing: 1px;
  }

  @media (max-width:961px) {}
</style>

@endsection

@section('content')
<div class="row">
  <div class="col-md-6 mb-1">
    <h5 class="pb-1 mb-1">
      Dashboard
    </h5>
  </div>
  <div class="col-md-2 mb-1">
    <input class="form-control filters flatpickr" placeholder="Tanggal Start" value="" name="tglStart" id="tglStart">
  </div>
  <div class="col-md-2 mb-1">
    <input class="form-control filters mr-3 flatpickr" placeholder="Tanggal End" value="" name="tglEnd" id="tglEnd">
  </div>
  <div class="col-md-2 mb-1">
    <select class="form-control select2 filters" multiple="true" id="statusLayanan" name="statusLayanan[]">
      @foreach ($data->statusLayanan as $item)
      <option value="{{ $item->Id }}">{{ $item->Nama }}</option>
      @endforeach
    </select>
  </div>
</div>
<div class="row" id="statusLayananData"></div>
<div class="row">

  <div class="col-sm-6 col-xxxl-6">
    <div class="card mb-4">
      <div class="card-header header-elements">
        <span class=" me-2">Tabel Layanan Berdasarkan ITSM</span>
        <div class="card-header-elements ms-auto">
          <span>
          </span>
        </div>
      </div>
      <div class="card-body">
        <table class="table table-striped table-lightfont" id="table">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Nama ITSM</th>
              <th>Jumlah</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
    <div class="card mb-4">
      <div class="card-header header-elements">
        <span class=" me-2">Pemenuhan SLA</span>
        <div class="card-header-elements ms-auto">
          <span>
          </span>
        </div>
      </div>
      <div class="card-body">
        <div id="pemenuhanSLAChart"></div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xxxl-6">
    <div class="element-wrapper">

      @can('dashboard.pie-group-solver.read')
      <div class="card mb-4">
        <div class="card-header header-elements">
          <span class=" me-2">Layanan Berdasarkan Group Solver / Subdirektorat</span>
        </div>
        <div class="card-body">
          <div id="subBagLayananChart"></div>
        </div>
      </div>
      @endcan
      @can('dashboard.pie-solver.read')
      <div class="card mb-4">
        <div class="card-header header-elements">
          <span class=" me-2">Layanan Berdasarkan Solver</span>
        </div>
        <div class="card-body">
          <div id="solverLayananChart"></div>
        </div>
      </div>
      @endcan
      <div class="card mb-4">
        <div class="card-header header-elements">
          <span class=" me-2">Prioritas Layanan</span>
        </div>
        <div class="card-body">
          <div id="prioritasLayananChart"></div>
        </div>
      </div>
      @if (Str::substr(auth()->user()->pegawai->KdUnitOrg, 0, 6) !=103403)
        <div class="card mb-4">
          <div class="card-header header-elements">
            <span class=" me-2">Tematik Layanan Teratas</span>
          </div>
          <div class="card-body">
            <div id="pemenuhanSLAChart"></div>
          </div>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection

@section('vendor-script')
{{-- vendor files --}}
<script src="{{ asset(mix('vendors/js/charts/apexcharts.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/charts/chart.min.js')) }}"></script>
@endsection
@section('page-script')
{{-- Page js files --}}
{{-- <script src="{{ asset(mix('js/scripts/charts/chart-chartjs.js')) }}"></script> --}}
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"
  integrity="sha512-LsnSViqQyaXpD4mBBdRYeP6sRwJiJveh2ZIbW41EBrNmKxgr/LFZIiWT6yr+nycvhvauz8c2nYMhrP80YhG7Cw=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script>
  $( document ).ready(function() {
      @if (session()->has('flash'))
          // $('#news').modal('toggle');
      @endif
      $("#statusLayanan").select2({
          placeholder: "Status Layanan",
          allowClear: true
      });
      $("#tglStart").flatpickr({
          altInput: true,
          altFormat: "j F Y",
          dateFormat: "Y-m-d",
          defaultDate: '{{ date('Y') }}-01-01'
      });
      $("#tglEnd").flatpickr({
          altInput: true,
          altFormat: "j F Y",
          dateFormat: "Y-m-d",
          defaultDate: new Date()
      });
      // $('#tglStart').datepicker({ dateFormat: 'dd M yy'}).datepicker("setDate", new Date('{{ date('Y') }}-01-01'));
      // $('#tglEnd').datepicker({ dateFormat: 'dd M yy'}).datepicker("setDate", new Date());
      dashboardData();
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      var table = $('#table').DataTable({
          processing: true,
          serverSide: true,
          paging: false,
          ajax: {
              method: 'GET',
              url : "{!! route('dashboard.layanan.datatables') !!}",
          },
          columns: [
              // { data: 'Id', name: 'Id' },
              {
                  data: 'ServiceCatalogKode',
                  render: function( data, type, full, meta ) {
                      return data;
                  },
                  width:'15%',
              },
              {
                  data: 'ServiceCatalogNama',
                  render: function( data, type, full, meta ) {
                      return data;
                  },
              },
              {
                  data: 'Jumlah',
                  render: function( data, type, full, meta ) {
                      return data;
                  },
                  searchable:false
              },
          ],
      });
  });
  function grafik(data, title, element) {
    Highcharts.chart(element, {
      chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
      },
      title: {
        text: title
      },
      tooltip: {
        pointFormat: '<b>{point.y:.0f}</b>'
      },
      accessibility: {
        point: {
          valueSuffix: '%'
        }
      },
      plotOptions: {
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          dataLabels: {
            enabled: true,
            format: '<b>{point.name}</b>: {point.y:.0f} ({point.percentage:.1f} %)'
          }
        }
      },
      series: [{
        name: 'Permohonan',
        colorByPoint: true,
        data: data,
                point:{
                    events:{
                        click: function (event) {
                            window.open('{{ route('layanan.index') }}?'+this.key+'='+this.id+'&'+$('#form').serialize());
                        }
                    }
                }
      }]
    });
  }
  $(document).on('change', '.filters', function(){
      dashboardData();
      $('#table').DataTable().ajax.url("{!! route('dashboard.layanan.datatables') !!}?tglStart="+tglStart+'&tglEnd='+tglEnd+'&statusLayanan='+statusLayanan).load();
  })
  $(document).on('click', '.even, .odd', function(){
      window.open('{{ route('layanan.index') }}?serviceCatalog='+$(this).attr('id')+'&'+$('#form').serialize());
  })
  dashboardData = () => {
      tglStart = $('#tglStart').val()
      tglEnd = $('#tglEnd').val()
      statusLayanan = $('#statusLayanan').val()

      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
          url: "{{ route('dashboard.data') }}?tglStart="+tglStart+'&tglEnd='+tglEnd+'&statusLayanan='+statusLayanan,
          type: "POST",
          beforeSend: function(data) {
          },
          success: function(response) {
              @can('dashboard.pie-solver.read')
              let solverData = [];
              let solverLayanan =response.data.solverLayanan
              for (let i in solverLayanan) {
                  solverData.push({
                      name:solverLayanan[i].NmPeg,
                      y:parseInt(solverLayanan[i].Jumlah),
                      key: 'solver',
                      id: solverLayanan[i].Nip,
                  });
              }
      grafik(solverData, '', 'solverLayananChart')
              @endif

              let pemenuhanSLAData = [];
              let pemenuhanSLA =response.data.pemenuhanSLA
              for (let i in pemenuhanSLA) {
                      sla = 'melewati'
                  if(pemenuhanSLA[i].PemenuhanSLA=='Memenuhi SLA'){
                      sla = 'tidak_melewati'
                  }
                  pemenuhanSLAData.push({
                      name:pemenuhanSLA[i].PemenuhanSLA,
                      y:parseInt(pemenuhanSLA[i].Jumlah),
                      key: 'sla',
                      id: sla,
                      color : pemenuhanSLA[i].Color
                  });
              }
      grafik(pemenuhanSLAData, '', 'pemenuhanSLAChart')

              let prioritasLayananData = [];
              let prioritasLayanan =response.data.prioritasLayanan
              for (let i in prioritasLayanan) {
                  prioritasLayananData.push({
                      name:prioritasLayanan[i].PrioritasLayanan,
                      y:parseInt(prioritasLayanan[i].Jumlah),
                      key: 'prioritasLayanan',
                      id: prioritasLayanan[i].PrioritasLayanan
                  });
              }
      grafik(prioritasLayananData, '', 'prioritasLayananChart')

              @can('dashboard.pie-group-solver.read')
              let subBagLayananData = [];
              let subBagLayanan =response.data.subBagLayanan
              for (let i in subBagLayanan) {
                  subBagLayananData.push({
                      name:subBagLayanan[i].NmUnitOrg,
                      y:parseInt(subBagLayanan[i].Jumlah),
                      key: 'groupSolver',
                      id: subBagLayanan[i].KdUnitOrg
                  });
              }
      grafik(subBagLayananData, '', 'subBagLayananChart')
              @endif
              tickets= response.data.tickets
              dataTicket='';
              for (let key in tickets) {
                  dataTicket +=`<div class="col-lg-2 col-6 mb-0">
                    <div class="card">
                      <div class="card-body text-left">
                        <a class="element-box el-tablo openTicket " style="cursor: pointer;" data-id="${tickets[key].StatusLayanan}">
                        <small class="label">${tickets[key].Name}</small>
                        <h5 class="card-title mb-0">${tickets[key].Jumlah}</h5>
                        </a>
                      </div>
                    </div>
                  </div>`
              }
              mendekatiDeadline= response.data.mendekatiDeadline
                  dataTicket +=`<div class="col-lg-2 col-6 mb-0">
                    <div class="card">
                      <div class="card-body text-left">
                        <a class="element-box el-tablo mendekatiDeadline"  style="cursor: pointer;">
                        <small class="label">Mendekati Deadline</small>
                        <h5 class="card-title mb-0">${mendekatiDeadline}</h5>
                        </a>
                      </div>
                    </div>
                  </div>`
              $('#statusLayananData').html(dataTicket)
          },
          error: function() {
              alert('Terjadi kesalahan, silakan reload');
          }
      })
  }
  $(document).on('click', '.openTicket', function(){
      window.open('/layanan?statusLayanan='+$(this).data('id')+'&tglStart='+$('#tglStart').val()+'&tglEnd='+$('#tglEnd').val())
  })
  $(document).on('click', '.mendekatiDeadline', function(){
      window.open('/layanan?sla=mendekati_deadline&tglStart='+$('#tglStart').val()+'&tglEnd='+$('#tglEnd').val())
  })
</script>
@endpush
