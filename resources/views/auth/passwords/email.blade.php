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
                        <form method="POST" action="{{ route('password.email') }}"
                              aria-label="{{ __('Reset Password') }}">
                            @csrf

                            <div class="form-group row{{ $errors->has('email') ? ' is-invalid' : '' }}">
                                <label for="email" class="form-control-label col-lg-4 col-form-label text-lg-right">
                                    {{ __('E-Mail Address') }}
                                </label>
                                <div class="col-lg-6">
                                    <input id="email" type="email" class="form-control" name="email"
                                           value="{{ old('email') }}" required autofocus>
                                    @if ($errors->has('email'))
                                        <div class="invalid-feedback" role="alert">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-lg-0 mt-lg-0 mb-1 mt-4">
                                <div class="col-lg-6 offset-lg-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Send Password Reset Link') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
