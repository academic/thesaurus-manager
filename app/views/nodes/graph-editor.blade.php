@extends('layouts.default')

@section('css')
@parent 
<link rel="stylesheet" href="{{{ asset('assets/css/grapheditor.css') }}}">
@stop

@section('content')

<div class="page-header">
    <h1>Graph Editor</h1>
</div> 
<input class="form-control hidden" id="selected" />
<div id="graph"></div>

@stop

@section('js')
@parent
<script src="{{{ asset('assets/js/d3.v3.min.js') }}}"></script>
<script src="{{{ asset('assets/js/grapheditor.js') }}}"></script>

@stop