@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <ul>
            <li><passport-clients></passport-clients>
            <li><passport-authorized-clients></passport-authorized-clients>
            <li><passport-personal-access-tokens></passport-personal-access-tokens>
        </ul>
    </d>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
