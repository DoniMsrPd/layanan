<!-- BEGIN: Vendor CSS-->
@if ($configData['direction'] === 'rtl' && isset($configData['direction']))
<link rel="stylesheet" href="{{ asset(mix('vendors/css/vendors-rtl.min.css')) }}" />
@else
<link rel="stylesheet" href="{{ asset(mix('vendors/css/vendors.min.css')) }}" />
@endif

@yield('vendor-style')
<!-- END: Vendor CSS-->

<!-- BEGIN: Theme CSS-->
<link rel="stylesheet" href="{{ asset(mix('css/core.css')) }}" />
<link rel="stylesheet" href="{{ asset(mix('css/base/themes/dark-layout.css')) }}" />
<link rel="stylesheet" href="{{ asset(mix('css/base/themes/bordered-layout.css')) }}" />
<link rel="stylesheet" href="{{ asset(mix('css/base/themes/semi-dark-layout.css')) }}" />

@php $configData = Helper::applClasses(); @endphp

<!-- BEGIN: Page CSS-->
@if ($configData['mainLayoutType'] === 'horizontal')
<link rel="stylesheet" href="{{ asset(mix('css/base/core/menu/menu-types/horizontal-menu.css')) }}" />
@else
<link rel="stylesheet" href="{{ asset(mix('css/base/core/menu/menu-types/vertical-menu.css')) }}" />
@endif

{{-- Page Styles --}}
@yield('page-style')

<!-- laravel style -->
<link rel="stylesheet" href="{{ asset(mix('css/overrides.css')) }}" />

<!-- BEGIN: Custom CSS-->

@if ($configData['direction'] === 'rtl' && isset($configData['direction']))
<link rel="stylesheet" href="{{ asset(mix('css-rtl/custom-rtl.css')) }}" />
<link rel="stylesheet" href="{{ asset(mix('css-rtl/style-rtl.css')) }}" />

@else
{{-- user custom styles --}}
<link rel="stylesheet" href="{{ asset(mix('css/style.css')) }}" />
@endif

<style>
  .navbar-floating .header-navbar-shadow {
    background: linear-gradient(180deg,#fff,#f6ece2) ;
    background-repeat: repeat ;
  }
  .horizontal-layout.navbar-floating .header-navbar-shadow {
      height: 110px
  }

  @media(min-width: 1200px) {
      .horizontal-layout.navbar-floating .header-navbar-shadow {
        top:0px !important
      }
  }
  .header-navbar .navbar-shadow{
    box-shadow: none
  }
  .horizontal-menu .header-navbar {
    background: none;
  }
  .horizontal-menu .header-navbar.navbar-brand-center .navbar-header .navbar-brand .brand-logo img {
    max-width: 200px !important;
  }

  .text-right {
    text-align: right;
  }
  .text-left {
    text-align: left;
  }
  .text-center {
    text-align: center;
  }

  .text-detail {
    color: #cbcbcb;
  }
  html body {
    background-image: url('{{url("/")}}/bg-ecc4.jpg');
  }
  @media screen and (min-width: 1024px) {
      .header-navbar-shadow {
        display: inline;
      }
  }
</style>
