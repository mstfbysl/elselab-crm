@extends('layouts/contentLayoutMaster')

@section('title', 'Edit Project')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
@endsection


@section('content')
<!-- Edit Project -->
<input type="hidden" name="edit-project-id" id="edit-project-id" value="{{ request()->id }}">
<input type="hidden" name="nth-tab" id="nth-tab" value="{{ request()->tab }}">
<div class="col-xl-12 col-lg-12">
    <div class="card">
        <div class="card-header">
            <a href="/projects" class="btn btn-info btn-icon"><i class="bi bi-arrow-left"></i></a>
            <h4 class="card-title">{{ __('locale.Edit Project') }}</h4>
            <a href="/offer/project/{{ request()->id }}" class="btn btn-outline-primary waves-effect waves-light" > {{ __('locale.Generate Offer') }}</a>
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-outline-warning waves-effect waves-light preparing-btn" onclick="onChangePreparingProject({{ request()->id }})" >{{ __('locale.Preparing') }}</button>
                <button type="button" class="btn btn-outline-success waves-effect waves-light ongoing-btn" onclick="onChangeOnGoingProject({{ request()->id }})" >{{ __('locale.OnGoing') }}</button>
                <button type="button" class="btn btn-outline-dark waves-effect waves-light completed-btn" onclick="onChangeToCompletedProject({{ request()->id }})" >{{ __('locale.Completed') }}</button>
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" id="edit-project-tab" data-bs-toggle="tab" href="#edit-project" aria-controls="edit-project" role="tab" aria-selected="true">
                        <i class="bi bi-pencil"></i> {{ __('locale.Project details') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="services-tab" data-bs-toggle="tab" href="#services" aria-controls="services" role="tab" aria-selected="false">
                        <i class="bi bi-wrench"></i> 
                        {{ __('locale.Services') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="expenses-tab" data-bs-toggle="tab" href="#expenses" aria-controls="expenses" role="tab" aria-selected="false">
                        <i class="bi bi-basket"></i>
                        {{ __('locale.Expenses') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="rentals-tab" data-bs-toggle="tab" href="#rentals" aria-controls="rentals" role="tab" aria-selected="false">
                        <i class="bi bi-arrow-repeat"></i> 
                        {{ __('locale.Rentals') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="incomes-tab" data-bs-toggle="tab" href="#incomes" aria-controls="incomes" role="tab" aria-selected="false">
                        <i class="bi bi-cash-stack"></i> 
                        {{ __('locale.Incomes') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="users-tab" data-bs-toggle="tab" href="#users" aria-controls="users" role="tab" aria-selected="false">
                        <i class="bi bi-people"></i>
                        {{ __('locale.Project Owners') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="files-tab" data-bs-toggle="tab" href="#files" aria-controls="files" role="tab" aria-selected="false">
                        <i class="bi bi-journal-bookmark"></i>
                        {{ __('locale.Documents') }}
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" id="edit-project" aria-labelledby="edit-project-tab" role="tabpanel">
                    <form id="editProjectForm" class="mt-2" method="POST">
                        <div class="mb-1">
                            <label class="form-label" for="edit-project-client-id">{{ __('locale.Client') }}</label>
                            <select class="select2 form-select" id="edit-project-client-id" required></select>
                        </div>
                        <div class="mb-1">
                            <label for="edit-project-title" class="form-label">{{ __('locale.Title') }}</label>
                            <input type="text" class="form-control" id="edit-project-title" name="edit-project-title"/>
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="edit-project-status-id">{{ __('locale.Status') }}</label>
                            <select class="select2 form-select" id="edit-project-status-id"></select>
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="select2-multiple">{{ __('locale.Labels') }}</label>
                            <select class="select2 form-select" id="edit-labels" multiple></select>
                        </div>
                        <div class="mb-1">
                            <label for="edit-project-start-date" class="form-label">{{ __('locale.Start Date') }}</label>
                            <input type="date" class="form-control" id="edit-project-start-date" name="edit-project-start-date"/>
                        </div>
                        <div class="mb-1 p-finish-date">
                            <label for="edit-project-finish-date" class="form-label">{{ __('locale.Approximate Deadline') }}</label>
                            <input type="date" class="form-control" id="edit-project-finish-date" name="edit-project-finish-date"/>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="edit-project-comments" class="form-label">{{ __('locale.Comment') }}</label>
                                <x-forms.tinymce-project-comment/>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mb-1 d-grid w-100">{{ __('locale.Save') }}</button>
                    </form>
                </div>
                <div class="tab-pane" id="services" aria-labelledby="services-tab" role="tabpanel">
                    <button class="btn btn-success add-service-button" onclick="onAddService()"><i class="bi bi-plus"></i> {{ __('locale.Add Service') }}</button><hr>
                    <table id="DataTableService" class="datatables-ajax table">
                        <thead>
                            <tr>
                                <th>{{ __('locale.ID') }}</th>
                                <th>{{ __('locale.Title') }}</th>
                                <th>{{ __('locale.Quantity') }}</th>
                                <th>{{ __('locale.Cost') }}</th>
                                <th>{{ __('locale.Total Cost') }}</th>
                                <th>{{ __('locale.Action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="tab-pane" id="expenses" aria-labelledby="expenses-tab" role="tabpanel">
                    <button class="btn btn-success add-expense-button" onclick="onAddExpense()"><i class="bi bi-plus"></i> {{ __('locale.Add Expense') }}</button><hr>
                    <table id="DataTableExpense" class="datatables-ajax table">
                        <thead>
                            <tr>
                                <th>{{ __('locale.ID') }}</th>
                                <th>{{ __('locale.Invoice') }}</th>
                                <th>{{ __('locale.Item Title') }}</th>
                                <th>{{ __('locale.Quantity') }}</th>
                                <th>{{ __('locale.Cost') }}</th>
                                <th>{{ __('locale.Total Cost') }}</th>
                                <th>{{ __('locale.Action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="tab-pane" id="rentals" aria-labelledby="rentals-tab" role="tabpanel">
                    <button class="btn btn-success add-rental-button" onclick="onAddRental()"><i class="bi bi-plus"></i> {{ __('locale.Add Rental') }}</button><hr>
                    <table id="DataTableRental" class="datatables-ajax table">
                        <thead>
                            <tr>
                                <th>{{ __('locale.ID') }}</th>
                                <th>{{ __('locale.Item Title') }}</th>
                                <th>{{ __('locale.Quantity') }}</th>
                                <th>{{ __('locale.Price Per Unit') }}</th>
                                <th>{{ __('locale.Action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="tab-pane" id="incomes" aria-labelledby="incomes-tab" role="tabpanel">
                    <button class="btn btn-success add-income-button" onclick="onAddIncome()"><i class="bi bi-plus"></i> {{ __('locale.Add Income') }}</button>
                    <a href="/invoice/project/{{ request()->id }}" class="btn btn-primary" ><i class="bi bi-receipt"></i> {{ __('locale.Generate Invoice') }}</a><hr>
                    <table id="DataTableIncome" class="datatables-ajax table">
                        <thead>
                            <tr>
                                <th>{{ __('locale.ID') }}</th>
                                <th>{{ __('locale.Invoice') }}</th>
                                <th>{{ __('locale.Item ID') }}</th>
                                <th>{{ __('locale.Item Title') }}</th>
                                <th>{{ __('locale.Cost') }}</th>
                                <th>{{ __('locale.Action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="tab-pane" id="users" aria-labelledby="users-tab" role="tabpanel">
                    <button class="btn btn-success add-user-button" onclick="onAddUser()"><i class="bi bi-plus"></i> {{ __('locale.Add Owner') }}</button>
                    <hr>
                    <div class="row">
                        <div class="col-3">
                            <div class="progress-wrapper">
                                <div id="example-caption">{{ __('locale.Remaining Percentage of Project') }}: <span id="edit-project-user-remaining"></span></div>
                                <div class="progress progress-bar-primary">
                                    <div id="edit-project-user-progressbar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="" aria-describedby="example-caption"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table id="DataTableUser" class="datatables-ajax table">
                        <thead>
                            <tr>
                                <th>{{ __('locale.ID') }}</th>
                                <th>{{ __('locale.User') }}</th>
                                <th>{{ __('locale.Percentage') }}</th>
                                <th>{{ __('locale.Action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="tab-pane" id="files" aria-labelledby="files-tab" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-3">
                            <h4>{{ __('locale.Add Document') }}</h4>
                            <form id="createDocumentForm" class="mt-2" method="POST">
                                <div class="mb-1">
                                    <label for="create-document-title" class="form-label">{{ __('locale.Title') }}</label>
                                    <input type="text" class="form-control" id="create-document-title" name="create-document-title"/>
                                </div>
                                <div class="mb-1">
                                    <label for="create-document-file" class="form-label">{{ __('locale.File') }}</label>
                                    <input type="file" class="form-control fuploader" name="create-document-file" id="create-document-file" data-file-id=""/>
                                </div>
                                <button type="submit" class="btn btn-primary mb-1 d-grid w-100">{{ __('locale.Add') }}</button>
                            </form>
                        </div>
                        <div class="col-lg-9">
                            <h4>{{ __('locale.Documents') }}</h4>
                            <ul class="list-group" id="document-list"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Edit Invoice -->

@include('modals.add-project-expense')
@include('modals.add-project-rental')

@include('modals.add-project-service')
@include('modals.edit-project-service')
@include('modals.edit-project-rental')

@include('modals.add-project-income')
@include('modals.add-project-user')

@include('modals.edit-project-user')

@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.js')) }}"></script>
@endsection


@section('page-script')
<x-head.tinymce-config/>

<script type="text/javascript">
$(async function() {
    'use strict';

    var id = $('#edit-project-id').val();
    var tab = $('#nth-tab').val();

    $(".nav-item:nth-child("+tab+") .nav-link").addClass('active');
    $(".tab-pane:nth-child("+tab+")").addClass('active');

    /** Edit Project Tab */
    window.onEditProject = () => {
        AxiosGET('/api/project/detail/' + id, (r) => {

            let response = r.data;

            if(response.status == true){

                FillSelect2('#edit-labels', '/api/labels/list-all', true, response.data.labels);
                FillSelect2('#edit-project-client-id', '/api/client/list-all', false, [response.data.project.client_id]);
                FillSelect2('#edit-project-status-id', '/api/status/list-all', false, [response.data.project.status_id]);


                if(response.data.project.core_status == 1){
                    $('.preparing-btn').removeClass('btn-outline-warning');
                    $('.preparing-btn').addClass('btn-warning');
                }else if(response.data.project.core_status == 2){
                    $('.ongoing-btn').removeClass('btn-outline-success');
                    $('.ongoing-btn').addClass('btn-success');
                }else if(response.data.project.core_status == 3){
                    $('.completed-btn').removeClass('btn-outline-dark');
                    $('.completed-btn').addClass('btn-dark');
                }

                $('#edit-project-title').val(response.data.project.title);
                $('#edit-project-date').val(response.data.project.date);
                $('#edit-project-start-date').val(response.data.project.start_date);
                $('#edit-project-finish-date').val(response.data.project.finish_date);
        
                $('#edit-project-user-progressbar').attr('style', 'width: ' + response.data.project.percentage_ownerness + '%');
                $('#edit-project-user-remaining').html(response.data.project.human_ownerness);
            }

        }, (error) => {

            if(error.response.status == 404){
                return location.href = '/projects';
            }

        })
    }

    onEditProject();

    var editProjectForm = $('#editProjectForm');

    editProjectForm.validate({
        rules: {
            'edit-project-client-id': {
                required: true,
            },
            'edit-project-title': {
                required: true,
            },
            'edit-project-start-date': {
                required: true,
            }
        }
    });

    editProjectForm.submit(() => {
        event.preventDefault();

        if(!editProjectForm.valid()){
            return;
        }

        let client_id = $('#edit-project-client-id').val();
        let status_id = $('#edit-project-status-id').val();
        let title = $('#edit-project-title').val();
        let labels = $('#edit-labels').val();
        let start_date = $('#edit-project-start-date').val();
        let finish_date = $('#edit-project-finish-date').val();

        AxiosPOST('/api/project/edit/' + id, {client_id,status_id, title, start_date, finish_date, labels}, (r) => {

            let response = r.data;

            if(response.status == true){

                location.href = '/projects';

            }

        });
    });

    window.onCloseProject = () =>{
        AxiosAskGET('/api/project/close-project/' + id, (r) => {

            let response = r.data;

            if(response.status == true){
                ToastAlert(response.message, 'success');

                onEditProject();
                DataTableExpense.ajax.reload();
                DataTableRental.ajax.reload();
                DataTableIncome.ajax.reload();
                DataTableUser.ajax.reload();
            }

        }, window.JSLang['Are you sure?'], window.JSLang['This project closed and you will not be able to revert this!'], window.JSLang['Yes, do it!'])
    }
    /** Edit Project Tab */

    /** Services Tab */
    var addServiceModal = new bootstrap.Modal($('#addServiceModal'));
    var addServiceForm = $('#addServiceForm');

    var editServiceModal = new bootstrap.Modal($('#editServiceModal'));
    var editServiceForm = $('#editServiceForm');

    var DataTableService = $('#DataTableService').DataTable({
        processing: true,
        dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        ajax: {
            "url": "/api/project-services/list-datatable-filter",
            "data": {
                "project_id": id
            },
            "dataSrc": function (json) {
                return json.data;
            },
            "complete": function(json) {
                if (json.responseJSON.hidden_columns !== undefined) {
                    json.responseJSON.hidden_columns.forEach(function(columnIndex) {
                        $('#DataTableService').DataTable().column(columnIndex).visible(false);
                    });
                }
            }
        },
        language: {
            paginate: {
                previous: '&nbsp;',
                next: '&nbsp;'
            }
        }
    });

    addServiceForm.validate({
        rules: {
            'add-service-id': {
                required: true,
            },
            'add-service-quantity': {
                required: true
            },
            'add-service-price': {
                required: true
            }
        }
    });

    addServiceForm.submit(() => {
        event.preventDefault();

        if(!addServiceForm.valid()){
            return;
        }

        let service_id = $('#add-service-id').val();
        let quantity = $('#add-service-quantity').val();
        let price = $('#add-service-price').val();

        AxiosPOST('/api/project-services/create', {project_id: id, service_id, quantity, price}, (r) => {

            let response = r.data;

            if(response.status == true){

                addServiceModal.hide();
                ToastAlert(response.message, 'success');

                DataTableService.ajax.reload();
                onEditProject();

            }

        });
    });


    editServiceForm.validate({
        rules: {
            'edit-service-id': {
                required: true,
            },
            'edit-service-quantity': {
                required: true
            },
            'edit-service-price': {
                required: true
            }
        }
    });

    editServiceForm.submit(() => {
        event.preventDefault();

        if(!addServiceForm.valid()){
            return;
        }

        let id = $('#edit-project-service-id').val();
        let service_id = $('#edit-service-id').val();
        let quantity = $('#edit-service-quantity').val();
        let price = $('#edit-service-price').val();

        AxiosPOST('/api/project-services/edit/' + id, {service_id, quantity, price}, (r) => {

            let response = r.data;

            if(response.status == true){

                editServiceModal.hide();
                ToastAlert(response.message, 'success');

                DataTableService.ajax.reload();
                onEditProject();
            }

        });
    });

    window.onEditService = async (ID) => {

        let service = await AxiosGET('/api/project-services/detail/' + ID);
        let service_data = service.data.data;

        if(!service_data){
            return;
        }

        await FillSelect2('#edit-service-id', '/api/services/list-all', false, [service_data.service_id]);

        $('#edit-project-service-id').val(service_data.id);
        $('#edit-service-id').val(service_data.service_id);
        $('#edit-service-quantity').val(service_data.quantity);
        $('#edit-service-price').val(service_data.price);
        
        $('#edit-service-id').on('select2:select', function (e) {
            AxiosGET('/api/service/detail/' + $(this).val(), (r) => {
                let response = r.data;
                if(response.status == true){
                    $('#edit-service-price').val(response.data.price);
                }
            })
        });


        editServiceModal.show();
    }


    window.onAddService = () => {
        ResetForms('#addServiceForm');
        addServiceModal.show();

        FillSelect2('#add-service-id', '/api/services/list-all', false, []);

        $('#add-service-id').on('select2:select', function (e) {
            AxiosGET('/api/service/detail/' + $(this).val(), (r) => {
                let response = r.data;
                if(response.status == true){
                    $('#add-service-price').val(response.data.price);
                }
            })
        });

        $('#add-service-quantity').val(1);
    }

    window.onDeleteService = (ID) => {
        AxiosAskGET('/api/project-services/delete/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){
                ToastAlert(response.message, 'success');
                DataTableService.ajax.reload();
            }

        }, window.JSLang['Are you sure?'], window.JSLang['This will deleted and you will not be able to revert this!'], window.JSLang['Yes, do it!'])
    }
    /** Services Tab */


    /** Expenses Tab */
    var addExpenseModal = new bootstrap.Modal($('#addExpenseModal'));
    var addExpenseForm = $('#addExpenseForm');

    var DataTableExpense = $('#DataTableExpense').DataTable({
        processing: true,
        dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        ajax: {
            "url": "/api/project-expense/list-datatable-filter",
            "data": {
                "project_id": id
            },
            "dataSrc": function (json) {
                return json.data;
            },
            "complete": function(json) {
                if (json.responseJSON.hidden_columns !== undefined) {
                    json.responseJSON.hidden_columns.forEach(function(columnIndex) {
                        $('#DataTableExpense').DataTable().column(columnIndex).visible(false);
                    });
                }
            }
        },
        language: {
            paginate: {
                previous: '&nbsp;',
                next: '&nbsp;'
            }
        }
    });

    addExpenseForm.validate({
        rules: {
            'add-expense-item-id': {
                required: true,
            },
            'add-expense-quantity': {
                required: true
            }
        }
    });

    addExpenseForm.submit(() => {
        event.preventDefault();

        if(!addExpenseForm.valid()){
            return;
        }

        let item_id = $('#add-expense-item-id').val();
        let quantity = $('#add-expense-quantity').val();

        AxiosPOST('/api/project-expense/create', {project_id: id, item_id, quantity}, (r) => {

            let response = r.data;

            if(response.status == true){

                addExpenseModal.hide();
                ToastAlert(response.message, 'success');

                DataTableExpense.ajax.reload();
                onEditProject();

            }

        });
    });

    window.onAddExpense = () => {
        ResetForms('#addExpenseForm');
        addExpenseModal.show();

        FillSelect2('#add-expense-item-id', '/api/purchase-invoice-item/list-short-term', false);
        $('#add-expense-quantity').val(1);
    }

    window.onDeleteExpense = (ID) => {
        AxiosAskGET('/api/project-expense/delete/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){
                ToastAlert(response.message, 'success');
                DataTableExpense.ajax.reload();

                onEditProject();
            }

        }, window.JSLang['Are you sure?'], window.JSLang['This will deleted and you will not be able to revert this!'], window.JSLang['Yes, do it!'])
    }
    /** Expenses Tab */


    /** Rentals Tab */
    var addRentalModal = new bootstrap.Modal($('#addRentalModal'));
    var addRentalForm = $('#addRentalForm');
    var editRentalModal = new bootstrap.Modal($('#editRentalModal'));
    var editRentalForm = $('#editRentalForm');

    var DataTableRental = $('#DataTableRental').DataTable({
        processing: true,
        dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        ajax: {
            "url": "/api/project-rental/list-datatable-filter",
            "data": {
                "project_id": id
            },
            "dataSrc": function (json) {
                return json.data;
            },
            "complete": function(json) {
                if (json.responseJSON.hidden_columns !== undefined) {
                    json.responseJSON.hidden_columns.forEach(function(columnIndex) {
                        $('#DataTableRental').DataTable().column(columnIndex).visible(false);
                    });
                }
            }
        },
        language: {
            paginate: {
                previous: '&nbsp;',
                next: '&nbsp;'
            }
        }
    });


    DataTableRental.on('click', '.isreturned', function (e) {

        const elem = document.getElementById(e.target.id);

        let ID = elem.getAttribute('data-id');
        let is_returned = (elem.checked == true) ? 1 : 0;

        AxiosPOST('/api/project-rental/set-returned/' + ID, {is_returned}, (r) => {

            let response = r.data;

            if(response.status == true){
                ToastAlert(response.message, 'success');
                DataTableRental.ajax.reload();
            }

        })

    });

    addRentalForm.validate({
        rules: {
            'add-rental-id': {
                required: true,
            },
            'add-rental-quantity': {
                required: true
            },
            'add-rental-price': {
                required: true
            }
        }
    });

    addRentalForm.submit(() => {
        event.preventDefault();

        if(!addRentalForm.valid()){
            return;
        }

        let rental_id = $('#add-rental-id').val();
        let quantity = $('#add-rental-quantity').val();
        let price = $('#add-rental-price').val();

        AxiosPOST('/api/project-rental/create', {project_id: id, rental_id, quantity, price}, (r) => {

            let response = r.data;

            if(response.status == true){

                addRentalModal.hide();
                ToastAlert(response.message, 'success');

                DataTableRental.ajax.reload();
                onEditProject();

            }

        });

        
    });

    window.onAddRental = () => {
        ResetForms('#addRentalForm');
        addRentalModal.show();

        FillSelect2('#add-rental-id', '/api/rental/list-available', false, []);
        $('#add-rental-quantity').val(1);

        $('#add-rental-id').on('select2:select', function (e) {
            AxiosGET('/api/rental/detail/' + $(this).val(), (r) => {
                let response = r.data;
                if(response.status == true){
                    $('#add-rental-price').val(response.data.price);
                }
            })
        });
    }

    editRentalForm.validate({
        rules: {
            'edit-rental-id': {
                required: true,
            },
            'edit-rental-quantity': {
                required: true
            },
            'edit-rental-price': {
                required: true
            }
        }
    });

    editRentalForm.submit(() => {
        event.preventDefault();

        if(!editRentalForm.valid()){
            return;
        }

        let rental_id = $('#edit-rental-id').val();
        let quantity = $('#edit-rental-quantity').val();
        let price = $('#edit-rental-price').val();

        AxiosPOST('/api/project-rental/edit/' + rental_id, {quantity, price}, (r) => {

            let response = r.data;

            if(response.status == true){

                editRentalModal.hide();
                ToastAlert(response.message, 'success');

                DataTableRental.ajax.reload();
            }

        });

        
    });

    window.onEditRental = async (ID) => {

        let rental = await AxiosGET('/api/project-rental/detail/' + ID);
        let rental_data = rental.data.data;

        if(!rental_data){
            return;
        }

        $('#edit-rental-id').val(rental_data.rental_id);
        $('#edit-rental-quantity').val(rental_data.quantity);
        $('#edit-rental-price').val(rental_data.price);


        editRentalModal.show();
    }

    window.onDeleteRental = (ID) => {
        AxiosAskGET('/api/project-rental/delete/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){
                ToastAlert(response.message, 'success');
                DataTableRental.ajax.reload();
            }

        }, window.JSLang['Are you sure?'], window.JSLang['This will deleted and you will not be able to revert this!'], window.JSLang['Yes, do it!'])
    }
    /** End Rentals Tab */

    /** Income Tab */
    var addIncomeModal = new bootstrap.Modal($('#addIncomeModal'));
    var addIncomeForm = $('#addIncomeForm');

    var DataTableIncome = $('#DataTableIncome').DataTable({
        processing: true,
        dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        ajax: {
            "url": "/api/project-income/list-datatable-filter",
            "data": {
                "project_id": id
            },
            "dataSrc": function (json) {
                return json.data;
            },
            "complete": function(json) {
                if (json.responseJSON.hidden_columns !== undefined) {
                    json.responseJSON.hidden_columns.forEach(function(columnIndex) {
                        $('#DataTableIncome').DataTable().column(columnIndex).visible(false);
                    });
                }
            }
        },
        language: {
            paginate: {
                previous: '&nbsp;',
                next: '&nbsp;'
            }
        }
    });

    addIncomeForm.validate({
        rules: {
            'add-income-item-id': {
                required: true,
            },
        }
    });

    addIncomeForm.submit(() => {
        event.preventDefault();

        if(!addIncomeForm.valid()){
            return;
        }

        let item_id = $('#add-income-item-id').val();

        AxiosPOST('/api/project-income/create', {project_id: id, item_id}, (r) => {

            let response = r.data;

            if(response.status == true){

                addIncomeModal.hide();
                ToastAlert(response.message, 'success');

                DataTableIncome.ajax.reload();
                onEditProject();

            }

        });
    });

    window.onAddIncome = () => {
        ResetForms('#addIncomeForm');
        addIncomeModal.show();

        FillSelect2('#add-income-item-id', '/api/invoice-item/list-filter', false);
    }

    window.onDeleteIncome = (ID) => {
        AxiosAskGET('/api/project-income/delete/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){
                ToastAlert(response.message, 'success');
                DataTableIncome.ajax.reload();
            }

        }, window.JSLang['Are you sure?'], window.JSLang['This will deleted and you will not be able to revert this!'], window.JSLang['Yes, do it!'])
    }

    /** Income Tab */

    /** User Tab */
    var addUserModal = new bootstrap.Modal($('#addUserModal'));
    var addUserForm = $('#addUserForm');

    var editUserModal = new bootstrap.Modal($('#editUserModal'));
    var editUserForm = $('#editUserForm');

    var DataTableUser = $('#DataTableUser').DataTable({
        processing: true,
        dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        ajax: {
            "url": "/api/project-user/list-datatable-filter",
            "data": {
                "project_id": id
            },
            "dataSrc": function (json) {
                return json.data;
            },
            "complete": function(json) {
                if (json.responseJSON.hidden_columns !== undefined) {
                    json.responseJSON.hidden_columns.forEach(function(columnIndex) {
                        $('#DataTableUser').DataTable().column(columnIndex).visible(false);
                    });
                }
            }
        },
        language: {
            paginate: {
                previous: '&nbsp;',
                next: '&nbsp;'
            }
        }
    });

    addUserForm.validate({
        rules: {
            'add-user-id': {
                required: true,
            },
        }
    });

    addUserForm.submit(() => {
        event.preventDefault();

        if(!addUserForm.valid()){
            return;
        }

        let user_id = $('#add-user-id').val();

        AxiosPOST('/api/project-user/create', {project_id: id, user_id}, (r) => {

            let response = r.data;

            if(response.status == true){

                addUserModal.hide();
                ToastAlert(response.message, 'success');

                DataTableUser.ajax.reload();
                onEditProject();

            }

        });
    });

    editUserForm.validate({
        rules: {
            'edit-user-percentage': {
                required: true,
            },
        }
    });

    editUserForm.submit(() => {
        event.preventDefault();

        if(!addUserForm.valid()){
            return;
        }

        let id = $('#edit-project-user-id').val();
        let percentage = $('#edit-user-percentage').val();

        AxiosPOST('/api/project-user/edit/' + id, {percentage}, (r) => {

            let response = r.data;

            if(response.status == true){

                editUserModal.hide();
                ToastAlert(response.message, 'success');

                DataTableUser.ajax.reload();
                onEditProject();

            }

        });
    });

    window.onAddUser = () => {
        ResetForms('#addUserForm');
        addUserModal.show();

        FillSelect2('#add-user-id', '/api/user/list-all', false);
    }

    window.onEditUser = async (ID) => {

        let user = await AxiosGET('/api/project-user/detail/' + ID);
        let user_data = user.data.data;

        if(!user_data){
            return;
        }

        await FillSelect2('#edit-user-id', '/api/user/list-all', false, [user_data.user_id]);

        $('#edit-project-user-id').val(user_data.id);
        $('#edit-user-id').val(user_data.user_id);
        $('#edit-user-percentage').val(user_data.percentage);
        
        editUserModal.show();

    }

    window.onDeleteUser = (ID) => {
        AxiosAskGET('/api/project-user/delete/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){
                ToastAlert(response.message, 'success');
                DataTableUser.ajax.reload();

                onEditProject();
            }

        }, window.JSLang['Are you sure?'], window.JSLang['This will deleted and you will not be able to revert this!'], window.JSLang['Yes, do it!'])
    }
    /** User Tab */

   /** Project Document - Tab */
   var createDocumentForm = $('#createDocumentForm');

    createDocumentForm.validate({
        rules: {
            'create-document-title': {
                required: true,
            },
            'create-document-file': {
                required: true,
            },
        }
    });

    createDocumentForm.submit(() => {
        event.preventDefault();

        if(!createDocumentForm.valid()){
            return;
        }

        let project_id = $('#edit-project-id').val();
        let title = $('#create-document-title').val();

        (async () => {

            if($('#create-document-file').val()){
                await upload_form_files('#create-document-file', 'docs');
            }

            let file_id = $('#create-document-file').attr('data-file-id');

            AxiosPOST('/api/project-document/create', {project_id, title, file_id}, (r) => {
                let response = r.data;

                if(response.status == true){

                    ToastAlert(response.message, 'success');
                    ResetForms('#createDocumentForm');

                    window.listDocuments(project_id);
                }
            });
        })();
    });

    window.listDocuments = async(project_id) => {

        let documents_response = await AxiosGET('/api/project-document/list-by-project/' + project_id);
        let documents = documents_response.data.data;

        let document_list_html = '';

        documents.forEach(doc => {
            
            document_list_html += `
            <li class="list-group-item d-flex align-items-center">
                <a target="_blank" class="me-1" href="#" onclick="onDeleteDocument(${doc.id}, ${doc.project_id})" title="Delete"><i class="bi bi-x"></i></a>
                <span>${doc.title}</span>
                <a target="_blank" class="ms-auto" href="${doc.preview_link}"><i class="bi bi-eye"></i> Preview</a>
                <a target="_blank" class="ms-auto" href="${doc.download_link}"><i class="bi bi-cloud-download"></i> Download</a>
            </li>`;

        });

        $('#document-list').html(document_list_html);

        feather.replace();
    }

    window.onDeleteDocument = (ID, project_id) => {

        AxiosAskGET('/api/project-document/delete/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){

                ToastAlert(response.message, 'success');
                window.listDocuments(project_id);

            }

        }, window.JSLang['Are you sure?'], window.JSLang['This will deleted and you will not be able to revert this!'], window.JSLang['Yes, do it!'])

    }
    /** Project Document - Tab */

    window.listDocuments(id);

 /** Project Statuses */

    window.onChangePreparingProject = (ID) => {
        AxiosGET('/api/project/change-status-preparing/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){
                $('.completed-btn').removeClass('btn-dark');
                $('.completed-btn').addClass('btn-outline-dark');
                $('.ongoing-btn').removeClass('btn-success');
                $('.ongoing-btn').addClass('btn-outline-success');

                $('.preparing-btn').removeClass('btn-outline-warning');
                $('.preparing-btn').addClass('btn-warning');
                ToastAlert(response.message, 'success');
            }
        });
    }

    window.onChangeOnGoingProject = (ID) => {
        AxiosGET('/api/project/change-status-ongoing/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){
                $('.preparing-btn').removeClass('btn-warning');
                $('.preparing-btn').addClass('btn-outline-warning');
                $('.completed-btn').removeClass('btn-dark');
                $('.completed-btn').addClass('btn-outline-dark');

                $('.ongoing-btn').removeClass('btn-outline-success');
                $('.ongoing-btn').addClass('btn-success');
                ToastAlert(response.message, 'success');
            }
        });
    }

    window.onChangeToCompletedProject = (ID) => {
        AxiosGET('/api/project/change-status-completed/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){
                $('.preparing-btn').removeClass('btn-warning');
                $('.preparing-btn').addClass('btn-outline-warning');
                $('.ongoing-btn').removeClass('btn-success');
                $('.ongoing-btn').addClass('btn-outline-success');
                
                $('.completed-btn').removeClass('btn-outline-dark');
                $('.completed-btn').addClass('btn-dark');
                ToastAlert(response.message, 'success');
            }
        });
    }
});

</script>
@endsection