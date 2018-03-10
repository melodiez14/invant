@extends('layouts.dashboard')
@section('pagecss')
    <link rel="stylesheet" href="{{ asset('js/datatables/datatables.css') }}" type="text/css" cache="false"/>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
@stop

@section('breadcrumb')
    <li><a href="#">{{trans('user.user_man')}}</a></li>
    <li class="active">Staff</li>
@stop

@section('content')
    <h3>Staff
        @if (isXUser('create', 'staffs'))
            <a href="{{ route('staffs.create') }}" class="btn btn-xs btn-default btn-rounded"><i class="fa fa-user m-l-xs m-r-sm"></i>{{ trans('staff.new_staff') }}</a>
        @endif
    </h3>
    <h4 class="inline text-muted m-t-n">Total <span class="m-l-xl m-r-sm">: </span></h4><h3 class="inline"> {{ Staff::all()->count() }}</h3>
    <section class="panel panel-default">
        <header class="panel-heading">
            <div class="pull-right" >
                @if (isXUser('create', 'staffs'))
                    <button class="btn btn-xs btn-default btn-rounded" id="import"><i class="fa fa-download m-l-xs m-r-sm"></i>{{ trans('general.import') }}</button>
                @endif
                <a href="{{ route('staffs.export') }}" class="btn btn-xs btn-default btn-rounded" id="export"><i class="fa fa-print m-l-xs m-r-sm"></i>{{ trans('general.export') }}</a>
            </div>
          <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
        </header>
        <div class="table-responsive">
        <?php
        $options = array(
            array(
                'bVisible' => false,
                'aTargets' => [0]
            ),
            array(
                'sTitle' => trans('user.name'),
                'aTargets' => [1]),
            array(
                'sTitle' => trans('user.e_mail'),
                'aTargets' => [2]),
            array(
                'sTitle' => trans('user.phone'),
                'aTargets' => [3]),
            array(
                'sTitle' => trans('user.base_location'),
                'aTargets' => [4],
                "bSortable" => false),
            array(
                'sTitle' => trans('user.active_user'),
                'aTargets' => [5]
            )
            );
        $columns = array('id','name', 'email','phone','address', 'active_user');
        if($XUserMode) {
            $options[] = array(
                'sTitle' => trans('general.action'),
                'aTargets' => [6],
                'bSortable' => false);
            $columns[] = 'action';
        }

        ?>
        {{-- Specify datatable with custom title and first column (id) hidden --}}
        {{ Datatable::table()
            ->addColumn($columns)
            ->setUrl(route('staffs.index') . '?datatable=1')
            ->setOptions('bProcessing', true)
            ->setOptions('aoColumns', array(
              'sWidth'=>'0%',
              'sWidth'=>'25%',
              'sWidth'=>'15%',
              'sWidth'=>'15%',
              'sWidth'=>'15%',
              'sWidth'=>'15%',
              'sWidth'=>'15%'
            ))
            ->setOptions(['bAutoWidth'=>false])
            ->setOptions('aaSorting', [[1,'asc']])
            ->setOptions('aoColumnDefs',
                $options
            )
            ->render('datatable.basic') }}
    </div>
    </section>

    {{--modal form section for layout--}}
    <section>
        {{--Form Modal layout--}}
        <div class="modal fade" id="modalImport" role="dialog">
            <div class="modal-dialog">
                {{-- Modal Content --}}
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">{{ trans('staff.import_data') }}</h4>
                    </div>
                    <div class="modal-body" style="padding:20px 50px;">
                        <div class="col-sm-12" style="padding-bottom:20px;">
                            <b>{{ trans('staff.step_1') }}</b>
                        </div>
                        <div class="col-sm-12" style="padding-bottom:10px; padding-top:10px;">
                            <a href="{{ route('staffs.importlayout') }}" class="btn btn-success btn-s-sm center-block"><i class="fa fa-download fa-hover"></i> Download Template</a>
                        </div>
                        <div class="col-sm-12" style="padding-bottom:30px; padding-top:20px;">
                            <b>{{ trans('staff.step_2') }}</b>
                        </div>
                        {{ Form::open(array('url' => route('staffs.import'), 'method' => 'post', 'files' => true,
                            'role' => 'form', 'class'=>'form-horizontal', 'parsley-validate', 'novalidate')) }}

                        <div class="col-sm-4">
                            {{ Form::label('excel', 'Upload Excel File') }}
                        </div>
                        <div class="col-sm-8">
                            {{ Form::file('excel', array(
                                    'type'=>'file',
                                    'class'=>'filestyle',
                                    'parsley-required' => 'true',
                                    'required'=>'required',
                                    'data-icon'=>'false',
                                    'data-classButton'=>'btn btn-default',
                                    'data-classInput'=>'form-control inline input-s' )) }}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal">{{ trans('general.cancel') }}</button>
                        <button type="submit" class="btn btn-success btn-default pull-right">{{ trans('general.upload') }}</button>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </section>
@stop

@section('pagejs')
    <script src="{{ asset('js/datatables/jquery.dataTables.min.js') }}" cache="false"></script>

    {{--Show Modal--}}
    <script>
        $(document).ready(function(){
            $("#import").click(function(){
                $("#modalImport").modal();
            });
        });
    </script>
@stop
