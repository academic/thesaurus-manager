@extends('layouts.default')

@section('title')
@parent
| Edit Account
@stop

 

@section('content')
<div class="page-header">
    <h1>Edit your settings</h1>
</div>
<form method="post" action="" class="form col-md-12 center-block">

    {{ Form::token() }}

    <div class="form-group {{{ $errors->has('first_name') ? 'error' : '' }}}">

        <input type="text" placeholder="First Name " name="first_name" id="first_name" class="form-control input-lg" 
               value="{{{ Request::old('first_name', $user->first_name) }}}" />
        {{ $errors->first('first_name') }}
    </div>

    <div class="form-group {{{ $errors->has('last_name') ? 'error' : '' }}}"> 
        <input placeholder="Last Name" type="text" name="last_name" id="last_name" class="form-control input-lg"
               value="{{{ Request::old('last_name', $user->last_name) }}}" />
        {{ $errors->first('last_name') }} 
    </div>

    <div class="form-group {{{ $errors->has('email') ? 'error' : '' }}}">
        <input type="text" name="email" id="email"  class="form-control input-lg"
               value="{{{ Request::old('email', $user->email) }}}" />
        {{ $errors->first('email') }}
    </div>

    <div class="form-group {{{ $errors->has('password') ? 'error' : '' }}}">  
        <input type="password" name="password" id="password" value=""  class="form-control input-lg" placeholder="Password" />
        {{ $errors->first('password') }} 
    </div>

    <div class="form-group {{{ $errors->has('password_confirmation') ? 'error' : '' }}}">
        <input placeholder="Password Confirm" type="password" name="password_confirmation" id="password_confirmation" class="form-control input-lg"  />
        {{ $errors->first('password_confirmation') }}
    </div>

    <div class="form-group">
        <div class="controls">
            <button type="submit" class="btn btn-success btn-lg">Update</button>
        </div>
    </div>
</form>
@stop
