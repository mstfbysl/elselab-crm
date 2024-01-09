@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Company Expenses'))

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
@endsection

@section('content')
<!-- Company Expenses -->
<section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">{{ __('locale.Company Expenses') }}</h4>
                    <button
                        class="btn btn-success permission-selector"
                        type="button"
                        onclick="onCreate()"
                        data-permission-id="33"
                        style="display: none"
                    >
                    {{ __('locale.Create Company Expense') }}
                    </button>
                </div>
                <div class="card-datatable">
                    <table id="DataTable" class="datatables-ajax table">
                        <thead>
                            <tr>
                                <th>{{ __('locale.ID') }}</th>
                                <th>{{ __('locale.Invoice') }}</th>
                                <th>{{ __('locale.Item') }}</th>
                                <th>{{ __('locale.Quantity') }}</th>
                                <th>{{ __('locale.Cost pcs') }}</th>
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
<!--/ Company Expenses -->

@include('modals.create-company-expense')
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
        ajax: '/api/company-expense/list-datatable',
        order: [[0, 'desc']],
        language: {
            paginate: {
                previous: '&nbsp;',
                next: '&nbsp;'
            }
        }
    });

    await FillSelect2('#create-company-expense-item-id', '/api/purchase-invoice-item/list-long-term', false);

    var createForm = $('#createForm');

    createForm.validate({
        rules: {
            'create-company-expense-item-id': {
                required: true,
            },
        }
    });

    createForm.submit(() => {
        event.preventDefault();

        if(!createForm.valid()){
            return;
        }

        let item_id = $('#create-company-expense-item-id').val();
        let quantity = $('#create-company-expense-quantity').val();
        
        AxiosPOST('/api/company-expense/create', {item_id, quantity}, (r) => {

            let response = r.data;

            if(response.status == true){

                createModal.hide();
                ToastAlert(response.message, 'success');

                DataTable.ajax.reload();

            }

        });
    });

    window.onCreate = () => {
        ResetForms('#createForm');
        createModal.show();

        FillSelect2('#create-company-expense-item-id', '/api/purchase-invoice-item/list-long-term', false);

        $('#create-company-expense-quantity').val(1);
    }

    window.onDelete = (ID) => {
        AxiosAskGET('/api/company-expense/delete/' + ID, (r) => {

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