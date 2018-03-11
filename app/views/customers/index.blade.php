@extends('layouts.dashboard')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('js/datatables/datatables.min.css') }}" type="text/css" cache="false"/>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
@stop

@section('breadcrumb')
    <li class="active">{{trans('customer.module_title')}}</li>
@stop

@section('content')
    <h3>{{ trans('customer.module_title') }}
        @if($customer_access->create)
            <a href="{{ route('customers.create') }}" class="btn btn-xs btn-default btn-rounded"><i class="fa fa-user m-l-xs m-r-sm"></i>{{trans('customer.create_customer')}}</a>
        @endif
    </h3>

    <section class="panel panel-default">
        <header class="panel-heading">
            <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
        </header>
        <div class="table-responsive p-15">
            <table id="users-table" class="table" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                    </tr>
                </thead>
            </table>
        </div>
    </section>
@stop

@section('pagejs')
    <script src="{{ asset('js/datatables/datatables.min.js') }}" cache="false"></script>
    <script>
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/api/customers',
            scrollY:        null,
            deferRender:    true,
            scroller:       true
        });
    </script>
@stop
