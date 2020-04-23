@extends('layouts.crud-master')
@php $nav_path = ['race-ethnicity'] @endphp
@section('page-title', 'Rax Ethnicities')
@section('page-header-title', 'Rax Ethnicities')
@section('page-help-link', '#TODO')
@section('page-header-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="location">Rax Ethnicities</li>
    </ol>
@endsection
@section('content')
    <race-ethnicity-grid :params="{
        Page: '{{ $page }}',
        Search: '{{ $search }}',
        sortOrder: '{{ $direction }}',
        sortKey: '{{ $column }}',
        CanAdd: '{{ $can_add }}',
        CanEdit: '{{ $can_edit }}',
        CanShow: '{{ $can_show }}',
        CanDelete: '{{ $can_delete }}',
        CanExcel: '{{ $can_excel }}'
        }"></race-ethnicity-grid>
@endsection
