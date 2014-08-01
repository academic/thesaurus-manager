@extends('layouts.default')

@section('title')
@parent
| Register
@stop

{{-- Content --}}
@section('content')
<div class="page-header">
    <h1>Signup</h1>
</div>
<form method="post" action="" class="form form-horizontal">
    <!-- CSRF Token -->
    <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />

    <div class="form-group {{{ $errors->has('first_name') ? 'error' : '' }}}">
        <label class="label" for="first_name">First Name</label>
        <input class="form-control" type="text" name="first_name" id="first_name" value="{{{ Request::old('first_name') }}}" />
        {{{ $errors->first('first_name') }}}
    </div>

    <div class="form-group {{{ $errors->has('last_name') ? 'error' : '' }}}">
        <label class="label" for="last_name">Last Name</label>
        <input class="form-control" type="text" name="last_name" id="last_name" value="{{{ Request::old('last_name') }}}" />
        {{{ $errors->first('last_name') }}}
    </div>

    <div class="form-group {{{ $errors->has('email') ? 'error' : '' }}}">
        <label class="label" for="email">Email</label>
        <input class="form-control"type="text" name="email" id="email" value="{{{ Request::old('email') }}}" />
        {{{ $errors->first('email') }}}
    </div>
    <div class="form-group {{{ $errors->has('password') ? 'error' : '' }}}">
        <label class="label" for="password">Password</label>
        <input  class="form-control"type="password" name="password" id="password" value="" />
        {{{ $errors->first('password') }}}
    </div>
    <div class="form-group {{{ $errors->has('password_confirmation') ? 'error' : '' }}}">
        <label class="label" for="password_confirmation">Password Confirm</label>
        <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" value="" />
        {{{ $errors->first('password_confirmation') }}}
    </div>

    <div class="form-group"> 
        <button type="submit" class="btn btn-success">Signup</button>
    </div>
</form>
@stop
