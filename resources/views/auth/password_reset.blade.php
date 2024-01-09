@extends('layouts/fullLayoutMaster')

@section('title', __('locale.Reset Password'))

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/authentication.css')) }}">
@endsection

@php
$configData = Helper::applClasses();
@endphp

@section('content')
<div class="auth-wrapper auth-basic px-2">
    <div class="auth-inner my-2">
        <!-- Reset Password basic -->
        <div class="card mb-0">
            <div class="card-body">
                <a href="#" class="brand-logo">
                    <img src="{{ $configData['appLogo'] }}" height="60">
                </a>
                <h4 class="card-title mb-1">{{ __('locale.Reset Password') }} 🔒</h4>
                <p class="card-text mb-2">{{ __('locale.Type your email address for reset your password.') }} </p>
                <form class="mt-2" id="passwordResetForm">
                    <div class="mb-1">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" for="reset-email">{{ __('locale.Email') }}</label>
                        </div>
                        <div class="input-group">
                            <input type="email" class="form-control" id="reset-email" name="reset-email" value="" autofocus />
                        </div>
                    </div>
                    <button class="btn btn-primary w-100" tabindex="3">{{ __('locale.Reset Your Password') }}</button>
                </form>
                <form class="mt-2" id="passwordResetConfirmForm" style="display: none">
                    <div class="mb-1">
                        <label class="form-label" for="reset-confirm-code">{{ __('locale.Confirm Code') }}</label>
                        <input type="number" class="form-control" id="reset-confirm-code" name="reset-confirm-code" value="" autofocus/>
                    </div>
                    <div class="mb-1">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" for="reset-confirm-password">{{ __('locale.New Password') }}</label>
                        </div>
                        <div class="input-group input-group-merge form-password-toggle">
                            <input type="password" class="form-control form-control-merge" id="reset-confirm-password" name="reset-confirm-password" placeholder="" aria-describedby="reset-confirm-password"/>
                            <span class="input-group-text cursor-pointer">
                                <i data-feather="eye"></i>
                            </span>
                        </div>
                    </div>
                    <button class="btn btn-primary w-100" tabindex="3">{{ __('locale.Save') }}</button>
                </form>
                <p class="text-center mt-2">
                    <a href="{{url('auth/login')}}">
                        <i data-feather="chevron-left"></i> {{ __('locale.Back to login') }}
                    </a>
                </p>
            </div>
        </div>
        <!-- /Reset Password basic -->
    </div>
</div>
@endsection

@section('vendor-script')
<script src="{{asset(mix('vendors/js/forms/validation/jquery.validate.min.js'))}}"></script>
@endsection

@section('page-script')
<script type="text/javascript">
$(function () {
    'use strict';

    var token = null;
    var passwordResetForm = $('#passwordResetForm');

    passwordResetForm.validate({
        rules: {
            'reset-email': {
                required: true,
                email: true
            },
        }
    });

    passwordResetForm.submit((event) => {

        event.preventDefault();

        if(!passwordResetForm.valid()){
            return;
        }

        let email = $('#reset-email').val();

        AxiosPOST('/api/auth/password-reset', {email}, (r) => {

            let response = r.data;

            if(response.status == true){
                
                $('#passwordResetForm').hide();
                $('#passwordResetConfirmForm').show();

                token = response.data.token;

            }

        });

    })

    var passwordResetConfirmForm = $('#passwordResetConfirmForm');

    passwordResetConfirmForm.validate({
        rules: {
            'reset-confirm-code': {
                required: true
            },
            'reset-confirm-password': {
                required: true
            },
        }
    });

    passwordResetConfirmForm.submit((event) => {

        event.preventDefault();

        if(!passwordResetForm.valid()){
            return;
        }

        let code = $('#reset-confirm-code').val();
        let password = $('#reset-confirm-password').val();

        AxiosPOST('/api/auth/password-reset/confirm', {token, code, password}, (r) => {

            let response = r.data;

            if(response.status == true){
                
                ToastAlert(response.message, 'success');

                setTimeout(() => {

                    location.href = '/auth/login'
                    
                }, 1000);

            }

        });

    })

});
</script>
@endsection