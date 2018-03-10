@extends('layouts.dashboard')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('js/datatables/datatables.css') }}" type="text/css" cache="false"/>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
@stop

@section('breadcrumb')
    <li><a href="#">{{trans('user.user_man')}}</a></li>
    <li><a href="#">{{ trans('rolegroup.rolegroups') }}</a></li>
@stop

@section('content')
    <h3>{{ trans('rolegroup.rolegroups') }} <a href="{{ route('rolegroups.create') }}" class="btn btn-xs btn-default btn-rounded"><i class="fa fa-plus m-l-xs m-r-sm"></i>{{ trans('rolegroup.create_rolegroup') }}</a></h3>
    <h4 class="inline text-muted m-t-n">Total <span class="m-l-xl m-r-sm">: </span></h4><h3 class="inline"> {{ $rolegroups->count() }}</h3>
    <section class="panel panel-default">
        <header class="panel-heading">
          <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
        </header>
        <div class="table-responsive">
            {{ Datatable::table()
                ->addColumn('id','rolegroup_name','rolegroup_depth','created_at', 'action')
                ->setUrl(route('rolegroups.index'))
                ->setOptions('bProcessing', true)
                ->setOptions('aoColumnDefs',array(
                    array(
                        'bVisible' => false,
                        'aTargets' => [0]
                    ),
                    array(
                        'sTitle' => trans('rolegroup.name'),
                        'aTargets' => [1]),
                    array(
                        'sTitle' => trans('rolegroup.level'),
                        'aTargets' => [2]),
                    array(
                        'sTitle' => trans('rolegroup.created_at'),
                        'aTargets' => [3]),
                    array(
                        'sTitle' => 'Action',
                        'aTargets' => [4],
                        "bSortable" => false),
                ))
                ->render('datatable.basic') }}
        </div>
    </section>
@stop

@section('pagejs')
    <script src="{{ asset('js/datatables/jquery.dataTables.min.js') }}" cache="false"></script>
@stop
