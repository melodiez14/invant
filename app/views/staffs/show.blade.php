@extends('layouts.dashboard')
@section('breadcrumb')
    <li><a href="{{ route('staffs.index') }}">Staffs</a></li>
    <li class="active">View: {{ $staff->name }}</li>
@stop

@section('content')
    <section class="panel panel-default">
        <header class="panel-heading font-bold">
            <h3>Showing {{ $staff->prefix . ' ' . $staff->name }}</h3>
        </header>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-1">
                    @if (!empty($staff->photo_id))
                        <img src="{{ route('uploads.show', ['uploads' => $staff->photo_id]) }}" alt="" class="img-thumbnail mg-full">
                    @else
                        <img src="{{ asset('images/avatar_default.jpg') }}" alt="" class="img-thumbnail mg-full">
                    @endif
                </div>
                <div class="col-sm-7">
                    <div class="row m-t-md">
                        <div class="col-sm-4 text-muted text-right text-left-xs">Name</div>
                        <div class="col-sm-8 text-dark text-left font-bold">{{ $staff->name }}</div>
                    </div>
                    <div class="row m-t-md">
                        <div class="col-sm-4 text-muted text-right text-left-xs">Sex</div>
                        <div class="col-sm-8 text-dark text-left font-bold">{{ ($staff->sex_id == 1) ? 'Male' : 'Female' }}</div>
                    </div>
                    <div class="row m-t-md">
                        <div class="col-sm-4 text-muted text-right text-left-xs">Base Location</div>
                        <div class="col-sm-8 text-dark text-left font-bold">{{ $staff->address }}</div>
                    </div>
                    <div class="row m-t-md">
                        <div class="col-sm-4 text-muted text-right text-left-xs">Email</div>
                        <div class="col-sm-8 text-dark text-left font-bold">{{ $staff->email }}</div>
                    </div>
                    <div class="row m-t-md">
                        <div class="col-sm-4 text-muted text-right text-left-xs">Phone</div>
                        <div class="col-sm-8 text-dark text-left font-bold">{{ $staff->phone }}</div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="line line-dashed line-lg pull-in visible-xs"></div>
                    <div class="row m-t-md">
                        <div class="col-sm-4 text-muted text-right text-left-xs">Manager of</div>
                        <div class="col-sm-8 text-dark text-left font-bold">
                            @if (!empty($managedprojects))
                                <ul class="list-unstyled">
                                    @foreach($managedprojects as $project)
                                        <li><a href="{{route('projects.show', ['projects'=>$project->id])}}">
                                        {{$project->title}}</a></li>
                                    @endforeach
                                </ul>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="row m-t-md">
                        <div class="col-sm-4 text-muted text-right text-left-xs">Admin of</div>
                        <div class="col-sm-8 text-dark text-left font-bold">
                            @if (!empty($projects))
                            <ul class="list-unstyled">
                                @foreach($projects as $project)
                                    <li><a href="{{route('projects.show', ['projects'=>$project->id])}}">
                                    {{$project->title}}</a></li>
                                @endforeach
                            </ul>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <a href="{{ $previousUrl }}" class="btn btn-link pull-right">Back</a>
        </div>
    </section>
@stop
