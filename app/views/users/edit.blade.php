@extends('layouts.dashboard')

@section('breadcrumb')
    <li><a href="#">Account</a></li>
    <li><a href="{{ route('users.index') }}">Local Admin</a></li>
    <li class="active">Update Profile</li>
@stop

@section('content')
    <div class="m-b-md">
        <h3>{{trans('user.update_profile', ['user' => (!empty($user->profile) ? $user->profile->name : null)])}}</h3>
        <a href="{{-- route('users.changepassword', ['users'=>$user->id]) --}}" class="btn btn-xs btn-default btn-rounded">Change Password</a>
    </div>

    <section class="panel panel-default">
        <header class="panel-heading font-bold">
            Please fill this form
        </header>
    <div class="panel-body">
    {{ Form::open(array('url' => route('users.update', ['users'=>$user->id]), 'method' => 'put', 'role' => 'form', 'class'=>'form-horizontal', 'parsley-validate', 'novalidate')) }}
        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group required">
            {{ Form::label('name', trans('user.name'), array('class'=>'col-sm-2 control-label')) }}
            <div class="col-sm-10">
            {{ Form::text('name', ((!empty($user->profile)) ? $user->profile->name : null), array(
                'class' => 'form-control',
                'placeholder' => trans('user.name')
                )) }}
            </div>
        </div>
        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
        </div>
        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group required">
            {{ Form::label('email', trans('user.email'), array('class'=>'col-sm-2 control-label')) }}
            <div class="col-sm-10">
            {{ Form::email('email', $user->email, array(
                'class' => 'form-control',
                'placeholder' => trans('user.email'),
                'data-required' => 'true',
                'data-type' => 'email'
                )) }}
            </div>
        </div>
        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-2">
                {{ Form::submit('Save', array('class' => 'btn btn-default m-r-sm')) }}
                <a href="{{-- $previousUrl --}}">Cancel</a>
            </div>
        </div>
    {{ Form::close() }}
    </div>
    </section>
@stop

@section('pagejs')
    <!-- parsley -->
    <script src="{{ asset('js/parsley/parsley.min.js')}}" cache="false"></script>
    <script src="{{ asset('js/parsley/parsley.extend.js')}}" cache="false"></script>
@stop
