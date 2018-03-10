@section('pagecss')
    <link rel="stylesheet" href="{{ asset('js/fuelux/fuelux.css') }}" type="text/css" cache="false" />
@stop
@section('breadcrumb')
    <li><a href="#">Account</a></li>
    <li><a href="{{ route('staffs.index') }}">SC Staff</a></li>
    <li class="active">New SC Staff</li>
@stop

@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none">New SC Staff</h3>
    </div>
    <section class="panel panel-default">
        <header class="panel-heading font-bold">
            New SC Staff Form
        </header>
        <div class="panel-body">
        {{ Form::open(array('url' => route('staffs.store'), 'method' => 'post','files' => true,
            'role' => 'form', 'class'=>'form-horizontal', 'parsley-validate', 'novalidate')) }}
            <div class="form-group required">
                {{ Form::label('first_name', 'First Name', array('class'=>'col-sm-2 control-label')) }}
                <div class="col-sm-10">
                {{ Form::text('first_name', null, array(
                    'class' => 'form-control',
                    'placeholder' => 'First Name',
                    'parsley-required' => 'true'
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
                {{ Form::label('sex_id', 'Sex', array('class'=>'col-sm-2 control-label')) }}
                <div class="col-sm-10">
                    <div class="radio">
                        {{ Form::radio('sex_id', '1', true) }} Man
                    </div>
                    <div class="radio">
                        {{ Form::radio('sex_id', '2') }} Woman
                    </div>
                </div>
            </div>
            <div class="line line-dashed line-lg pull-in"></div>
            <div class="form-group">
                {{ Form::label('address', 'Address', array('class'=>'col-sm-2 control-label')) }}
                <div class="col-sm-10">
                {{ Form::text('address', null, array(
                    'class' => 'form-control',
                    'placeholder' => 'Address'
                    )) }}
                </div>
            </div>
            <div class="line line-dashed line-lg pull-in"></div>
            <div class="form-group">
                {{ Form::label('phone', 'Phone', array('class'=>'col-sm-2 control-label')) }}
                <div class="col-sm-10">
                {{ Form::text('phone', null, array(
                    'class' => 'form-control',
                    'placeholder' => '+62 xxxxx xxxx'
                    )) }}
                </div>
            </div>
            <div class="line line-dashed line-lg pull-in"></div>
            <div class="form-group required">
                {{ Form::label('email', 'Email Address', array('class'=>'col-sm-2 control-label')) }}
                <div class="col-sm-10">
                {{ Form::email('email', null, array(
                    'class' => 'form-control',
                    'placeholder' => 'someone@example.com',
                    'parsley-type' => 'email',
                    'parsley-required' => 'true',
                    'parsley-trigger' => 'change'
                    )) }}
                </div>
            </div>
            <div class="line line-dashed line-lg pull-in"></div>
            <div class="form-group">
                {{ Form::label('photo_id', 'Photo', array('class'=>'col-sm-2 control-label')) }}
                <div class="col-sm-10">
                    {{ Form::file('photo_id', array(
                        'type'=>'file',
                        'class'=>'filestyle',
                        'data-icon'=>'false',
                        'data-classButton'=>'btn btn-default',
                        'data-classInput'=>'form-control inline input-s'
                        )) }}
                    <span class="help-block m-b-none">Max size 2 Mb and resolution 320px x 320px. Larger image will be cropped and resized.</span>
                </div>
            </div>
            <div class="line line-dashed line-lg pull-in"></div>
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    {{ Form::submit('Register', array('class' => 'btn btn-primary m-r-sm')) }}
                    <a href="{{ $previousUrl }}">Cancel</a>
                </div>
            </div>
        {{ Form::close() }}
        </div>
    </section>


@stop

@section('pagejs')
    <!-- file input -->
    <script src="{{ asset('js/file-input/bootstrap-filestyle.min.js') }}" cache="false"></script>
    <!-- parsley -->
    <script src="{{ asset('js/parsley/parsley.min.js')}}" cache="false"></script>
    <script src="{{ asset('js/parsley/parsley.extend.js')}}" cache="false"></script>
@stop
