@extends('layouts/contentLayoutMaster')

@section('title', 'Projects')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
@endsection

@section('content')
<!-- Projects -->
<section id="ajax-datatable">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">{{ __('locale.Projects') }}</h4>

                    <div class="mt-1 col-3">
                        <select class="form-select" name="project-type" id="project-type">
                            <option value="1" selected>{{ __('locale.Preparing') }} / {{ __('locale.Ongoing') }}</option>
                            <option value="3">{{ __('locale.Finished') }}</option>
                            <option value="0">{{ __('locale.All') }}</option>
                        </select>
                    </div>

                    <button
                        class="btn btn-success permission-selector"
                        type="button"
                        onclick="onCreate()"
                        data-permission-id="23"
                        style="display: none"
                    >
                    {{ __('locale.New Project') }}
                    </button>
                </div>
                <div class="card-datatable">
                    <table id="DataTable" class="datatables-ajax table">
                        <thead>
                            <tr>
                                <th>{{ __('locale.ID') }}</th>
                                <th></th>
                                <th>{{ __('locale.Title') }}</th>
                                <th>{{ __('locale.Aprx. Deadline') }}</th>
                                <th>{{ __('locale.Owners') }}</th>
                                <th>{{ __('locale.Status') }}</th>
                                <th>{{ __('locale.Labels') }}</th>
                                <th>{{ __('locale.Client') }}</th>
                                <th>{{ __('locale.Profit') }}</th>
                                <th>{{ __('locale.Action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Projects -->
@include('modals.create-project')
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

    var createModal = new bootstrap.Modal($('#createModal'));

    window.getCurrentType = () => {
        return $('#project-type').val();
    } 

    var DataTable = $('#DataTable').DataTable({
        processing: true,
        dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        ajax: {
            url: '/api/project/list-datatable',
            method: 'GET',
            "data": function(d) {
                d.type = $('#project-type').val();
            },
            "dataSrc": function (json) {
                return json.data;
            },
            "complete": function(json) {
                if (json.responseJSON.hidden_columns !== undefined) {
                    json.responseJSON.hidden_columns.forEach(function(columnIndex) {
                        $('#DataTable').DataTable().column(columnIndex).visible(false);
                    });
                }
            }
        },
        order: [[0, 'desc']],
        language: {
            paginate: {
                previous: '&nbsp;',
                next: '&nbsp;'
            }
        },
        createdRow: function(row, data, dataIndex) {
            // Add a class to the row based on the data
            if (data[1] === 1) {
                $(row).addClass('bg-warning-light');
            } else if (data[1] === 2) {
                $(row).addClass('bg-success-light');
            } else if (data[1] === 3) {
                $(row).addClass('bg-dark-light');
            }
        },
        columnDefs: [
            { className: "avatar-group", "targets": [ 5 ] },
            { "visible": false, "targets": [ 1 ] },
            { "orderable": false, "targets": [ 8, 9 ] }
        ],
        
        fnDrawCallback: function() {
            [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]')).map(function(e){return new bootstrap.Popover(e,{'html':true})});
            [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(function(e){return new bootstrap.Tooltip(e)});
        }
    });

    $( "#project-type" ).change(function() {
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
            'create-start-date': {
                required: true
            },
            'create-finish-date': {
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
        let start_date = $('#create-start-date').val();
        let finish_date = $('#create-finish-date').val();

        AxiosPOST('/api/project/create', {client_id, title, start_date, finish_date}, (r) => {

            let response = r.data;

            if(response.status == true){

                createModal.hide();
                ToastAlert(response.message, 'success');

                DataTable.ajax.reload();

            }

        })
    });

    window.onCreate = () => {
        ResetForms('#createForm');
        createModal.show();

        FillSelect2('#create-client-id', '/api/client/list-all', false, []);
    }

    window.onEdit = (ID,tab) => {
        location.href = 'project/edit/' + ID + '/' + tab;
    }

    window.onDelete = (ID) => {
        AxiosAskGET('/api/project/delete/' + ID, (r) => {

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