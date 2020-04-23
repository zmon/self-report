@extends('layouts.print')
@section('page-title', 'Users')
@section('table-headings-row')
    <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Active</th>
        </tr>
@endsection
@section('table-data-rows')
    @foreach($data as $obj)
        <tr>
                    <td>{{ $obj->name }}</td>
                    <td>{{ $obj->email }}</td>
                    <td>{{ $obj->active }}</td>
                </tr>
    @endforeach
@endsection
