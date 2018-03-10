@section('breadcrumb')
    <li><a href="#">Account</a></li>
    <li><a href="{{ route('users.index') }}">Local Admin</a></li>
    <li><a href="{{ route('users.edit', ['users'=>$user->id]) }}">Update Profile</a></li>
    <li class="active">Change password</li>
@stop

@section('content')
    <div class="m-b-md">
        <h3 class="">Change {{ $user->first_name.' '.$user->last_name}}'s password</h3>
    </div>

    <section class="panel panel-default">
        <header class="panel-heading font-bold">
            Please fill this form
        </header>
    <div class="panel-body">
    {{ Form::open(array('url' => route('users.changepassword', ['users'=>$user->id]), 'method' => 'put', 'role' => 'form', 'class'=>'form-horizontal', 'data-validate'=>'parsley-validate', 'novalidate')) }}
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
            {{ Form::submit('Update', array('class' => 'btn btn-default m-r-sm')) }}
            <a href="{{ $previousUrl }}">Cancel</a>
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
