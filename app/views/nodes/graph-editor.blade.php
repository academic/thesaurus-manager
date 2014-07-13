@extends('layouts.default')

@section('css')
@parent 
<link rel="stylesheet" href="{{{ asset('assets/css/grapheditor.css') }}}">
@stop

@section('content')

<div class="page-header">
    <p>
        <a href="#"><h2>{{{ $node->getProperty("word")}}} <small>lang:{{{ $node->getProperty("lang")}}}</small></h2></a> 
    <div>
        @if($nodesSynonym)
        Synonyms : 
        @foreach ($nodesSynonym as $synonym)
        @if($synonym->getProperty("word")!=$node->getproperty("word"))
        <span class="badge">{{$synonym->getProperty("word")}}</span>
        @endif
        @endforeach
        @endif

    </div>
</p>
<p>
    <small>Check results on: </small>
    <a class="label label-default" target="_blank"
       href="https://www.wordnik.com/words/{{ $node->getProperty("word") }}">wornik</a>
    <a class="label label-default" target="_blank"
       href="http://thesaurus.com/browse/{{ $node->getProperty("word") }}">thesaurus</a>
    <a class="label label-default" target="_blank"
       href="https://duckduckgo.com/?q={{ $node->getProperty("word") }}">duckduckgo</a>
    <a class="label label-default" target="_blank"
       href="https://www.google.com/search?q={{ $node->getProperty("word") }}">google</a>
    <a class="label label-default" target="_blank"
       href="http://words.bighugelabs.com/{{ $node->getProperty("word") }}">words.bighugelabs</a>
</p>
</div>

<div class="col-md-12">
    <a class="btn btn-success" data-toggle="modal" data-target="#addNodeModal">
        <i class="glyphicon glyphicon-plus-sign"></i> Add Related Word
    </a> 
    <a class="btn btn-info" data-toggle="modal" data-target="#addSynonymModal">
        <i class="glyphicon glyphicon-plus"></i> Add Synonym
    </a> 


    <!-- graph editor not implemented yet. so this button is useless as windows 95 -->
    <a onclick="return confirm('Are you sure?') ? location.reload() : ''"  class="btn btn-warning">Reset Work</a>
</div> 
<div class="row">
    <input class="form-control hidden" id="selected" />
</div>
<div id="graph"></div> 


<!-- Modals -->
@include('nodes.add-node-modal')

@include('nodes.add-synonym-modal')

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