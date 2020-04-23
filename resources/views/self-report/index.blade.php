@extends('layouts.crud-master')
@php $nav_path = ['self-report'] @endphp
@section('page-title', 'Self Reports')
@section('page-header-title', 'Self Reports')
@section('page-help-link', '#TODO')
@section('page-header-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="location">Self Reports</li>
    </ol>
@endsection
@section('content')
    <self-report-grid :params="{
        Page: '{{ $page }}',
        Search: '{{ $search }}',
        sortOrder: '{{ $direction }}',
        sortKey: '{{ $column }}',
        CanShow: '{{ $can_show }}',
        CanExcel: '{{ $can_excel }}'
        }"></self-report-grid>
@endsection
