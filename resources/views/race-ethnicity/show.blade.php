@extends('layouts.crud-master')
@php $nav_path = ['race-ethnicity'] @endphp
@section('page-title')
    View {{$race_ethnicity->name}}
@endsection
@section('page-header-title')
    View {{$race_ethnicity->name}}
@endsection
@section('page-header-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('race-ethnicity.index') }}">Rax Ethnicities</a></li>
        <li class="breadcrumb-item active" aria-current="location">View {{$race_ethnicity->name}}</li>
    </ol>
@endsection
@section('content')

    <race-ethnicity-show :record='@json($race_ethnicity)'></race-ethnicity-show>

    <div class="row">
        <div class="col-md-12">
            <div class="row mt-4">
                <div class="col-md-4">
                    @if ($can_edit)
                        <a href="/race-ethnicity/{{ $race_ethnicity->id }}/edit" class="btn btn-primary">Edit Rax
                            Ethnicity</a>
                    @endif
                </div>
                <div class="col-md-4 text-md-center mt-2 mt-md-0">
                    @if ($can_delete)
                        <form class="form" role="form" method="POST" action="/race-ethnicity/{{ $race_ethnicity->id }}">
                            <input type="hidden" name="_method" value="delete">
                            {{ csrf_field() }}

                            <input class="btn btn-danger" Onclick="return ConfirmDelete();" type="submit"
                                   value="Delete Rax Ethnicity">

                        </form>
                    @endif
                </div>
                <div class="col-md-4 text-md-right mt-2 mt-md-0">
                    <a href="{{ url('/race-ethnicity') }}" class="btn btn-default">Return to List</a>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('scripts')
    <script>
        function ConfirmDelete() {
            var x = confirm("Are you sure you want to delete this Rax Ethnicity?");
            if (x)
                return true;
            else
                return false;
        }
    </script>
@endsection
