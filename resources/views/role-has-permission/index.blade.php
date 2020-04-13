@extends('layouts.crud-master')
@php $nav_path = ['role-has-permission'] @endphp
@section('page-title', 'role_has_permissions')
@section('page-header-title', 'role_has_permissions')
@section('page-help-link', '#TODO')
@section('page-header-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="location">role_has_permissions</li>
    </ol>
@endsection
@section('content')
    <role-has-permission-grid :params="{
        Page: '{{ $page }}',
        Search: '{{ $search }}',
        sortOrder: '{{ $direction }}',
        sortKey: '{{ $column }}',
        CanAdd: '{{ $can_add }}',
        CanEdit: '{{ $can_edit }}',
        CanShow: '{{ $can_show }}',
        CanDelete: '{{ $can_delete }}',
        CanExcel: '{{ $can_excel }}'
        }"></role-has-permission-grid>
@endsection
