@extends('layouts.notebook')
@section('title')
    Sign In | IDMS
@stop

@section('content')
    <div class="container aside-xxl">
        <a class="navbar-brand block" href="index.html" style="color: #f9f9f9;">IDMS</a>
        <section class="panel panel-default bg-white m-t-lg">
            <header class="panel-heading text-center">
                <strong>Sign in</strong>
            </header>

            {{ Form::open(array('url' => 'login', 'role' => 'form', 'class' => 'panel-body wrapper-lg')) }}
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
                <div class="form-group">
                    {{ Form::label('password', 'Password', array('class' => 'control-label')) }}
                    {{ Form::password('password', array(
                        'class' => 'form-control input-lg',
                        'placeholder' => 'Password'
                        )) }}
                </div>
                <div class="checkbox">
                    <label>{{ Form::checkbox('remember_me') }} Keep me logged in</label>
                </div>
                <a href="{{ url('password/remind')  }}" class="pull-right m-t-xs"><small>Forgot password?</small></a>
                <button type="submit" class="btn btn-primary">Sign in</button>
                <div class="line line-dashed"></div>

                {{-- Display if not google chrome --}}

            {{ Form::close() }}
        </section>
    </div>
@stop
