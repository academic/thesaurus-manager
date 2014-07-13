@extends('layouts.default')

@section('css')
@parent 
<link rel="stylesheet" href="{{{ asset('assets/css/grapheditor.css') }}}">
@stop

@section('content')

<div class="page-header">
    <p>
        <a href="#"><h2>{{{ $node->getProperty("word")}}} <small>lang:{{{ $node->getProperty("lang")}}}</small></h2></a> 
    </p>
    <p>
        <a class="label label-info" href="https://www.wordnik.com/words/{{ $node->getProperty("word") }}">wornik</a>
        <a class="label label-info" href="http://thesaurus.com/browse/{{ $node->getProperty("word") }}">thesaurus</a>
        <a class="label label-info" href="https://duckduckgo.com/?q={{ $node->getProperty("word") }}">duckduckgo</a>
        <a class="label label-info" href="https://www.google.com/search?q={{ $node->getProperty("word") }}">google</a>
        <a class="label label-info" href="http://words.bighugelabs.com/{{ $node->getProperty("word") }}">words.bighugelabs</a>


    </p>
</div>

<div class="col-md-12">
    <a class="btn btn-success" data-toggle="modal" data-target="#addNodeModal"><i class="glyphicon glyphicon-plus-sign"></i> Add Word</a> 
    <a onclick="return confirm('Are you sure?') ? location.reload() : ''"  class="btn btn-warning">Reset Work</a>
</div> 
<div class="row">
    <input class="form-control hidden" id="selected" />
</div>
<div id="graph"></div> 


<!-- Modal -->
<div class="modal fade" id="addNodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addNodeModalLabel">Add Word</h4>
            </div>


            <div class="modal-body">
                <form method="POST" action="/nodes/add" class="form col-md-12 center-block">
                    {{ Form::token() }}
                    <div class="form-group col-md-5">
                        <input type="text" placeholder="word1 " name="word1"  
                               class="form-control input-lg" value="{{{$node->getProperty("word")}}}" />
                    </div>
                    <div class="col-md-1" style="text-align: center">
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

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
@parent
<script>
            var links = [
                @if($relations)
                @foreach ($relations as $relation)
                {source: "{{{ $relation["source"] }}}", target: "{{{$relation["target"]}}}", type: "related"},
                @endforeach
                @endif
            ];</script>
<script src="{{{ asset('assets/js/d3.v3.min.js') }}}"></script>
<script src="{{{ asset('assets/js/grapheditor.js') }}}"></script>

@stop