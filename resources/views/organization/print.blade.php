@extends('layouts.print')
@section('page-title', 'Organizations')
@section('table-headings-row')
    <tr>
            <th>Name</th>
            <th>Alias</th>
        </tr>
@endsection
@section('table-data-rows')
    @foreach($data as $obj)
        <tr>
                    <td>{{ $obj->name }}</td>
                    <td>{{ $obj->alias }}</td>
                </tr>
    @endforeach
@endsection
