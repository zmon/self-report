@extends('layouts.crud-master')
@php $nav_path = ['self-report']; @endphp
@section('page-title')
    Add New SelfReports
@endsection
@section('page-header-title')
    Add New SelfReports
@endsection
@section('page-help-link', '#TODO')
@section('page-header-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('self-report.index') }}">SelfReports</a></li>
        <li class="breadcrumb-item active" aria-current="location">Add New SelfReports</li>
    </ol>
@endsection
@section('content')
    <self-report-form csrf_token="{{ csrf_token() }}"></self-report-form>
@endsection
