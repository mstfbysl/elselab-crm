@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Edit Purchase Invoice'))

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
@endsection

@section('content')
<!-- Edit Invoice -->
<input type="hidden" name="nth-tab" id="nth-tab" value="{{ request()->tab }}">
<div class="col-xl-12 col-lg-12">
    <div class="card">
        <div class="card-header">
            <a href="/purchase-invoices" class="btn btn-info btn-icon"><i class="bi bi-arrow-left"></i></a> 
            <h4 class="card-title">{{ __('locale.Edit Purchase Invoice') }}</h4>
            <div class="progress-wrapper">
                <div id="example-caption">{{ __('locale.Remaining Total of Invoice') }}: <span class="edit-invoice-remaining"></span></div>
                <div class="progress progress-bar-primary">
                    <div class="progress-bar edit-invoice-progressbar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="" aria-describedby="example-caption"></div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" id="edit-purchase-invoice-tab" data-bs-toggle="tab" href="#edit-purchase-invoice" aria-controls="edit-purchase-invoice" role="tab" aria-selected="true">
                        <i class="bi bi-pencil"></i> {{ __('locale.Edit Purchase Invoice') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="items-tab" data-bs-toggle="tab" href="#items" aria-controls="item" role="tab" aria-selected="false">
                        <i class="bi bi-box-seam"></i> {{ __('locale.Items') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="rentals-tab" data-bs-toggle="tab" href="#rentals" aria-controls="rental" role="tab" aria-selected="false">
                        <i class="bi bi-arrow-repeat"></i> {{ __('locale.Rentals') }}
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" id="edit-purchase-invoice" aria-labelledby="edit-purchase-invoice-tab" role="tabpanel">
                    <form id="editInvoiceForm" class="mt-2 form-with-upload" method="POST">
                        <input type="hidden" name="edit-invoice-id" id="edit-invoice-id" value="{{ request()->id }}">
                        <div class="mb-1">
                            <label class="form-label" for="edit-invoice-client-id">{{ __('locale.Client') }}</label>
                            <select class="select2 form-select" id="edit-invoice-client-id" required>
                                <option></option>
                            </select>
                        </div>
                        <div class="mb-1">
                            <label for="edit-invoice-title" class="form-label">{{ __('locale.Title') }}</label>
                            <input type="text" class="form-control" id="edit-invoice-title" name="edit-invoice-title"/>
                        </div>
                        <div class="mb-1">
                            <label for="edit-invoice-serie" class="form-label">{{ __('locale.Serie') }}</label>
                            <input type="text" class="form-control" id="edit-invoice-serie" name="edit-invoice-serie"/>
                        </div>
                        <div class="mb-1">
                            <label for="edit-invoice-date" class="form-label">{{ __('locale.Date') }}</label>
                            <input type="date" class="form-control" id="edit-invoice-date" name="edit-invoice-date"/>
                        </div>
                        <div class="mb-1">
                            <label for="edit-invoice-document" class="form-label">{{ __('locale.Invoice') }}</label>
                            <input type="file" class="form-control fuploader" name="edit-invoice-document" id="edit-invoice-document" data-file-id=""/>
                        </div>
                        <div class="mb-1">
                            <label for="edit-invoice-total" class="form-label">{{ __('locale.Total without VAT') }}</label>
                            <div class="input-group input-group-merge mb-2">
                                <span class="input-group-text">€</span>
                                <input type="text" class="form-control" id="edit-invoice-total" name="edit-invoice-total">
                            </div>
                        </div>
                        <div class="mb-1">
                            <label for="edit-invoice-total-with-vat" class="form-label">{{ __('locale.Total with VAT') }}</label>
                            <div class="input-group input-group-merge mb-2">
                                <span class="input-group-text">€</span>
                                <input type="text" class="form-control" id="edit-invoice-total-with-vat" name="edit-invoice-total-with-vat">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-lg-6">
                                <div class="d-flex flex-column">
                                    <label class="form-check-label mb-50" for="edit-invoice-is-paid">{{ __('locale.Paid') }}</label>
                                    <div class="form-check form-switch form-check-success">
                                        <input type="checkbox" class="form-check-input" id="edit-invoice-is-paid" />
                                        <label class="form-check-label" for="edit-invoice-is-paid">
                                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                                            <span class="switch-icon-right"><i class="bi bi-x"></i></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-flex flex-column">
                                    <label class="form-check-label mb-50" for="edit-invoice-is-accountant">{{ __('locale.Accountant') }}</label>
                                    <div class="form-check form-switch form-check-success">
                                        <input type="checkbox" class="form-check-input" id="edit-invoice-is-accountant" />
                                        <label class="form-check-label" for="edit-invoice-is-accountant">
                                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                                            <span class="switch-icon-right"><i class="bi bi-x"></i></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mb-1 d-grid w-100">{{ __('locale.Save') }}</button>
                    </form>
                </div>
                <div class="tab-pane" id="items" aria-labelledby="items-tab" role="tabpanel">
                    <button class="btn btn-primary" onclick="onCreateItem()">{{ __('locale.Add Item') }}</button><hr>
                    <table id="DataTableItem" class="datatables-ajax table">
                        <thead>
                            <tr>
                                <th>{{ __('locale.ID') }}</th>
                                <th>{{ __('locale.Type') }}</th>
                                <th>{{ __('locale.Serial') }}</th>
                                <th>{{ __('locale.Title') }}</th>
                                <th>{{ __('locale.Quantity') }}</th>
                                <th>{{ __('locale.Usage') }}</th>
                                <th>{{ __('locale.Cost') }}</th>
                                <th>{{ __('locale.Total Cost') }}</th>
                                <th>{{ __('locale.Action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="tab-pane" id="rentals" aria-labelledby="rentals-tab" role="tabpanel">
                    <button class="btn btn-primary" onclick="onCreateRental()">{{ __('locale.Add Rental') }}</button><hr>
                    <table id="DataTableRental" class="datatables-ajax table">
                        <thead>
                            <tr>
                                <th>{{ __('locale.ID') }}</th>
                                <th>{{ __('locale.Serial') }}</th>
                                <th>{{ __('locale.Title') }}</th>
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
</div>
<!-- Edit Invoice -->
@include('modals.create-purchase-item')
@include('modals.edit-purchase-item')

@include('modals.create-purchase-rental')
@include('modals.edit-purchase-rental')
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

    var id = $('#edit-invoice-id').val();
    var tab = $('#nth-tab').val();

    $(".nav-item:nth-child("+tab+") .nav-link").addClass('active');
    $(".tab-pane:nth-child("+tab+")").addClass('active');

    CurrencyMask('#create-item-cost');
    CurrencyMask('#create-rental-cost');

    /** Invoice Tab */
    window.onEditInvoice = () => {
        AxiosGET('/api/purchase-invoice/detail/' + id, (r) => {

            let response = r.data;

            if(response.status == true){

                FillSelect2('#edit-invoice-client-id', '/api/client/list-all', false, [response.data.client_id]);

                $('#edit-invoice-id').val(response.data.id);
                $('#edit-invoice-title').val(response.data.title);
                $('#edit-invoice-serie').val(response.data.serie);
                $('#edit-invoice-date').val(response.data.date);
                $('#edit-invoice-total').val(response.data.total);
                $('#edit-invoice-total-with-vat').val(response.data.total_with_vat);
                
                $('#edit-invoice-document').attr('data-file-id', response.data.document);

                CurrencyMask('#edit-invoice-total');
                CurrencyMask('#edit-invoice-total-with-vat');

                if(response.data.is_paid){
                    $('#edit-invoice-is-paid').prop('checked', true);
                }
                
                if(response.data.is_accountant){
                    $('#edit-invoice-is-accountant').prop('checked', true);
                }

                $('.edit-invoice-progressbar').attr('style', 'width: ' + response.data.percentage_total + '%');
                $('.edit-invoice-remaining').html(response.data.human_total);
                
            }

        }, (error) => {

            if(error.response.status == 404){
                return location.href = '/purchase-invoices';
            }

        })
    }

    onEditInvoice();

    var editInvoiceForm = $('#editInvoiceForm');

    editInvoiceForm.validate({
        rules: {
            'edit-invoice-client-id': {
                required: true,
            },
            'edit-invoice-title': {
                required: true,
            },
            'edit-invoice-serie': {
                required: true,
            },
            'edit-invoice-date': {
                required: true
            },
            'edit-invoice-total': {
                required: true
            },
            'edit-invoice-total-with-vat': {
                required: true
            },
            'edit-invoice-is-paid': {
                required: true
            },
            'edit-invoice-is-accountant': {
                required: true
            }
        }
    });

    editInvoiceForm.submit(() => {
        event.preventDefault();

        if(!editInvoiceForm.valid()){
            return;
        }

        let client_id = $('#edit-invoice-client-id').val();
        let title = $('#edit-invoice-title').val();
        let serie = $('#edit-invoice-serie').val();
        let date = $('#edit-invoice-date').val();
        let total = GetCurrencyUnmask('#edit-invoice-total');
        let total_with_vat = GetCurrencyUnmask('#edit-invoice-total-with-vat');
        let is_paid = $("#edit-invoice-is-paid").is(":checked");
        let is_accountant = $("#edit-invoice-is-accountant").is(":checked");

        (async () => {

            if($('#edit-invoice-document').val()){
                await upload_form_files('#edit-invoice-document', 'docs');
            }

            let document = $('#edit-invoice-document').attr('data-file-id');

            AxiosPOST('/api/purchase-invoice/edit/' + id, {client_id, title, serie,date, total, total_with_vat, is_paid, is_accountant, document}, (r) => {
                let response = r.data;

                if(response.status == true){
                    location.href = '/purchase-invoices';
                }
            });
        })();

    });

    /** Item Tab */
    var createItemModal = new bootstrap.Modal($('#createItemModal'));
    var createItemForm = $('#createItemForm');

    var editItemModal = new bootstrap.Modal($('#editItemModal'));
    var editItemForm = $('#editItemForm');

    var DataTableItem = $('#DataTableItem').DataTable({
        processing: true,
        dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        ajax: {
            "url": "/api/purchase-invoice-item/list-datatable-filter",
            "data": {
                "purchase_invoice_id": id
            }
        },
        language: {
            paginate: {
                previous: '&nbsp;',
                next: '&nbsp;'
            }
        },
        fnDrawCallback: function() {
            [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]')).map(function(e){return new bootstrap.Popover(e,{'html':true})});
        }
    });

    createItemForm.validate({
        rules: {
            'create-item-type': {
                required: true,
            },
            'create-item-title': {
                required: true,
            },
            'create-item-cost': {
                required: true,
            },
            'create-item-quantity': {
                required: true,
            }
        }
    });

    createItemForm.submit(() => {
        event.preventDefault();

        if(!createItemForm.valid()){
            return;
        }

        let type = $('#create-item-type').val();
        let serial = $('#create-item-serial').val();
        let title = $('#create-item-title').val();
        let quantity = $('#create-item-quantity').val();
        let cost = GetCurrencyUnmask('#create-item-cost');

        AxiosPOST('/api/purchase-invoice-item/create', {purchase_invoice_id: id, type, serial, title, quantity, cost}, (r) => {

            let response = r.data;

            if(response.status == true){

                createItemModal.hide();
                ToastAlert(response.message, 'success');

                DataTableItem.ajax.reload();
                onEditInvoice();

            }

        });
    });

    editItemForm.validate({
        rules: {
            'edit-item-type': {
                required: true,
            },
            'edit-item-title': {
                required: true,
            },
            'edit-item-cost': {
                required: true,
            }
        }
    });

    editItemForm.submit(() => {
        event.preventDefault();

        if(!editItemForm.valid()){
            return;
        }

        let item_id = $('#edit-item-id').val();
        let type = $('#edit-item-type').val();
        let serial = $('#edit-item-serial').val();
        let title = $('#edit-item-title').val();
        let quantity = $('#edit-item-quantity').val();
        let cost = GetCurrencyUnmask('#edit-item-cost');

        AxiosPOST('/api/purchase-invoice-item/edit/' + item_id, {type, serial, title, quantity, cost}, (r) => {

            let response = r.data;

            if(response.status == true){

                editItemModal.hide();
                ToastAlert(response.message, 'success');

                DataTableItem.ajax.reload();
                onEditInvoice();

            }

        });
    });

    window.onCreateItem = () => {
        ResetForms('#createItemForm');
        createItemModal.show();

        $('#create-item-quantity').val(1);

        FillSelect2('#create-item-type', '/api/system-definition/list-filter', false, [], {type: 'purchase_invoice_item_type'}); 
    }

    window.onEditItem = (ID) => {
        ResetForms('#editItemForm');

        AxiosGET('/api/purchase-invoice-item/detail/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){

                FillSelect2('#edit-item-type', '/api/system-definition/list-filter', false, [response.data.type], {type: 'purchase_invoice_item_type'}); 
                
                $('#edit-item-id').val(response.data.id);
                $('#edit-item-serial').val(response.data.serial);
                $('#edit-item-title').val(response.data.title);
                $('#edit-item-quantity').val(response.data.quantity);
                $('#edit-item-cost').val(response.data.cost);
                
                CurrencyMask('#edit-item-cost');

                editItemModal.show();
            }

        })
    }

    window.onDeleteItem = (ID) => {
        AxiosAskGET('/api/purchase-invoice-item/delete/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){
                ToastAlert(response.message, 'success');
                DataTableItem.ajax.reload();

                onEditInvoice();
            }

        }, window.JSLang['Are you sure?'], window.JSLang['This will deleted and you will not be able to revert this!'], window.JSLang['Yes, do it!'])
    }
    /** Item Tab */

    /** Rental Tab */
    var createRentalModal = new bootstrap.Modal($('#createRentalModal'));
    var createRentalForm = $('#createRentalForm');

    var editRentalModal = new bootstrap.Modal($('#editRentalModal'));
    var editRentalForm = $('#editRentalForm');

    var DataTableRental = $('#DataTableRental').DataTable({
        processing: true,
        dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        ajax: {
            "url": "/api/purchase-invoice-rental/list-datatable-filter",
            "data": {
                "purchase_invoice_id": id
            }
        },
        language: {
            paginate: {
                previous: '&nbsp;',
                next: '&nbsp;'
            }
        }
    });

    createRentalForm.validate({
        rules: {
            'create-rental-title': {
                required: true,
            },
            'create-rental-cost': {
                required: true,
            },
            'create-rental-quantity': {
                required: true,
            }
        }
    });

    createRentalForm.submit(() => {
        event.preventDefault();

        if(!createItemForm.valid()){
            return;
        }

        let rental_id = $('#create-rental-id').val();
        let serial = $('#create-rental-serial').val();
        let title = $('#create-rental-title').val();
        let quantity = $('#create-rental-quantity').val();
        let cost = GetCurrencyUnmask('#create-rental-cost');

        AxiosPOST('/api/purchase-invoice-rental/create', {purchase_invoice_id: id, rental_id, serial, title, quantity, cost}, (r) => {

            let response = r.data;

            if(response.status == true){

                createRentalModal.hide();
                ToastAlert(response.message, 'success');

                DataTableRental.ajax.reload();
                onEditInvoice();

            }

        });
    });

    editRentalForm.validate({
        rules: {
            'edit-rental-title': {
                required: true,
            },
            'edit-rental-cost': {
                required: true,
            }
        }
    });

    editRentalForm.submit(() => {
        event.preventDefault();

        if(!editRentalForm.valid()){
            return;
        }

        let purchase_invoice_rental_id = $('#edit-purchase-invoice-rental-id').val();
        let rental_id = $('#edit-rental-id').val();
        let serial = $('#edit-rental-serial').val();
        let title = $('#edit-rental-title').val();
        let quantity = $('#edit-rental-quantity').val();
        let cost = GetCurrencyUnmask('#edit-rental-cost');

        AxiosPOST('/api/purchase-invoice-rental/edit/' + purchase_invoice_rental_id, {rental_id, serial, title, quantity, cost}, (r) => {

            let response = r.data;

            if(response.status == true){

                editRentalModal.hide();
                ToastAlert(response.message, 'success');

                DataTableRental.ajax.reload();
                onEditInvoice();

            }

        });
    });

    window.onCreateRental = () => {
        ResetForms('#createRentalForm');
        createRentalModal.show();

        $('#create-rental-quantity').val(1);

        FillSelect2('#create-rental-id', '/api/rental/list-all', false, []); 
    }

    window.onEditRental = (ID) => {
        ResetForms('#editRentalForm');

        AxiosGET('/api/purchase-invoice-rental/detail/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){

                FillSelect2('#edit-rental-id', '/api/rental/list-all', false, [response.data.rental_id]); 
                
                $('#edit-purchase-invoice-rental-id').val(response.data.id);
                $('#edit-rental-id').val(response.data.rental_id);
                $('#edit-rental-serial').val(response.data.serial);
                $('#edit-rental-title').val(response.data.title);
                $('#edit-rental-quantity').val(response.data.quantity);
                $('#edit-rental-cost').val(response.data.cost);
                
                CurrencyMask('#edit-rental-cost');

                editRentalModal.show();
            }

        })
    }

    window.onDeleteRental = (ID) => {
        AxiosAskGET('/api/purchase-invoice-rental/delete/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){
                ToastAlert(response.message, 'success');
                DataTableRental.ajax.reload();

                onEditInvoice();
            }

        }, window.JSLang['Are you sure?'], window.JSLang['This will deleted and you will not be able to revert this!'], window.JSLang['Yes, do it!'])
    }
    /** Rental Tab */
});
</script>
@endsection