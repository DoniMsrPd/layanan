@extends('core::layouts.app')

@section('title', 'Impersonate User')

@section('content')

{{ Form::open(['route' => 'core.impersonate.impersonate', 'method' => 'POST']) }}

<div class="row">
    <div class="col-lg-8 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                @if (!$users->count())
                <div class="alert alert-primary">
                    <div class="icon"></div>
                    <div class="message">You look lonely. <a href="{{ route('core.user.create') }}">Add another user</a></div>
                </div>
                @endif
                <div class="form-group pt-2">
                    <label for="nip">User</label>
                    {{ Form::select('nip', $users, null, ['class' => 'select2 form-control', 'id' => 'nip']) }}
                    @if ($errors->has('nip'))
                        <span class="help-block">
                            <strong>{{ $errors->first('nip') }}</strong>
                        </span>
                    @endif
                </div>

                {{ Form::submit('Impersonate', ['class' => 'btn btn-lg btn-primary']) }}
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-12 col-xs-12">

    </div>
</div>


{{ Form::close() }}

@endsection

@push('scripts')
<script>
$('#nip').select2({
    width: '100%'
});

</script>
@endpush
