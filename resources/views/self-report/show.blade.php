@extends('layouts.crud-master')
@php $nav_path = ['self-report']; @endphp
@section('page-title')
View {{$self_report->name}}
@endsection
@section('page-header-title')
View
@endsection
@section('page-header-breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('self-report.index') }}">SelfReports</a></li>
    <li class="breadcrumb-item active" aria-current="location">View </li>
</ol>
@endsection
@section('content')

    <self-report-show :record='@json($self_report)'></self-report-show>

    <div class="row">
        <div class="col-md-12 text-md-center mt-2 mt-md-0">

                    <a href="{{ url('/self-report') }}" class="btn btn-default">Return to List</a>
                </div>
            </div>


@endsection
