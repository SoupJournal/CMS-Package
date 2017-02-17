@extends('cms::layouts.page')

@section('title') Login @stop

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

	{{-- display login form errors --}}
    @if (isset($errors) && $errors->has())
        @foreach ($errors->all() as $error)
            <div class='bg-danger alert'>{{ $error }}</div>
        @endforeach
    @endif



    <h1><i class='fa fa-lock'></i> Login</h1>

    {{ Form::open(Array('role' => 'form')) }}

    <div class='form-group'>
        {{ Form::label('username', 'Username') }}
        {{ Form::text('username', null, Array ('placeholder' => 'Username', 'class' => 'form-control')) }}
    </div>

    <div class='form-group'>
        {{ Form::label('password', 'Password') }}
        {{ Form::password('password', Array ('placeholder' => 'Password', 'class' => 'form-control')) }}
    </div>

    <div class='form-group'>
        {{ Form::submit('Login', Array ('class' => 'btn btn-primary')) }}
    </div>

    {{ Form::close() }}

</div>

@stop