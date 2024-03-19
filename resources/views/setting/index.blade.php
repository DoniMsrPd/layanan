@extends('core.layouts.master')

@section('content')
    <h1>Hello Worldsss</h1>

    <p>
        This view is loaded from module: {!! config('setting.name') !!}
    </p>
@endsection
