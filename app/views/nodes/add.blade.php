@extends('layouts.default')

@section('content')

<div class="page-header">
    <h1>Add New Thesaurus </h1>
</div>
<form method="post" action="" class="form col-md-12 center-block">

    {{ Form::token() }}

    <div class="form-group col-md-5">
        <input type="text" placeholder="word1 " name="word1"  
               class="form-control input-lg" />
    </div>
    <div class="col-md-2" style="text-align: center">
        <i class="glyphicon glyphicon-arrow-left"></i><i class="glyphicon glyphicon-arrow-right"></i>
    </div>

    <div class="form-group col-md-5">
        <input type="text" placeholder="word2 " name="word2"  
               class="form-control input-lg" />
    </div>

    <div class="form-group col-md-12"> 
        <select class="form-control input-lg" name="level">
            <option value="100">High</option>
            <option value="50">Medium</option>
            <option value="10">Low</option>
        </select>
    </div>

    <div class="form-group col-md-12">
        <div class="controls">
            <button type="submit" class="btn btn-success btn-lg">Add</button>
        </div>
    </div>
</form>

@stop
