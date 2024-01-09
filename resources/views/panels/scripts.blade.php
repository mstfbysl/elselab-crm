<!-- BEGIN: Vendor JS-->
<script src="{{ asset(mix('vendors/js/vendors.min.js')) }}"></script>
<!-- BEGIN Vendor JS-->
<!-- BEGIN: Page Vendor JS-->
<script src="{{asset(mix('vendors/js/ui/jquery.sticky.js'))}}"></script>

<!-- BEGIN: Init Vendors -->
<script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js'))}} "></script>
<script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/cleave/cleave.min.js'))}}"></script>
<script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>

@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
<script src="{{ asset(mix('js/core/app-menu.js')) }}"></script>
<script src="{{ asset(mix('js/core/app.js')) }}"></script>


<!-- custome scripts file for user -->
<script src="{{ asset(mix('js/core/scripts.js')) }}"></script>

<!-- END: Theme JS-->

<script type="text/javascript">
window.JSLang = {
    'Are you sure?': "{{ __('locale.Are you sure?') }}",
    'Yes, do it!': "{{ __('locale.Yes, do it!') }}",
    'This will deleted and you will not be able to revert this!': "{{ __('locale.This will deleted and you will not be able to revert this!') }}",
    'This project closed and you will not be able to revert this!': "{{ __('locale.This project closed and you will not be able to revert this!') }}",
    'Serie must be selected!': "{{ __('locale.Serie must be selected!') }}",
    'Client must be selected!': "{{ __('locale.Client must be selected!') }}",
    'User must be selected!': "{{ __('locale.User must be selected!') }}",
    'You have to at least 1 valid item to this invoice!': "{{ __('locale.You have to at least 1 valid item to this invoice!') }}",
}

window.InitPage = async(Callback) => {
    let account = await AxiosGET('/api/account/detail');
    let account_data = account.data.data;

    if(account_data.user_role_id != 1)
    {
        account_data.permissions.forEach(element => {
            $('.permission-selector[data-permission-id="' + element.permission_id + '"]').show();
        });
    }else{
        $('.permission-selector').show();
    }
    $('input[type=date]').flatpickr();
}


if(!window.location.pathname.includes('auth')){
    InitPage();
}

</script>

<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->