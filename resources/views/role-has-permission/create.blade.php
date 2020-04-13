@extends('layouts.crud-master')
@php $nav_path = ['role-has-permission'] @endphp
@section('page-title')
    Add New role_has_permissions
@endsection
@section('page-help-link', '#TODO')
@section('page-header-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('role-has-permission.index') }}">role_has_permissions</a></li>
        <li class="breadcrumb-item active" aria-current="location">Add</li>
    </ol>
@endsection
@section('content')
    <role-has-permission-form csrf_token="{{ csrf_token() }}" cancel_url="{{$cancel_url}}"></role-has-permission-form>
@endsection
