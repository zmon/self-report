@extends('layouts.crud-master')
@php $nav_path = ['preexisting-condition'] @endphp
@section('page-title', 'Preexisting Conditions')
@section('page-header-title', 'Preexisting Conditions')
@section('page-help-link', '#TODO')
@section('page-header-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="location">Preexisting Conditions</li>
    </ol>
@endsection
@section('content')
    <preexisting-condition-grid :params="{
        Page: '{{ $page }}',
        Search: '{{ $search }}',
        sortOrder: '{{ $direction }}',
        sortKey: '{{ $column }}',
        CanAdd: '{{ $can_add }}',
        CanEdit: '{{ $can_edit }}',
        CanShow: '{{ $can_show }}',
        CanDelete: '{{ $can_delete }}',
        CanExcel: '{{ $can_excel }}'
        }"></preexisting-condition-grid>
@endsection
