@extends('layouts.dashboard')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('js/datatables/datatables.css') }}" type="text/css" cache="false"/>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
@stop

@section('breadcrumb')
    <li><a href="#">{{trans('user.user_man')}}</a></li>
    <li class="active">{{trans('user.accounts')}}</li>
@stop

@section('content')
    <h3>{{ trans('user.accounts') }}
        @if(isXUser('create', 'users'))
            <a href="{{ route('users.create') }}" class="btn btn-xs btn-default btn-rounded"><i class="fa fa-user m-l-xs m-r-sm"></i>{{trans('user.create_user')}}</a>
        @endif
    </h3>

    <section class="panel panel-default">
        <header class="panel-heading">

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
                'sTitle' => 'Username',
                'aTargets' => [1]),
            array(
                'sTitle' => trans('user.rolegroup'),
                'aTargets' => [2]),
            array(
                'sTitle' => trans('staff.name'),
                'aTargets' => [3]),
            array(
                'sTitle' => trans('user.is_active'),
                'aTargets' => [4]
            )
        );
        $columns = array('id','email','rolegroup','name', 'active');

        if($XUserMode) {
            $options[] = array(
                'sTitle' => trans('general.action'),
                'aTargets' => [5],
                'bSortable' => false);
            $columns[] = 'action';
        }

        ?>
        {{-- Specify datatable with custom title and first column (id) hidden --}}
        {{ Datatable::table()
            ->addColumn($columns)
            ->setUrl(route('users.index') . '?datatable=1')
            ->setOptions('bProcessing', true)
            ->setOptions('aoColumns', array(
              'sWidth'=>'0%',
              'sWidth'=>'25%',
              'sWidth'=>'20%',
              'sWidth'=>'20%',
              'sWidth'=>'20%',
              'sWidth'=>'15%'
            ))
            ->setOptions(['bAutoWidth'=>false])
            ->setOptions('aoColumnDefs',
                $options
            )
            ->render('datatable.basic') }}
    </div>
    </section>
@stop

@section('pagejs')
    <script src="{{ asset('js/datatables/jquery.dataTables.min.js') }}" cache="false"></script>
@stop
