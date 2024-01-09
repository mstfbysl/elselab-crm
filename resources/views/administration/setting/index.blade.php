@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Settings'))

@section('content')
<!-- Settings -->
<section>
    <div class="row">
        <div class="col-9">
            <div class="card">
                <div class="card-header ">
                    <h4 class="card-title">{{ __('locale.Settings') }}</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="company-settings-tab" data-bs-toggle="tab" href="#company-settings" aria-controls="profile" role="tab" aria-selected="false">
                                <i data-feather="briefcase"></i> {{ __('locale.Company Settings') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="general-settings-tab" data-bs-toggle="tab" href="#general-settings" aria-controls="home" role="tab" aria-selected="true">
                                <i data-feather="coffee"></i> {{ __('locale.Logo') }}
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="company-settings" aria-labelledby="company-settings-tab" role="tabpanel">
                            <form id="companySettingForm" class="mt-2" method="POST">
                                <div class="mb-1">
                                    <label for="setting-company-name" class="form-label">{{ __('locale.Company Name') }}</label>
                                    <input type="text" class="form-control" id="setting-company-name" name="setting-company-name"/>
                                </div>
                                <div class="mb-1">
                                    <label for="setting-company-code" class="form-label">{{ __('locale.Company Code') }}</label>
                                    <input type="text" class="form-control" id="setting-company-code" name="setting-company-code"/>
                                </div>
                                <div class="mb-1">
                                    <label for="setting-company-address" class="form-label">{{ __('locale.Company Address') }}</label>
                                    <input type="text" class="form-control" id="setting-company-address" name="setting-company-address"/>
                                </div>
                                <div class="mb-1">
                                    <label for="setting-company-is-vat-member" class="form-label">{{ __('locale.Company Is VAT member?') }}</label>
                                    <select class="select2 form-select" id="setting-company-is-vat-member" required>
                                        <option></option>
                                    </select>
                                </div>
                                <div class="mb-1" style="display: none" id="company-vat-number-box">
                                    <label for="setting-company-vat-number" class="form-label">{{ __('locale.Company VAT Number') }}</label>
                                    <input type="number" class="form-control" id="setting-company-vat-number" name="setting-company-vat-number"/>
                                </div>
                                <div class="mb-1">
                                    <label for="setting-company-currency" class="form-label">{{ __('locale.Company Currency') }}</label>
                                    <select class="select2 form-select" id="setting-company-currency" required>
                                        <option></option>
                                    </select>
                                </div>
                                <div class="mb-1">
                                    <label for="setting-company-phone" class="form-label">{{ __('locale.Company Phone') }}</label>
                                    <input type="text" class="form-control" id="setting-company-phone" name="setting-company-phone"/>
                                </div>
                                <div class="mb-1">
                                    <label for="setting-company-email" class="form-label">{{ __('locale.Company Email') }}</label>
                                    <input type="text" class="form-control" id="setting-company-email" name="setting-company-email"/>
                                </div>
                                <div class="mb-1">
                                    <label for="setting-company-bank-name" class="form-label">{{ __('locale.Company Bank Name') }}</label>
                                    <input type="text" class="form-control" id="setting-company-bank-name" name="setting-company-bank-name"/>
                                </div>
                                <div class="mb-1">
                                    <label for="setting-company-bank-iban" class="form-label">{{ __('locale.Company Bank IBAN') }}</label>
                                    <input type="text" class="form-control" id="setting-company-bank-iban" name="setting-company-bank-iban"/>
                                </div>
                                <div class="mb-1">
                                    <label for="setting-company-bank-code" class="form-label">{{ __('locale.Company Bank Code') }}</label>
                                    <input type="text" class="form-control" id="setting-company-bank-code" name="setting-company-bank-code"/>
                                </div>
                                <button type="submit" class="btn btn-primary mb-1 d-grid w-100">{{ __('locale.Save') }}</button>
                            </form>
                        </div>
                        <div class="tab-pane" id="general-settings" aria-labelledby="general-settings-tab" role="tabpanel">
                            <form id="logoSettingForm" class="mt-2 form-with-upload" method="POST">
                                <div class="mb-1">
                                    <label for="setting-logo" class="form-label">{{ __('locale.Logo') }}</label>
                                    <input type="file" class="form-control fuploader" name="setting-logo" id="setting-logo" data-file-id=""/>
                                </div>
                                <button type="submit" class="btn btn-primary mb-1 d-grid w-100">{{ __('locale.Save') }}</button>
                            </form>
                        </div>
                    </div>            
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">{{ __('locale.Preview') }}</h4>
                </div>
                <div class="card-body">
                    <div class="mt-1 mb-1">
                        <label class="form-label">{{ __('locale.App Logo') }}</label><br>
                        <img id="app-logo-preview" src="" alt="" width="150">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Settings -->
@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection

@section('page-script')
<script type="text/javascript">
$(function() {
    'use strict';

    window.onVatMemberChange = () => {

        if($('#setting-company-is-vat-member').val() == 1){
            $('#company-vat-number-box').show();
        }else{
            $('#company-vat-number-box').hide();
        }

    }

    AxiosGET('/api/setting/detail', (r) => {

        let response = r.data;

        if(response.status == true){

            $('#setting-app-name').val(response.data.app_name);
            $('#setting-logo').attr('data-file-id', response.data.logo);
            $('#setting-favicon').attr('data-file-id', response.data.favicon);
            $('#app-logo-preview').attr('src', response.data.logo_preview);
            
            $('#setting-company-vat-number').val(response.data.company_vat_number);
            $('#setting-company-name').val(response.data.company_name);
            $('#setting-company-code').val(response.data.company_code);
            $('#setting-company-address').val(response.data.company_address);
            $('#setting-company-email').val(response.data.company_email);
            $('#setting-company-phone').val(response.data.company_phone);
            $('#setting-company-bank-name').val(response.data.company_bank_name);
            $('#setting-company-bank-iban').val(response.data.company_bank_iban);
            $('#setting-company-bank-code').val(response.data.company_bank_code);

            if(response.data.company_is_vat_member == 1){
                $('#company-vat-number-box').show();
            }

            FillSelect2('#setting-company-is-vat-member', '/api/system-definition/list-filter', false, [response.data.company_is_vat_member], {type: 'is_vat_member'}, onVatMemberChange); 
            FillSelect2('#setting-company-currency', '/api/currency/list-all', false, [response.data.company_currency]);

        }

    });

    /** General Setting */
    var logoSettingForm = $('#logoSettingForm');

    logoSettingForm.validate({
        rules: {
            'setting-logo': {
                required: true,
            },
        }
    });

    logoSettingForm.submit(() => {
        event.preventDefault();

        if(!logoSettingForm.valid()){
            return;
        }

        (async () => {

            if($('#setting-logo').val()){
                await upload_form_files('#setting-logo', 'public');

                let logo = $('#setting-logo').attr('data-file-id');
                AxiosPOST('/api/setting/save-logo', {logo}, (r) => {

                    let response = r.data;

                    if(response.status == true){
                        location.reload();
                    }

                })
            }
        })();
    });

    /** Company Setting */
    var companySettingForm = $('#companySettingForm');

    companySettingForm.validate({
        rules: {
            'setting-company-email': {
                email: true,
            },
        }
    });

    companySettingForm.submit(() => {
        event.preventDefault();

        if(!companySettingForm.valid()){
            return;
        }

        let company_is_vat_member = $('#setting-company-is-vat-member').val();
        let company_vat_number = $('#setting-company-vat-number').val();
        let company_currency = $('#setting-company-currency').val();
        let company_name = $('#setting-company-name').val();
        let company_code = $('#setting-company-code').val();
        let company_address = $('#setting-company-address').val();
        let company_email = $('#setting-company-email').val();
        let company_phone = $('#setting-company-phone').val();
        let company_bank_name = $('#setting-company-bank-name').val();
        let company_bank_iban = $('#setting-company-bank-iban').val();
        let company_bank_code = $('#setting-company-bank-code').val();

        AxiosPOST('/api/setting/save-company', {
            company_is_vat_member,
            company_vat_number,
            company_currency,
            company_name,
            company_code,
            company_address,
            company_email,
            company_phone,
            company_bank_name,
            company_bank_iban,
            company_bank_code
        }, (r) => {

            let response = r.data;

            if(response.status == true){
                ToastAlert(response.message, 'success');
            }

        })
    });

});
</script>
@endsection