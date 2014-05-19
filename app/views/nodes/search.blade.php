@extends('layouts.default')

@section('content')
<h1>Search Word</h1>



<form method="post" action="" class="form col-md-12 center-block">

    {{ Form::token() }}

    <div class="form-group">
        <input type="text" placeholder="word " name="word" id="thesaurus" 
               class="form-control input-lg" />
        {{ $errors->first('word') }}
    </div>


</form>
<br>
<div class="col-md-12">
    @if(isset($results))
    @foreach ($results as $item)
    <p><a href="/nodes/graph-editor/{{$item['id']}}">{{ $item['properties']['word'] }}</a></p>
    @endforeach
    @endif
</div>

@stop
