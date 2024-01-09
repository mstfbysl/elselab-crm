@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Rentals'))

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
@endsection

@section('content')
<!-- Rentals -->
<section id="ajax-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">{{ __('locale.Rentals') }}</h4>
                    <button
                        class="btn btn-success permission-selector"
                        type="button"
                        onclick="onCreate()"
                        data-permission-id="19"
                        style="display: none"
                    >
                    {{ __('locale.Create New Rental') }}
                    </button>
                </div>
                <div class="card-datatable">
                    <table id="DataTable" class="datatables-ajax table">
                        <thead>
                            <tr>
                                <th>{{ __('locale.ID') }}</th>
                                <th>{{ __('locale.Title') }}</th>
                                <th>{{ __('locale.Total Quantity') }}</th>
                                <th>{{ __('locale.Rent price') }}</th>
                                <th>{{ __('locale.Action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Rentals -->

@include('modals.create-rental')
@include('modals.edit-rental')

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
    var editModal = new bootstrap.Modal($('#editModal'));

    var DataTable = $('#DataTable').DataTable({
        processing: true,
        dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        ajax: {
            url: '/api/rental/list-datatable',
            method: 'GET',
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
            'create-title': {
                required: true,
            },
        }
    });

    createForm.submit(() => {
        event.preventDefault();

        if(!createForm.valid()){
            return;
        }

        let title = $('#create-title').val();
        let price = $('#create-price').val();
        
        AxiosPOST('/api/rental/create', {title, price}, (r) => {

            let response = r.data;

            if(response.status == true){

                createModal.hide();
                ToastAlert(response.message, 'success');

                DataTable.ajax.reload();

            }

        });
    });

    var editForm = $('#editForm');

    editForm.validate({
        rules: {
            'edit-title': {
                required: true,
            },
        }
    });

    editForm.submit(() => {
        event.preventDefault();

        if(!editForm.valid()){
            return;
        }

        let id = $('#edit-id').val();
        let title = $('#edit-title').val();
        let price = $('#edit-price').val();
        
        AxiosPOST('/api/rental/edit/' + id, {title, price}, (r) => {

            let response = r.data;

            if(response.status == true){

                editModal.hide();
                ToastAlert(response.message, 'success');

                DataTable.ajax.reload();

            }

        });
    });

    window.onEdit = (ID) => {
        ResetForms('#editForm');

        AxiosGET('/api/rental/detail/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){

                $('#edit-id').val(response.data.id);
                $('#edit-title').val(response.data.title);
                $('#edit-price').val(response.data.price);

                editModal.show();
            }

        })
    }

    window.onCreate = () => {
        ResetForms('#createForm');
        createModal.show();
    }

    window.onDelete = (ID) => {
        AxiosAskGET('/api/rental/delete/' + ID, (r) => {

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