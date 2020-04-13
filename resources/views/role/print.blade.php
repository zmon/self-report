@extends('layouts.print')
@section('page-title', 'Roles')
@section('table-headings-row')
    <tr>
        <th>Name</th>
        <th>Can Assign</th>
    </tr>
@endsection
@section('table-data-rows')
    @foreach($data as $obj)
        <tr>
            <td>{{ $obj->name }}</td>
            <td>{{ $obj->can_assign }}</td>
        </tr>
    @endforeach
@endsection
