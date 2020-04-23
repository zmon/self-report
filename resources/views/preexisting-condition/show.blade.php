@extends('layouts.crud-master')
@php $nav_path = ['preexisting-condition']; @endphp
@section('page-title')
View {{$preexisting_condition->name}}
@endsection
@section('page-header-title')
View {{$preexisting_condition->name}}
@endsection
@section('page-header-breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('preexisting-condition.index') }}">Preexisting Conditions</a></li>
    <li class="breadcrumb-item active" aria-current="location">View {{$preexisting_condition->name}}</li>
</ol>
@endsection
@section('content')

    <preexisting-condition-show :record='@json($preexisting_condition)'></preexisting-condition-show>

    <div class="row">
        <div class="col-md-12">
            <div class="row mt-4">
                <div class="col-md-4">
                    @if ($can_edit)
                        <a href="/preexisting-condition/{{ $preexisting_condition->id }}/edit" class="btn btn-primary">Edit Preexisting Conditions</a>
                    @endif
                </div>
                <div class="col-md-4 text-md-center mt-2 mt-md-0">
                    @if ($can_delete)
                        <form class="form" role="form" method="POST" action="/preexisting-condition/{{ $preexisting_condition->id }}">
                            <input type="hidden" name="_method" value="delete">
                            {{ csrf_field() }}

                            <input class="btn btn-danger" Onclick="return ConfirmDelete();" type="submit" value="Delete Preexisting Conditions">

                        </form>
                    @endif
                </div>
                <div class="col-md-4 text-md-right mt-2 mt-md-0">
                    <a href="{{ url('/preexisting-condition') }}" class="btn btn-default">Return to List</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    function ConfirmDelete() {
        var x = confirm("Are you sure you want to delete this Preexisting Conditions?");
        if (x)
            return true;
        else
            return false;
    }
</script>
@endsection
