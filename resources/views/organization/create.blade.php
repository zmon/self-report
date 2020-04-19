@extends('layouts.crud-master')
@php $nav_path = ['organization']; @endphp
@section('page-title')
    Add New Organizations
@endsection
@section('page-header-title')
    Add New Organizations
@endsection
@section('page-help-link', '#TODO')
@section('page-header-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('organization.index') }}">Organizations</a></li>
        <li class="breadcrumb-item active" aria-current="location">Add New Organizations</li>
    </ol>
@endsection
@section('content')
    <organization-form csrf_token="{{ csrf_token() }}"></organization-form>
@endsection
