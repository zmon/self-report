@extends('layouts.crud-master')
@php $nav_path = ['symptom'] @endphp
@section('page-title')
    View {{$symptom->name}}
@endsection
@section('page-header-title')
    View {{$symptom->name}}
@endsection
@section('page-header-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('symptom.index') }}">Symptoms</a></li>
        <li class="breadcrumb-item active" aria-current="location">View {{$symptom->name}}</li>
    </ol>
@endsection
@section('content')

    <symptom-show :record='@json($symptom)'></symptom-show>

    <div class="row">
        <div class="col-md-12">
            <div class="row mt-4">
                <div class="col-md-4">
                    @if ($can_edit)
                        <a href="/symptom/{{ $symptom->id }}/edit" class="btn btn-primary">Edit Symptoms</a>
                    @endif
                </div>
                <div class="col-md-4 text-md-center mt-2 mt-md-0">
                    @if ($can_delete)
                        <form class="form" role="form" method="POST" action="/symptom/{{ $symptom->id }}">
                            <input type="hidden" name="_method" value="delete">
                            {{ csrf_field() }}

                            <input class="btn btn-danger" Onclick="return ConfirmDelete();" type="submit"
                                   value="Delete Symptoms">

                        </form>
                    @endif
                </div>
                <div class="col-md-4 text-md-right mt-2 mt-md-0">
                    <a href="{{ url('/symptom') }}" class="btn btn-default">Return to List</a>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('scripts')
    <script>
        function ConfirmDelete() {
            var x = confirm("Are you sure you want to delete this Symptoms?");
            if (x)
                return true;
            else
                return false;
        }
    </script>
@endsection
