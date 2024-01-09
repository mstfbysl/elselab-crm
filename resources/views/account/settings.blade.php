@extends('layouts/contentLayoutMaster')

@section('title', __('Account Settings'))

@section('content')
<!-- Account Settings -->
<section>
    @include('account.partials._nav', ['active' => 'settings'])
    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">{{ __('locale.Account Settings') }}</h4>
                </div>
                <div class="card-body">
                    <form id="saveForm" class="mt-2 form-with-upload" method="POST">
                        <div class="mb-1">
                            <label for="save-email" class="form-label">{{ __('locale.Profile Picture') }}</label>
                            <input type="file" class="form-control fuploader" name="save-profile-picture" id="save-profile-picture" data-file-id=""/>
                        </div>
                        <div class="mb-1">
                            <label for="save-fullname" class="form-label">{{ __('locale.Fullname') }}</label>
                            <input type="text" class="form-control" id="save-fullname" name="save-fullname"/>
                        </div>
                        <div class="mb-1">
                            <label for="save-email" class="form-label">{{ __('locale.E-mail') }}</label>
                            <input type="text" class="form-control" id="save-email" name="save-email"/>
                        </div>
                        <button type="submit" class="btn btn-primary mb-1 d-grid w-100">{{ __('locale.Save') }}</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">{{ __('locale.Preview') }}</h4>
                </div>
                <div class="card-body">
                    <div class="mt-1 mb-1">
                        <label class="form-label">{{ __('locale.Profile Picture') }}</label><br>
                        <img id="profile-picture-preview" src="" alt="" width="50">
                    </div>
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

    AxiosGET('/api/account/detail', (r) => {

        let response = r.data;

        if(response.status == true){
            $('#save-fullname').val(response.data.name_surname);
            $('#save-email').val(response.data.email);
            $('#save-profile-picture').attr('data-file-id', response.data.profile_picture);
            $('#profile-picture-preview').attr('src', response.data.profile_picture_preview);
        }

    })

    var saveForm = $('#saveForm');

    saveForm.validate({
        rules: {
            'fullname': {
                required: true,
            },
            'email': {
                required: true,
                email: true
            }
        }
    });

    saveForm.submit(() => {
        event.preventDefault();

        if(!saveForm.valid()){
            return;
        }

        let name_surname = $('#save-fullname').val();
        let email = $('#save-email').val();
        

        (async () => {

            if($('#save-profile-picture').val()){
                await upload_form_files('#save-profile-picture', 'public');
            }
            
            let profile_picture = $('#save-profile-picture').attr('data-file-id');
            
            AxiosPOST('/api/account/save-details', {name_surname, email, profile_picture}, (r) => {
                let response = r.data;

                if(response.status == true){
                    ToastAlert(response.message, 'success');
                    //location.reload();
                }

            })
        })();
    });
});
</script>
@endsection