<?php

$edit   = false;
$action = route('rolegroups.store');
$method = null;
$title  = trans('rolegroup.create_rolegroup');
$number = 1;

if (isset($rolegroup)) {
    $edit   = true;
    $method = method_field('PUT');
    $action = route('rolegroups.update', ['rolegroups' => $rolegroup->id]);
    $title  = trans('rolegroup.edit', ['name' => $rolegroup->rolegroup_name]);
}
?>
@extends('layouts.dashboard')
@section('breadcrumb')
    <li><a href="{{ route('rolegroups.index') }}">{{ trans('rolegroup.rolegroups') }}</a></li>
    <li>{{ $title }}</li>
@stop
@section('pagecss')
<link rel="stylesheet" href="{{ asset('js/select2/select2.css') }}" type="text/css" cache="false" />
<link rel="stylesheet" href="{{ asset('js/select2/theme.css') }}" type="text/css" cache="false" />
@stop
@section('content')
<div class="m-b-md">
    <h3 class="m-b-none">{{ $title }}</h3>
</div>
<section class="panel panel-default" id="modules-data-container" data-modules='{{ ($edit) ? $mods_json : null }}'>
    <div class="panel-heading">
    </div>
    <form class="form panel-body" method="post" action="{{ $action }}" id="rolegroup-form" data-edit="{{($edit) ? 1 : 0}}" parsley-validate>
        {{ $method }}
        {{ csrf_field() }}
        <div class="form-group required">
            <label for="rolegroup_name" class="control-label"><b>{{trans('rolegroup.name')}}</b></label>
            <input type="text" class="form-control" id="rolegroup_name" name="rolegroup_name" parsley-required="true" value="{{ ($edit) ? $rolegroup->rolegroup_name : Input::old('rolegroup_name') }}" />
        </div>
        <div class="form-group required">
            <label for="rolegroup_depth" class="control-label"><b>{{trans('rolegroup.level')}}</b></label>
            <input type="number" min="1" class="form-control" id="rolegroup_depth" name="rolegroup_depth" parsley-required="true" value="{{ (Input::old('rolegroup_depth') !== null) ? Input::old('rolegroup_depth') : (($edit) ? $rolegroup->rolegroup_depth  : 1) }}" />
        </div>
        <!-- <div class="form-group"> -->
            <label><b>{{ trans('role.roles') }}</b></label>
            <div class="container-fluid dynamic-row-grid" data-maxsequence="{{ ($edit) ? ($sequence + 1) : 2 }}">
                <div class="row dynamic-row-header">
                    <div class="col-md-6">{{ trans('module.module_name') }}</div>
                    <div class="col-md-5">{{ trans('role.abilities') }}</div>
                    <div class="col-md-1">
                        <button type="button" data-url="{{ route('modules.index') }}" class="btn btn-success add-row" id="add-row"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                @if($edit)
                    @foreach($roles as $idx => $role)
                    <div class="row dynamic-row">
                        <div class="col-md-6">
                            <select class="select2-combo js-states" name="module_id[]" id="{{ "module_" . $number }}" data-alias="module" data-rel="{{ "abilities_" . $number }}" style="width: 100%">
                                @foreach($modules as $idMod => $mod)
                                <option value="{{$mod->id}}" {{ ($mod->id == $role['module_id']) ? "selected=\"selected\"" : null }}>{{$mod->module_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <select class="select2-combo js-states" multiple="multiple" name="abilities[]" id="{{ "abilities_" . $number }}" data-alias="ability" data-rel="{{ "module_" . $number }}" style="width: 100%">
                                <option value="READ" {{ (in_array("READ", $role['abilities'])) ? "selected=\"selected\"" : null }}>Read</option>
                                <option value="CREATE" {{ (in_array("CREATE", $role['abilities'])) ? "selected=\"selected\"" : null }}>Create</option>
                                <option value="UPDATE" {{ (in_array("UPDATE", $role['abilities'])) ? "selected=\"selected\"" : null }}>Update</option>
                                <option value="DELETE" {{ (in_array("DELETE", $role['abilities'])) ? "selected=\"selected\"" : null }}>Delete</option>
                                <option value="XREAD" {{ (in_array("XREAD", $role['abilities'])) ? "selected=\"selected\"" : null }}>Extra Read</option>
                                <option value="XCREATE" {{ (in_array("XCREATE", $role['abilities'])) ? "selected=\"selected\"" : null }}>Extra Create</option>
                                <option value="XUPDATE" {{ (in_array("XUPDATE", $role['abilities'])) ? "selected=\"selected\"" : null }}>Extra Update</option>
                                <option value="XDELETE" {{ (in_array("XDELETE", $role['abilities'])) ? "selected=\"selected\"" : null }}>Extra Delete</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger delete-row" onClick="deleteRow(this)"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <?php $number += 1; ?>
                    @endforeach
                @else
                <div class="row dynamic-row">
                    <div class="col-md-6">
                        <select class="select2-combo js-states" name="module_id[]" id="module_1" data-alias="module" data-rel="abilities_1" style="width: 100%">
                        </select>
                    </div>
                    <div class="col-md-5">
                        <select class="select2-combo js-states" multiple="multiple" name="abilities[]" id="abilities_1" data-alias="ability" data-rel="module_1" style="width: 100%">
                            <option value="READ">Read</option>
                            <option value="CREATE">Create</option>
                            <option value="UPDATE">Update</option>
                            <option value="DELETE">Delete</option>
                            <option value="XREAD">Extra Read</option>
                            <option value="XCREATE">Extra Create</option>
                            <option value="XUPDATE">Extra Update</option>
                            <option value="XDELETE">Extra Delete</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger delete-row" onClick="deleteRow(this)"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                @endif
                <div class="dynamic-row-limit">
                    <input type="hidden" name="roles" id="roles" data-value='[]' value='{{ ($edit) ? json_encode($roles) : null }}' />
                </div>
            </div>
        <!-- </div> -->
    </form>
    <div class="panel-footer">
        <a href="#" class="btn btn-primary pull-right form-submit" data-rel="rolegroup-form"><i></i>&nbsp;<span>{{ trans('module.module_save') }}</span></a>
        <a href="{{route('rolegroups.index')}}" class="btn btn-link pull-right">Cancel</a>
    </div>
</div>
@stop
@section('pagejs')
    <script type="text/javascript" src="{{ asset('js/select2/select2.min.js') }}" cache="false"></script>

    <!-- parsley -->
    <script src="{{ asset('js/parsley/parsley.min.js')}}" cache="false"></script>
    <script src="{{ asset('js/parsley/parsley.extend.js')}}" cache="false"></script>

    <script type="text/javascript">
        function deleteRow(btn)
        {
            var $obj    = $(btn),
                $parent = $obj.parent(),
                $row    = $parent.parent(),
                $inc    = $('.dynamic-row-grid').data('maxsequence'),
                $modSel = $row.find('.select2-combo')[0],
                modVal  = $($modSel).select2('val');

            removeRolesRecord( isRoleExist( parseInt(modVal) ) );

            $row.remove();
            $('.dynamic-row-grid').data('maxsequence', ($inc - 1));
        }

        function getModulesOptions(tgt, select)
        {
            var selectObj   = $(select[0]),
                urlSource   = tgt.data('url'),
                currentData = $('#modules-data-container').data('modules');

            if ( (typeof currentData === 'undefined') || (currentData === null) || (currentData === '') || $.isEmptyObject(currentData) ) {
                $.ajax({
                    url : urlSource,
                    type: 'GET',
                    dataType: 'json'
                }).then(
                    function(data, success, xhr)
                    {
                        if (success) {
                            $('#modules-data-container').data('modules', data);
                            getModulesOptions(tgt, select);
                        }
                    }
                );
            } else {

                initSelect(selectObj);

            }
            /**/
        }

        function initSelect(tgt)
        {
            var modules = $('#modules-data-container').data('modules');

            $.each(modules, function(idx, obj) {
                tgt.append('<option value="' +obj.id+ '">' + obj.module_name + '</option>');
            });

            tgt.select2();
            tgt.on({
                change: onSelectChange
            });

        }

        function onSelectChange(evt)
        {
            var select  = $(evt.currentTarget),
                type    = select.data('alias'),
                roles   = $('#roles').data('value'),
                value   = evt.val,
                relId   = select.data('rel'),
                relation= $('#' + relId),
                relValue= relation.select2('val');

            if (type === 'module') {

                value   = parseInt(value);

                var idx     = isRoleExist(value);

                if (idx < 0) {
                    roles.push({
                        module_id: value,
                        abilities: ($.isEmptyObject(relValue)) ? [] : relValue
                    });
                } else {
                    roles[idx].abilities = ($.isEmptyObject(relValue)) ? [] : relValue;
                }

            } else {

                var modVal  = parseInt(relValue),
                    idx     = isRoleExist(modVal);

                if( (idx > -1) && ((value != '') || (typeof value != null) || ($.isEmptyObject(value) == true)) ) {
                    roles[idx] = {
                        module_id: modVal,
                        abilities: value
                    }
                } else {
                    if ((value != '') || (typeof value != null) || ($.isEmptyObject(value) == true)) {
                        roles.push({
                            module_id: modVal,
                            abilities: value
                        });
                    }
                }

            }

        }

        function isRoleExist(module) {

            var roles   = $('#roles').data('value'),
                found   = -1;

            $.each(roles, function(idx, obj) {

                if(obj.module_id === module) {
                    found = idx;
                }

            });

            return found;

        }

        function removeRolesRecord(idx) {

            var roles = $('#roles').data('value');

            if ( (idx >= 0) && ($.isEmptyObject(roles) == false) ) {
                roles = roles.splice(idx, 1);
            }
        }

        $(document).ready(
            function ()
            {

                var inc = $('.dynamic-row-grid').data('maxsequence') || 1;

                if ($('#rolegroup-form').data('edit') == 1){

                    $('.select2-combo').select2();
                    var curRoles = $('#roles').val();
                    $('#roles').data('value', JSON.parse(curRoles));

                } else {
                    getModulesOptions($('#add-row'), $('#module_1'));
                    $('#abilities_1').select2();
                }

                $('.select2-combo').on({
                    change: onSelectChange
                });

                $('.form-submit').on(
                    {
                        click: function (event)
                        {
                            var rel = $(this).data('rel'),
                                roles= $('#roles').data('value');
                            $('#roles').val(JSON.stringify(roles));
                            $('#' + rel).submit();

                            return false;
                        }
                    }
                );

                $('.add-row').on(
                    {
                        click: function(event)
                        {
                            var html = "<div class=\"row dynamic-row\">" +
                                            "<div class=\"col-md-6\"><select class=\"select2-combo js-states\" name=\"module_id[]\" id=\"module_" +inc+ "\" data-alias=\"module\" data-rel=\"abilities_"+inc+"\" style=\"width: 100%\"></select></div>" +
                                            "<div class=\"col-md-5\">" +
                                                "<select class=\"select2-combo js-states\" multiple=\"multiple\" name=\"abilities[]\" id=\"abilities_" +inc+ "\" data-alias=\"ability\" data-rel=\"module_" +inc+ "\" style=\"width: 100%\">" +
                                                    "<option value=\"READ\">Read</option>" +
                                                    "<option value=\"CREATE\">Create</option>" +
                                                    "<option value=\"UPDATE\">Update</option>" +
                                                    "<option value=\"DELETE\">Delete</option>" +
                                                    "<option value=\"XREAD\">Extra Read</option>" +
                                                    "<option value=\"XCREATE\">Extra Create</option>" +
                                                    "<option value=\"XUPDATE\">Extra Update</option>" +
                                                    "<option value=\"XDELETE\">Extra Delete</option>" +
                                                "</select>" +
                                            "</div>" +
                                            "<div class=\"col-md-1\">" +
                                                "<button type=\"button\" class=\"btn btn-danger delete-row\" onClick=\"deleteRow(this)\"><i class=\"fa fa-minus\"></i></button>" +
                                            "</div>" +
                                        "</div>",
                                obj  = $(html),
                                limit= $('.dynamic-row-limit');

                            limit.before(obj);

                            $('#abilities_' + inc).select2();
                            getModulesOptions($(this), $('#module_' + inc));

                            $('.select2-combo').on({
                                change: onSelectChange
                            });

                            inc += 1;

                            $('.dynamic-row-grid').data('maxsequence', inc);
                            return false;

                        }
                    }
                )
            }
        );
    </script>
@stop
