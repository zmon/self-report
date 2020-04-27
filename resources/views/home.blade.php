@extends('layouts.crud-master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <self-report-summary
                        :records='@json($organization_summary)'
                    ></self-report-summary>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
