@extends('layouts.crud-master')
@php $nav_path = ['race-ethnicity'] @endphp
@section('page-title')
    Edit {{$race_ethnicity->name}}
@endsection
@section('page-header-title')
    Edit {{$race_ethnicity->name}}
@endsection
@section('page-help-link', '#TODO')
@section('page-header-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('race-ethnicity.index') }}">Rax Ethnicities</a></li>
        <li class="breadcrumb-item active" aria-current="location">Edit {{$race_ethnicity->name}}</li>
    </ol>
@endsection
@section('content')
    <race-ethnicity-form csrf_token="{{ csrf_token() }}" :record='@json($race_ethnicity)'></race-ethnicity-form>
@endsection
