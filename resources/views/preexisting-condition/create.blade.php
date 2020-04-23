@extends('layouts.crud-master')
@php $nav_path = ['preexisting-condition'] @endphp
@section('page-title')
    Add New Preexisting Conditions
@endsection
@section('page-header-title')
    Add New Preexisting Conditions
@endsection
@section('page-help-link', '#TODO')
@section('page-header-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('preexisting-condition.index') }}">Preexisting Conditions</a></li>
        <li class="breadcrumb-item active" aria-current="location">Add New Preexisting Conditions</li>
    </ol>
@endsection
@section('content')
    <preexisting-condition-form csrf_token="{{ csrf_token() }}"></preexisting-condition-form>
@endsection
