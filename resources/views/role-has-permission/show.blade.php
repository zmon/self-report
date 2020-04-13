@extends('layouts.crud-master')
@php $nav_path = ['role-has-permission'] @endphp
@section('page-title')
    View {{$role_has_permission->name}}
@endsection

@section('page-header-breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('role-has-permission.index') }}">role_has_permissions</a></li>
        <li class="breadcrumb-item">{{$role_has_permission->name}}</li>
        <li class="breadcrumb-item active" aria-current="location">View</li>
    </ol>
@endsection
@section('content')

    <div class="card">
        <div class="card-header align-middle">
            <h1>
                {{$role_has_permission->name}} role_has_permissions </h1>
        </div>
        <div class="card-body">

            <role-has-permission-show :record='@json($role_has_permission)'></role-has-permission-show>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-4">
                    @if ($can_edit)
                        <a href="/role-has-permission/{{ $role_has_permission->id }}/edit" class="btn btn-primary">Edit
                            role_has_permissions</a>
                    @endif
                </div>
                <div class="col-md-4 text-md-center mt-2 mt-md-0">
                    @if ($can_delete)
                        <form class="form" role="form" method="POST"
                              action="/role-has-permission/{{ $role_has_permission->id }}">
                            <input type="hidden" name="_method" value="delete">
                            {{ csrf_field() }}

                            <input class="btn btn-outline-danger" Onclick="return ConfirmDelete();" type="submit"
                                   value="Delete role_has_permissions">

                        </form>
                    @endif
                </div>
                <div class="col-md-4 text-md-right mt-2 mt-md-0">
                    <a href="{{ url('/role-has-permission') }}" class="btn btn-default">Return to List</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function ConfirmDelete() {
            var x = confirm("Are you sure you want to delete this role_has_permissions?");
            if (x)
                return true;
            else
                return false;
        }
    </script>
@endsection
