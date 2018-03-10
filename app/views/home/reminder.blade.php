@extends('layouts.notebook')
@section('title')
    Forgot Password | IDMS
@stop

@section('content')
    <div class="container aside-xxl">
        <a class="navbar-brand block" href="index.html">IDMS</a>
        <section class="panel panel-default bg-white m-t-lg">
            <header class="panel-heading text-center">
                <strong>Forgot Password</strong>
            </header>
            {{ Form::open(array('url' => url('password/remind'), 'method' => 'post',
              'role' => 'form', 'class'=>'panel-body wrapper-lg', 'parsley-validate', 'novalidate')) }}
                {{-- BEGIN Alert Messages--}}
                @if (Session::has('success-message'))
                  <div class="alert alert-success alert-block">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                      <p>{{ Session::get('success-message') }}</p>
                  </div>
                @elseif (Session::has('error-message'))
                  <div class="alert alert-danger alert-block">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                      <p>{{ Session::get('error-message') }}</p>
                  </div>
                @endif

                <div class="form-group">
                    {{ Form::label('email', 'E-mail Address', array('class' => 'control-label')) }}
                    {{ Form::email('email', null, array(
                        'class' => 'form-control input-lg',
                        'placeholder' => 'Enter Email'
                        )) }}
                </div>

                <button type="submit" class="btn btn-primary">Reset Password</button>
                <div class="line line-dashed"></div>
            {{ Form::close() }}
        </section>
    </div>
@stop
