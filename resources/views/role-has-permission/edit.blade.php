@extends('layouts.crud-master')
@php $nav_path = ['role-has-permission'] @endphp
@section('page-title')
    Edit {{$role_has_permission->name}}
@endsection
@section('page-help-link', '#TODO')
@section('page-header-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('role-has-permission.index') }}">role_has_permissions</a></li>
        <li class="breadcrumb-item">
            <a href="{{ route('role-has-permission.show',['role_has_permission' => $role_has_permission->id ] ) }}">{{$role_has_permission->name}}</a>
        </li>
        <li class="breadcrumb-item active" aria-current="location">Edit</li>
    </ol>
@endsection
@section('content')
    <role-has-permission-form csrf_token="{{ csrf_token() }}" cancel_url="{{$cancel_url}}"
                              :record='@json($role_has_permission)'></role-has-permission-form>
@endsection
