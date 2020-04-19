@extends('layouts.crud-master')
@php $nav_path = ['organization']; @endphp
@section('page-title')
    Edit {{$organization->name}}
@endsection
@section('page-header-title')
    Edit {{$organization->name}}
@endsection
@section('page-help-link', '#TODO')
@section('page-header-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('organization.index') }}">Organizations</a></li>
        <li class="breadcrumb-item active" aria-current="location">Edit {{$organization->name}}</li>
    </ol>
@endsection
@section('content')
    <organization-form csrf_token="{{ csrf_token() }}" :record='@json($organization)'></organization-form>
@endsection
