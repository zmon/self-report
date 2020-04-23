@extends('layouts.print')
@section('page-title', 'Self Reports')
@section('table-headings-row')
    <tr>
        <th>Name</th>
        <th>Exposed</th>
        <th>State</th>
        <th>Zipcode</th>
        <th>Symptom Start Date</th>
    </tr>
@endsection
@section('table-data-rows')
    @foreach($data as $obj)
        <tr>
            <td>{{ $obj->name }}</td>
            <td>{{ $obj->exposed }}</td>
            <td>{{ $obj->state }}</td>
            <td>{{ $obj->zipcode }}</td>
            <td>{{ $obj->symptom_start_date }}</td>
        </tr>
    @endforeach
@endsection
