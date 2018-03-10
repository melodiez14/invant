<?php

$edit = false;
$action = route('staffs.store');
$title  = trans('staff.create');

if(isset($staff)) {
    $edit   = true;
    $action = route('staffs.update', ['staffs' => $staff->id]);
    $title  = trans('staff.update', ['name' => $staff->name]);
}

?>

@extends('layouts.dashboard')
@section('pagecss')
    <link rel="stylesheet" href="{{ asset('js/fuelux/fuelux.css') }}" type="text/css" cache="false" />
@stop
@section('breadcrumb')
    <li><a href="#">{{trans('user.user_man')}}</a></li>
    <li><a href="{{ route('staffs.index') }}">{{trans('staff.staffs')}}</a></li>
    <li class="active">{{$title}}</li>
@stop

@section('content')
    <div class="m-b-md">
        <h3 class="m-b-none">{{$title}}</h3>
    </div>
    <section class="panel panel-default">
        <header class="panel-heading font-bold">
            {{$title}}
        </header>
        <form class="form panel-body" id="staff-form" method="POST" action="{{$action}}" enctype="multipart/form-data">
            <div class="col-md-2">
                <img class="img" width="90" height="90" src="{{($edit && (!empty($staff->photo_id))) ? route('uploads.show', ['uploads' => $staff->photo_id]) : asset('images/avatar_default.jpg')}}">
                <div class="form-group">
                    <label for="file">{{trans('staff.upload_avatar')}}</label>
                    <input type="file" name="file" id="file">
                </div>
            </div>
            <div class="col-md-10">
                {{csrf_field()}}
                @if($edit)
                    {{method_field('PUT')}}
                @endif
                @if(!empty($user))
                    <input type="hidden" name="user_id" value="{{ $user }}" />
                @endif
                <div class="form-group required">
                    <label for="name" class="control-label"><b>{{trans('staff.name')}}</b></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{$edit ? $staff->name : Input::old('name') }}">
                </div>
                <div class="form-group required">
                    <label for="email" class="control-label"><b>{{trans('staff.email')}}</b></label>
                    <input type="email" name="email" id="email" class="form-control" value="{{$edit ? $staff->email : Input::old('email')}}">
                </div>
                <div class="form-group required">
                    <label class="control-label"><b>{{trans('staff.sex')}}</b></label>
                    <br>
                    <label><input type="radio" name="sex_id" id="sex_id_1" value="1" {{($edit && ($staff->sex_id == 1)) ? "checked" : null}}>&nbsp;{{trans('staff.male')}}</label>
                    <label><input type="radio" name="sex_id" id="sex_id_2" value="2" {{($edit && ($staff->sex_id == 2)) ? "checked" : null}}>&nbsp;{{trans('staff.female')}}</label>
                </div>
                <div class="form-group">
                    <label for="address">{{trans('staff.address')}}</label>
                    <textarea name="address" id="address" cols="30" class="form-control">{{$edit ? $staff->address : Input::old('address')}}</textarea>
                </div>
                <div class="form-group">
                    <label for="phone">{{trans('staff.phone')}}</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{$edit ? $staff->phone : Input::old('phone')}}">
                </div>
            </div>
        </form>
        <div class="panel-footer">
            <button class="btn btn-primary pull-right form-submit" data-rel="staff-form">{{ $edit ? 'Save' : 'Register' }}</button>
            <a href="{{route('staffs.index')}}" class="btn btn-link pull-right">Cancel</a>
        </div>
    </section>
@stop

@section('pagejs')
    <!-- file input -->
    <script src="{{ asset('js/file-input/bootstrap-filestyle.min.js') }}" cache="false"></script>
    <!-- parsley -->
    <script src="{{ asset('js/parsley/parsley.min.js')}}" cache="false"></script>
    <script src="{{ asset('js/parsley/parsley.extend.js')}}" cache="false"></script>
    <script>
        $(document).ready(function() {
            $('.form-submit').on('click', function() {
                var rel = $(this).data('rel');
                $('#' + rel).submit();
            })
        });
    </script>
@stop
