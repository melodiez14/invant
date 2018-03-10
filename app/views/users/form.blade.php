<?php

$edit = isset($user);
$action = route('users.store');
$title  = trans('user.create');

if ($edit) {
    $action = route('users.update', ['users' => $user->id]);
    $title = trans('user.update_noprofile');
}

?>
@extends('layouts.dashboard')

@section('pagecss')
<link rel="stylesheet" href="{{asset('js/select2/select2.css')}}">
@stop

@section('breadcrumb')
    <li><a href="#">{{ trans('user.user_man') }}</a></li>
    <li><a href="{{ route('users.index') }}">{{trans('user.accounts')}}</a></li>
    <li class="active">{{ $title }}</li>
@stop

@section('content')
<section class="panel panel-default">
    <div class="panel-heading">
        {{ $title }}
    </div>
    <div class="panel-body">
        <form action="{{ $action }}" method="post" class="form" id="user-form">
            {{ csrf_field() }}
            @if($edit)
                {{ method_field("PUT") }}
            @endif
            <div class="form-group required">
                <label for="email" class="control-label"><b>{{ trans('user.email') }}</b></label>
                <input type="email" name="email" id="email" class="form-control" value="{{ $edit ? $user->email : Input::old('email') }}" placeholder="Input Email Address">
            </div>
            <div class="form-group required">
                <label for="password" class="control-label"><b>{{ trans('user.password') }}</b></label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <div class="form-group required">
                <label for="password_confirmation" class="control-label"><b>{{ trans('user.password_confirmation') }}</b></label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            </div>
            @if(!$owned)
                <div class="form-group required">
                    <label class="control-label"><b>{{trans('user.is_active')}}</b></label>
                    <br>
                    <label><input type="radio" name="is_active" id="is_active_0" value="0" {{ (($edit && ($user->is_active == 0)) || (Input::old('is_active') == 0)) ? "checked" : null  }}>&nbsp;{{trans('user.inactive')}}</label>
                    <label><input type="radio" name="is_active" id="is_active_1" value="1" {{ (($edit && ($user->is_active == 1)) || (Input::old('is_active') == 1)) ? "checked" : null  }}>&nbsp;{{trans('user.active')}}</label>
                </div>
                <div class="form-group required">
                    <label for="rolegroup_id" class="control-label"><b>{{ trans('user.rolegroup') }}</b></label>
                    <select name="rolegroup_id" id="rolegroup_id" class="select2-combo">
                        <option value="" selected>--{{ trans('user.select_rolegroup') }}--</option>
                        @foreach($rolegroups as $rg)
                            @if(($edit && ($rg->id === $user->rolegroup_id)) || (Input::old('rolegroup_id') == $rg->id))
                                <option value="{{ $rg->id }}" selected>{{ $rg->rolegroup_name }}</option>
                            @else
                                <option value="{{ $rg->id }}">{{ $rg->rolegroup_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div id="profile-form" style="display: none">
                    <div class="line line-dashed line-lg pull-in"></div>
                    <div class="form-group required">
                        <label for="profile_name" class="control-label"><b>{{ trans('staff.name') }}</b></label>
                        <input type="text" name="profile_name" id="profile_name" class="form-control" value="{{Input::old('profile_name')}}">
                    </div>
                    <div class="form-group required">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="profile_email" class="control-label"><b>{{ trans('staff.email') }}</b></label>
                                <input type="email" name="profile_email" id="profile_email" class="form-control" value="{{Input::old('profile_email')}}">
                            </div>
                            <div class="col-md-6">
                                <div style="margin-top: 30px">
                                    <input type="checkbox" name="profile_email_check" id="profile_email_check">&nbsp;
                                    <label for="profile_email_check">{{ trans('staff.email_check') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="control-label"><b>{{trans('staff.sex')}}</b></label>
                        <br>
                        <label><input type="radio" name="sex_id" id="sex_id_1" value="1" {{(Input::old('sex_id') == 1) ? "checked" : null}}>&nbsp;{{trans('staff.male')}}</label>
                        <label><input type="radio" name="sex_id" id="sex_id_2" value="2" {{(Input::old('sex_id') == 2) ? "checked" : null}}>&nbsp;{{trans('staff.female')}}</label>
                    </div>
                    <div class="form-group">
                        <label for="profile_address">{{trans('staff.address')}}</label>
                        <textarea name="profile_address" id="profile_address" cols="30" class="form-control">{{Input::old('profile_address')}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="profile_phone">{{trans('staff.phone')}}</label>
                        <input type="text" name="profile_phone" id="profile_phone" class="form-control" value="{{Input::old('profile_phone')}}">
                    </div>
                </div>
            @endif
        </form>
    </div>
    <div class="panel-footer">
        <button class="btn btn-primary pull-right form-submit" data-rel="user-form">{{ $edit ? 'Save' : 'Register' }}</button>
        <a href="{{ route('users.index') }}" class="btn btn-link pull-right">Cancel</a>
    </div>
</section>

@stop

@section('pagejs')
    <script type="text/javascript" src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script type="text/javascript">

        $(document).ready(function() {

            $('.select2-combo').select2();

            // if($('#profile_id')) {
            //     var profile_id = $('#profile_id').val();

            //     if(profile_id != 0) {
            //         $('#profile-form').hide();
            //     } else {
            //         $('#profile-form').show();
            //     }
            //     $('#profile_id').on({
            //         change: function(event)
            //         {
            //             var value   = $(this).val(),
            //                 profileForm = $('#profile-form');

            //             if(value != 0) {
            //                 // load staffs detail
            //                 $.ajax({
            //                     type: 'GET',
            //                     url: '{{ route('api.staffdetails') }}',
            //                     dataType: 'html',
            //                     data: {
            //                         staff_id:value
            //                     }
            //                 }).done(function(data){
            //                     // get JSON
            //                     data = $.parseJSON(data);
            //                     $.each(data, function(i, item) {
            //                         // replace value
            //                         $('#email').val(data[i].email);
            //                     });
            //                 });
                            
            //                 $('#email').attr('readonly', true);
            //                 profileForm.hide();
            //                 return;
            //             }

            //             $('#email').removeAttr('readonly');
            //             $('#email').val(null);
            //             profileForm.show();
            //         }
            //     });
            // }

            // $('.form-submit').on({
            //     click: function()
            //     {
            //         var rel = $(this).data('rel');

            //         $('#' + rel).submit();
            //     }
            // });

            // $('#profile_email_check').on({
            //     change: function(event)
            //     {
            //         var userMail= $('#email').val(),
            //             copy    = $(this).prop('checked');
            //         $('#profile_email').removeAttr('readonly');
            //         $('#profile_email').val(null);

            //         if(copy){
            //             $('#profile_email').val(userMail);
            //             $('#profile_email').attr('readonly', true);
            //         }

            //     }
            // });


        });
    </script>
@stop
