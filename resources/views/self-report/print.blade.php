@extends('layouts.print')
@section('page-title', 'SelfReports')
@section('table-headings-row')
    <tr>
            <th>Organization Id</th>
            <th>Name</th>
            <th>State</th>
            <th>Zipcode</th>
            <th>Symptom Start Date</th>
            <th>County Calc</th>
            <th>Form Received At</th>
        </tr>
@endsection
@section('table-data-rows')
    @foreach($data as $obj)
        <tr>
                    <td>{{ $obj->organization_id }}</td>
                    <td>{{ $obj->name }}</td>
                    <td>{{ $obj->state }}</td>
                    <td>{{ $obj->zipcode }}</td>
                    <td>{{ $obj->symptom_start_date }}</td>
                    <td>{{ $obj->county_calc }}</td>
                    <td>{{ $obj->form_received_at }}</td>
                </tr>
    @endforeach
@endsection
