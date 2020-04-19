@extends('layouts.print')
@section('page-title', 'Self Reports')
@section('table-headings-row')
    <tr>
            <th>Name</th>
        </tr>
@endsection
@section('table-data-rows')
    @foreach($data as $obj)
        <tr>
                    <td>{{ $obj->name }}</td>
                </tr>
    @endforeach
@endsection
