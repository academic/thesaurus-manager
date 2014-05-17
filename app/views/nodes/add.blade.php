@extends('layouts.default')

@section('content')

<div class="page-header">
    <h1>Add New Thesaurus </h1>
</div>
<form method="post" action="" class="form col-md-12 center-block">

    {{ Form::token() }}

    <div class="form-group">
        <input type="text" placeholder="thesaurus " name="thesaurus" id="thesaurus" 
               class="form-control input-lg" />
        {{ $errors->first('thesaurus') }}
    </div>

    <div class="form-group">
        <div class="controls">
            <button type="submit" class="btn btn-success btn-lg">Add</button>
        </div>
    </div>
</form>

@stop
