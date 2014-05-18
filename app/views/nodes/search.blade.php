@extends('layouts.default')

@section('content')
<h1>Search Word</h1>



<form method="post" action="" class="form col-md-12 center-block">

    {{ Form::token() }}

    <div class="form-group">
        <input type="text" placeholder="thesaurus " name="thesaurus" id="thesaurus" 
               class="form-control input-lg" />
        {{ $errors->first('thesaurus') }}
    </div>


</form>
<br>

@stop
