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
  <div class="col-md-8 mb-1">
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
</div>
<div class="row" id="tickets">
@include('system.dashboard.ticket-pegawai')
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
      // dashboardData();
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
  });
  $(document).on('change', '.filters', function(){
      dashboardData();
  })
  dashboardData = () => {
      tglStart = $('#tglStart').val()
      tglEnd = $('#tglEnd').val()

      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
          url: "{{ route('dashboard.data') }}?tglStart="+tglStart+'&tglEnd='+tglEnd,
          type: "POST",
          beforeSend: function(data) {
          },
          success: function(response) {
            $('#tickets').html(response)
          },
          error: function() {
              alert('Terjadi kesalahan, silakan reload');
          }
      })
  }
</script>
@endpush
