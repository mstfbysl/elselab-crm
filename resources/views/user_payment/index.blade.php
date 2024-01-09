@extends('layouts/contentLayoutMaster')

@section('title', __('locale.User Payments'))

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
@endsection

@section('content')
<!-- User Payments -->
<section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">{{ __('locale.User Payments') }}</h4>
                    <button
                        class="btn btn-success permission-selector"
                        type="button"
                        onclick="onCreate()"
                        data-permission-id="36"
                        style="display: none"
                    >
                    {{ __('locale.Create User Payment') }}
                    </button>
                </div>
                <div class="card-datatable">
                    <table id="DataTable" class="datatables-ajax table">
                        <thead>
                            <tr>
                                <th>{{ __('locale.ID') }}</th>
                                <th>{{ __('locale.Type') }}</th>
                                <th>{{ __('locale.User') }}</th>
                                <th>{{ __('locale.Quantity') }}</th>
                                <th>{{ __('locale.Cost') }}</th>
                                <th>{{ __('locale.Total Cost') }}</th>
                                <th>{{ __('locale.Action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ User Payments -->

@include('modals.create-user-payment')
@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.js')) }}"></script>
@endsection

@section('page-script')
<script type="text/javascript">
$(async function() {
    'use strict';

    var createModal = new bootstrap.Modal($('#createModal'));

    var DataTable = $('#DataTable').DataTable({
        processing: true,
        dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        ajax: '/api/user-payment/list-datatable',
        language: {
            paginate: {
                previous: '&nbsp;',
                next: '&nbsp;'
            }
        }
    });

    var createForm = $('#createForm');

    createForm.validate({
        rules: {
            'create-user-payment-user-id': {
                required: true,
            },
            'create-user-payment-type': {
                required: true,
            },
        }
    });

    createForm.submit(() => {
        event.preventDefault();

        if(!createForm.valid()){
            return;
        }

        let url = null;
        let body = null;

        let type = $('#create-user-payment-type').val();
        let user_id = $('#create-user-payment-user-id').val();
        let total = $('#create-user-payment-total').val();

        if(type == 'Cash')
        {
            url = '/api/user-payment/create-cash';
            body = {user_id, type, total};
        }
        else
        {
            let item_id = $('#create-user-payment-item-id').val();
            let quantity = $('#create-user-payment-quantity').val();

            url = '/api/user-payment/create-item';
            body = {user_id, type, total, item_id, quantity};
        }

        AxiosPOST(url, body, (r) => {

            let response = r.data;

            if(response.status == true){

                createModal.hide();
                ToastAlert(response.message, 'success');

                DataTable.ajax.reload();

            }

        });
    });

    await FillSelect2('#create-user-payment-user-id', '/api/user/list-all', false);
    await FillSelect2('#create-user-payment-item-id', '/api/purchase-invoice-item/list-long-term', false);
    await FillSelect2('#create-user-payment-type', '/api/system-definition/list-filter', false, [], {type: 'user_payment_type'}); 

    $('#create-user-payment-type').change(function(){
        if($('#create-user-payment-type').val() == 'Cash'){
            $('.upitem').hide();
            $('.uptotal').show();
        }else{
            $('.upitem').show();
            $('.uptotal').hide();
        }
    });

    window.onCreate = () => {
        ResetForms('#createForm');
        createModal.show();

        $('#create-user-payment-quantity').val(1);
        FillSelect2('#create-user-payment-item-id', '/api/purchase-invoice-item/list-long-term', false);
    }

    window.onDelete = (ID) => {
        AxiosAskGET('/api/user-payment/delete/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){
                ToastAlert(response.message, 'success');
                DataTable.ajax.reload();
            }
            
        }, window.JSLang['Are you sure?'], window.JSLang['This will deleted and you will not be able to revert this!'], window.JSLang['Yes, do it!'])
    }
});
</script>
@endsection