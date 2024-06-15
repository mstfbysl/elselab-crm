@extends('layouts/fullLayoutMaster')

@section('title', __('locale.Login Page'))

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
    <!-- Login basic -->
    <div class="card mb-0">
      <div class="card-body">
        <a href="#" class="brand-logo">
          <img src="{{ $configData['appLogo'] }}" height="60">
        </a>

        <h4 class="card-title mb-1">{{ __('locale.Login') }}</h4>

        <form id="loginForm" class="mt-2" method="POST">
          <div class="mb-1">
            <label for="email" class="form-label">{{ __('locale.Email') }}</label>
            <input
              type="text"
              class="form-control"
              id="email"
              name="email"
              placeholder="name@example.com"
              aria-describedby="email"
              tabindex="1"
              autofocus
              autocomplete="off"
            />
          </div>

          <div class="mb-1">
            <div class="d-flex justify-content-between">
              <label class="form-label" for="password">{{ __('locale.Password') }}</label>
              <a href="{{ route('frontend.auth.password_reset') }}">
                <small>{{ __('locale.Forgot Password?') }}</small>
              </a>
            </div>
            <div class="input-group input-group-merge form-password-toggle">
              <input
                type="password"
                class="form-control form-control-merge"
                id="password"
                name="password"
                tabindex="2"
                placeholder="***********"
                aria-describedby="password"
                autocomplete="off"
              />
              <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
            </div>
          </div>
          <button class="g-recaptcha btn btn-primary w-100" data-sitekey="{{ env('GOOGLE_SITEKEY') }}" data-callback='onSubmit' data-action='submit'>{{ __('locale.Submit') }}</button>
        </form>
      </div>
    </div>
    <!-- /Login basic -->
  </div>
</div>
@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
<script src="https://www.google.com/recaptcha/api.js"></script>
@endsection

@section('page-script')
<script type="text/javascript">
function onSubmit(token) {

  let email = $('#email').val();
  let password = $('#password').val();


  AxiosPOST('/api/auth/login', {email, password}, (r) => {

      let response = r.data;

      if(response.status == true){
          location.href = '{{ route('index') }}';
      }

  })

}
$(function () {
    'use strict';
    var loginForm = $('#loginForm');

    loginForm.validate({
        rules: {
            'email': {
                required: true,
                email: true
            },
            'password': {
                required: true
            }
        }
    });

});
</script>
@endsection
