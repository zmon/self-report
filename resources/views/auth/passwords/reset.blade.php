@extends('layouts.master')
@php $nav_path = [] @endphp
@section('page-title', 'Reset Password')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card">
                    <div class="card-header">{{ __('Reset Password') }}</div>
                    <div class="card-body">

                        <password-reset-form csrf_token="{{ csrf_token() }}"
                                             token="{{ $token }}"
                        ></password-reset-form>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
