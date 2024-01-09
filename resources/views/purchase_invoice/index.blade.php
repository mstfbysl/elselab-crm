@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Purchase Invoices'))

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
@endsection

@section('content')
<!-- Purchase Invoices -->
<section id="ajax-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">{{ __('locale.Purchase Invoices') }}</h4>

                    <div class="mt-1 col-3">
                        <select class="form-select" name="invoice-type" id="invoice-type">
                            <option value="0">{{ __('locale.Unfinished') }}</option>
                            <option value="1">{{ __('locale.Finished') }}</option>
                            <option value="2" selected>{{ __('locale.All') }}</option>
                        </select>
                    </div>

                    <button
                        class="btn btn-success permission-selector"
                        type="button"
                        onclick="onCreate()"
                        data-permission-id="14"
                        style="display: none"
                    >
                    {{ __('locale.New Purchase') }}
                    </button>
                </div>
                <div class="card-datatable">
                    <table id="DataTable" class="datatables-ajax table">
                        <thead>
                            <tr>
                                <th class="text-nowrap">{{ __('locale.Title') }} / {{ __('locale.Client') }}</th>
                                <th class="text-nowrap">{{ __('locale.Date') }} / {{ __('locale.Serie') }}</th>
                                <th>{{ __('locale.Finance') }}</th>
                                <th class="text-nowrap">{{ __('locale.Total') }}</th>
                                <th>{{ __('locale.Paid') }}</th>
                                <th>{{ __('locale.Accountant') }}</th>
                                <th>{{ __('locale.Invoice') }}</th>
                                <th>{{ __('locale.Action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Purchase Invoices -->

@include('modals.create-purchase-invoice')
@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.js')) }}"></script>
@endsection

@section('page-script')
<script type="text/javascript">
$(function() {
    'use strict';
    
    CurrencyMask('#create-total');
    CurrencyMask('#create-total-with-vat');

    var createModal = new bootstrap.Modal($('#createModal'));

    window.getCurrentType = () => {
        return $('#invoice-type').val();
    } 

    var DataTable = $('#DataTable').DataTable({
        processing: true,
        dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        ajax: {
            url: '/api/purchase-invoice/list-datatable',
            method: 'GET',
            "data": function(d) {
                d.type = $('#invoice-type').val();
            },
        },
        order: [[1, 'desc']],
        language: {
            paginate: {
                previous: '&nbsp;',
                next: '&nbsp;'
            }
        },
        fnDrawCallback: function() {
            [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(function(e){return new bootstrap.Tooltip(e)});
        }
    });

    $( "#invoice-type" ).change(function() {
        DataTable.ajax.reload();
    });
    
    var createForm = $('#createForm');

    createForm.validate({
        rules: {
            'create-client-id': {
                required: true,
            },
            'create-title': {
                required: true,
            },
            'create-serie': {
                required: true,
            },
            'create-date': {
                required: true
            },
            'create-total': {
                required: true
            },
            'create-total-with-vat': {
                required: true
            },
            'create-is-paid': {
                required: true
            },
            'create-is-accountant': {
                required: true
            }
        }
    });

    createForm.submit(() => {
        event.preventDefault();

        if(!createForm.valid()){
            return;
        }

        let client_id = $('#create-client-id').val();
        let title = $('#create-title').val();
        let serie = $('#create-serie').val();
        let date = $('#create-date').val();
        let total = GetCurrencyUnmask('#create-total');
        let total_with_vat = GetCurrencyUnmask('#create-total-with-vat');
        let is_paid = $("#create-is-paid").is(":checked");
        let is_accountant = $("#create-is-accountant").is(":checked");
        let document = $('#create-document').attr('data-file-id');

        (async () => {

            if($('#create-document').val()){
                await upload_form_files('#create-document', 'docs');
            }

            let document = $('#create-document').attr('data-file-id');

            AxiosPOST('/api/purchase-invoice/create', {client_id, title, serie, date, total, total_with_vat, is_paid, is_accountant, document}, (r) => {
                let response = r.data;
                if(response.status == true){
                    createModal.hide();
                    ToastAlert(response.message, 'success');

                    DataTable.ajax.reload();
                }
            })
        })();




    });

    window.onCreate = () => {
        ResetForms('#createForm');
        createModal.show();

        FillSelect2('#create-client-id', '/api/client/list-all', false, []);
    }

    window.onEdit = (ID, tab) => {

        location.href = 'purchase-invoice/edit/' + ID + '/' + tab;
        
    }

    window.onDelete = (ID) => {
        AxiosAskGET('/api/purchase-invoice/delete/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){
                ToastAlert(response.message, 'success');
                DataTable.ajax.reload();
            }

        }, window.JSLang['Are you sure?'], window.JSLang['This will deleted and you will not be able to revert this!'], window.JSLang['Yes, do it!'])
    }

    DataTable.on('click', '.ispaid', function (e) {

        const elem = document.getElementById(e.target.id);

        let ID = elem.getAttribute('data-id');
        let is_paid = (elem.checked == true) ? 1 : 0;

        AxiosPOST('/api/purchase-invoice/set-is-paid/' + ID, {is_paid: is_paid}, (r) => {

            let response = r.data;

            if(response.status == true){
                ToastAlert(response.message, 'success');
                DataTable.ajax.reload();
            }

        })

    });

    DataTable.on('click', '.isaccountant', function (e) {

        const elem = document.getElementById(e.target.id);

        let ID = elem.getAttribute('data-id');
        let is_accountant = (elem.checked == true) ? 1 : 0;

        AxiosPOST('/api/purchase-invoice/set-is-accountant/' + ID, {is_accountant: is_accountant}, (r) => {

            let response = r.data;

            if(response.status == true){
                ToastAlert(response.message, 'success');
                DataTable.ajax.reload();
            }

        })

    });
    
});
</script>
@endsection