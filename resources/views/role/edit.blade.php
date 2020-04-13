@extends('layouts.master')
@php $nav_path = ['role'] @endphp
@section('page-title')
    Edit {{$role->name}}
@endsection
@section('page-header-title')
    Edit {{$role->name}}
@endsection
@section('page-help-link', '#TODO')
@section('page-header-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('role.index') }}">Roles</a></li>
        <li class="breadcrumb-item active" aria-current="location">Edit {{$role->name}}</li>
    </ol>
@endsection
@section('content')
    <role-form csrf_token="{{ csrf_token() }}" :record='@json($role)'></role-form>
@endsection
