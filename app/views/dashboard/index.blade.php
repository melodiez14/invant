@extends('layouts.dashboard')
@section('pagecss')
    <link rel="stylesheet" href="{{ asset('js/select2/select2.css') }}" type="text/css" cache="false" />
    <link rel="stylesheet" href="{{ asset('js/select2/theme.css') }}" type="text/css" cache="false" />
@stop
@section('breadcrumb')
    <li class="active">Dashboard</li>
@stop

@section('content')
    <div class="col-md-9">
        Dashboard
    </div>
@stop

@section('pagejs')

@stop
