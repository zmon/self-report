@extends('layouts.crud-master')
@php $nav_path = ['self-report'] @endphp
@section('page-title')
    Edit {{$self_report->name}}
@endsection
@section('page-header-title')
    Edit {{$self_report->name}}
@endsection
@section('page-help-link', '#TODO')
@section('page-header-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('self-report.index') }}">Self Reports</a></li>
        <li class="breadcrumb-item active" aria-current="location">Edit {{$self_report->name}}</li>
    </ol>
@endsection
@section('content')
    <self-report-form csrf_token="{{ csrf_token() }}" :record='@json($self_report)'></self-report-form>
@endsection
