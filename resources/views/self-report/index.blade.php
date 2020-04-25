@extends('layouts.crud-master')
@php $nav_path = ['self-report']; @endphp
@section('page-title', 'SelfReports')
@section('page-header-title', 'SelfReports')
@section('page-help-link', '#TODO')
@section('page-header-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="location">SelfReports</li>
    </ol>
@endsection
@section('content')
    <self-report-grid :params="{
            Page: '{{ $page }}',
            Search: '{{ $search }}',
            sortOrder: '{{ $direction }}',
            sortKey: '{{ $column }}',
            CanAdd: '{{ $can_add }}',
            CanEdit: '{{ $can_edit }}',
            CanShow: '{{ $can_show }}',
            CanDelete: '{{ $can_delete }}',
            CanExcel: '{{ $can_excel }}'
        }"></self-report-grid>
@endsection
