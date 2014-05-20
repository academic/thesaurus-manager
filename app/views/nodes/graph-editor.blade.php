@extends('layouts.default')

@section('css')
@parent 
<link rel="stylesheet" href="{{{ asset('assets/css/grapheditor.css') }}}">
@stop

@section('content')

<div class="page-header">
    <p>
        <a href="#"><h2>{{{ $node->getProperty("word")}}}</h2></a> 
    </p>
    <p>
        <a class="label label-info" href="https://www.wordnik.com/words/{{ $node->getProperty("word") }}">wornik</a>
        <a class="label label-info" href="http://thesaurus.com/browse/{{ $node->getProperty("word") }}">thesaurus</a>
        <a class="label label-info" href="https://duckduckgo.com/?q={{ $node->getProperty("word") }}">duckduckgo</a>
        <a class="label label-info" href="https://www.google.com/search?q={{ $node->getProperty("word") }}">google</a>

    </p>
</div>

<div class="col-md-12">
    <a class="btn btn-success"><i class="glyphicon glyphicon-plus-sign"></i> Add Word</a> 
    <a onclick="return confirm('Are you sure?') ? location.reload() : ''"  class="btn btn-warning">Reset Work</a>
</div> 
<div class="row">
    <input class="form-control hidden" id="selected" />
</div>
<div id="graph"></div>

@stop

@section('js')
@parent
<script>
            var nodes = [
                    @if(isset($nodes))
                    @foreach ($nodes as $node)
            {id: {{{ $node->getId()}}}, reflexive: false, data: "{{{$node->getProperty("word")}}}" },
                    @endforeach
                    @endif
            ];                    </script>
<script src="{{{ asset('assets/js/d3.v3.min.js') }}}"></script>
<script src="{{{ asset('assets/js/grapheditor.js') }}}"></script>

@stop