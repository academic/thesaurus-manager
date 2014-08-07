@extends('layouts.default')

@section('content')
<h1>New added entries</h1>

<div class="col-md-12">
    @if(isset($results))
    @foreach ($results as $item)
    <ul class="list-group">
        <li class="list-group-item">
            <a href="/nodes/graph-editor/{{$item['id']}}">
                <strong class="pull-left">{{{ urldecode($item['properties']['word']) }}}</strong>
            </a>
            <span class="pull-right">
                <a href="/moderation/approve/{{$item['id']}}" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-ok"></i></a> 
                <a href="/moderation/decline/{{$item['id']}}" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-ban-circle"></i></a>
            </span>
            <div class="row"></div>
        </li>
    </ul>
    @endforeach
    @endif
</div>

@stop
