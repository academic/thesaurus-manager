@extends('layouts.default')

@section('title')
@parent
| Login
@stop

{{-- Content --}}
@section('content')
<div class="page-header">
    <h1>Login into your account</h1>
</div>


<form method="post" action="" class="form col-md-12 center-block">
    <!-- CSRF Token -->
    <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />

    <div class="form-group">
        <input type="text" class="form-control input-lg" 
               placeholder="Email" name="email" id="email" value="{{{ Input::old('email') }}}" />
    </div>
    <div class="form-group ">
        <input type="password" class="form-control input-lg" 
               placeholder="Password" name="password" id="password" value="" /> 
    </div>
    <div class="form-group">
        <button class="btn btn-primary btn-lg btn-block">Sign In</button>
        <span class="pull-right"><a href="#">Register</a></span>

    </div>
</form> 

@stop