@extends('layouts.crud-master')
@php $nav_path = ['symptom']; @endphp
@section('page-title')
    Edit {{$symptom->name}}
@endsection
@section('page-header-title')
    Edit {{$symptom->name}}
@endsection
@section('page-help-link', '#TODO')
@section('page-header-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('symptom.index') }}">Symptoms</a></li>
        <li class="breadcrumb-item active" aria-current="location">Edit {{$symptom->name}}</li>
    </ol>
@endsection
@section('content')
    <symptom-form csrf_token="{{ csrf_token() }}" :record='@json($symptom)'></symptom-form>
@endsection
