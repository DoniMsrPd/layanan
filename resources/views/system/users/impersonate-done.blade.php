@extends('core::layouts.app')

@section('title', 'Impersonate User')

@section('page-tools')
<a href="{{ route('core.impersonate.leave') }}" class="btn btn-secondary"><i class="mdi mdi-face"></i> Leave Impersonation</a>
@endsection

@section('content')

@alert(['type' => 'primary'])
You are impersonating this person
@endalert

@include('system::users.profile')

@endsection
