@extends('layouts.dashboard')

@section('breadcrumb')
    <li><a href="#">Account</a></li>
    <li><a href="{{ route('users.index') }}">Local Admin</a></li>
    <li class="active">New Admin</li>
@stop

@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none">New Local Admin</h3>
    </div>
    <section class="panel panel-default">
        <header class="panel-heading font-bold">
            Please fill this form
        </header>
    <div class="panel-body">
    {{ Form::open(array('url' => route('users.store'), 'method' => 'post', 'role' => 'form', 'class'=>'form-horizontal', 'parsley-validate', 'novalidate')) }}
        <div class="form-group required">
            {{ Form::label('first_name', 'First Name', array('class'=>'col-sm-2 control-label')) }}
            <div class="col-sm-10">
            {{ Form::text('first_name', null, array(
                'class' => 'form-control',
                'placeholder' => 'First Name',
                'data-required' => 'true'
                )) }}
            </div>
        </div>
        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
            {{ Form::label('last_name', 'Last Name', array('class'=>'col-sm-2 control-label')) }}
            <div class="col-sm-10">
            {{ Form::text('last_name', null, array(
                'class' => 'form-control',
                'placeholder' => 'Last Name'
                )) }}
            </div>
        </div>
        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group required">
            {{ Form::label('email', 'Email Address', array('class'=>'col-sm-2 control-label')) }}
            <div class="col-sm-10">
            {{ Form::email('email', null, array(
                'class' => 'form-control',
                'placeholder' => 'Email Address',
                'data-required' => 'true',
                'data-type' => 'email',
                )) }}
            </div>
        </div>
        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group required">
            {{ Form::label('password', 'Password', array('class'=>'col-sm-2 control-label')) }}
            <div class="col-sm-10">
            {{ Form::password('password', array(
                'class' => 'form-control',
                'placeholder' => 'Password',
                'parsley-minlength' => '5',
                'data-required' => 'true'
                )) }}
                <span class="help-block m-b-none">Minimum 5 character.</span>
            </div>
        </div>
        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-2">
                {{ Form::submit('Register', array('class' => 'btn btn-default')) }}
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
