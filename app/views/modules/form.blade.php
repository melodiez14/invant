<?php
$editMode   = false;
$formTitle  = trans('module.create_module');
$formAction = route('modules.store');

if(isset($module)) {
    $editMode = true;
    $formTitle  = trans('module.update_module', ['module_name' => $module->module_name]);
    $formAction = route('modules.update', ['modules' => $module->id]);
}
?>
@extends('layouts.dashboard')
@section('breadcrumb')
    <li class="active">{{ trans('reference.references') }}</li>
    <li><a href="{{ route('modules.index') }}">{{ trans('module.modules') }}</a></li>
    <li>{{ $formTitle }}</li>
@stop
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none">{{ $formTitle }}</h3>
</div>
<section class="panel panel-default">
    <div class="panel-heading">
    </div>
    <form class="form panel-body" method="post" action="{{ $formAction }}" id="module-form">
        <div class="form-group">
            <label for="module_alias">{{ trans('module.module_alias') }}</label>
            <input type="text" name="module_alias" class="form-control" value="{{ $editMode ? $module->module_alias : null }}" {{ $editMode ? "disabled=\"disabled\"" : null }} />
        </div>
        <div class="form-group">
            <label for="module_name">{{ trans('module.module_name') }}</label>
            <input type="text" name="module_name" class="form-control" id="module_name" parsley-required="true" placeholder="{{ trans('module.module_name_placeholder') }}" value="{{($editMode) ? $module->module_name : null}}" />
            {{ csrf_field() }}
            {{ ($editMode) ? method_field('PUT') : null }}
        </div>
        <div class="form-group">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="module_core" {{ ($editMode && ($module->module_core == 1)) ? "checked" : null }} /> {{ trans('module.core_question') }}
                </label>
            </div>
        </div>
    </form>
    <div class="panel-footer">
        <a href="{{route('modules.index')}}" class="btn btn-link">&laquo; {{ trans('dashboard.back') }}</a>
        <a href="#" class="btn btn-primary pull-right form-submit" data-rel="module-form"><i class="fa fa-save fa-hover"></i>&nbsp;<span>{{ $editMode ? 'Save' : trans('module.module_save') }}</span></a>
    </div>
</section>
@stop

@section('pagejs')
    <script src="{{ asset('js/parsley/parsley.min.js')}}" cache="false"></script>
    <script src="{{ asset('js/parsley/parsley.extend.js')}}" cache="false"></script>
    <script type="text/javascript">
        $(document).ready(
            function ()
            {
                $('.form-submit').on(
                    {
                        click: function (event)
                        {
                            var rel = $(this).data('rel');
                            $('#' + rel).submit();
                            return false;
                        }
                    }
                );
            }
        );
    </script>
@stop
