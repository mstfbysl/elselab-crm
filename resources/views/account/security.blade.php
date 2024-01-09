@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Security Settings'))

@section('content')
<!-- Account Settings -->
<section>
    @include('account.partials._nav', ['active' => 'security'])
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">{{ __('locale.Security Settings') }}</h4>
                </div>
                <div class="card-body">
                    <form id="saveForm" class="mt-2" method="POST">
                        <div class="mb-1">
                            <label for="current-password" class="form-label">{{ __('locale.Current Password') }}</label>
                            <input type="password" class="form-control" id="current-password" name="current-password"/>
                        </div>
                        <div class="mb-1">
                            <label for="new-password" class="form-label">{{ __('locale.New Password') }}</label>
                            <input type="password" class="form-control" id="new-password" name="new-password"/>
                        </div>
                        <div class="mb-1">
                            <label for="confirm-password" class="form-label">{{ __('locale.Confirm New Password') }}</label>
                            <input type="password" class="form-control" id="confirm-password" name="confirm-password"/>
                        </div>
                        <button type="submit" class="btn btn-primary mb-1 d-grid w-100">{{ __('locale.Save') }}</button>
                    </form>
            
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Account Settings -->
@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection

@section('page-script')
<script type="text/javascript">
$(function() {
    'use strict';

    var saveForm = $('#saveForm');

    saveForm.validate({
        rules: {
            'current-password': {
              required: true
            },
            'new-password': {
              required: true,
              minlength: 8
            },
            'confirm-password': {
              required: true,
              equalTo: '#new-password'
            }
          }
    });

    saveForm.submit(() => {
        event.preventDefault();

        if(!saveForm.valid()){
            return;
        }

        let current_password = $('#current-password').val();
        let new_password = $('#new-password').val();

        AxiosPOST('/api/account/save-password', {current_password, new_password}, (r) => {
            let response = r.data;

            if(response.status == true){

                ToastAlert(response.message, 'success');

                setTimeout(() => {
                    location.href = '/auth/logout';
                }, 500);

            }
        })
    });
});
</script>
@endsection