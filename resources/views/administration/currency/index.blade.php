@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Currencies'))

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
@endsection

@section('content')
<!-- Currencies -->
<section id="ajax-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">{{ __('locale.Currencies') }}</h4>
                    <button
                        class="btn btn-success"
                        type="button"
                        onclick="onCreate()"
                    >
                    {{ __('locale.Create New Currency') }}
                    </button>
                </div>
                <div class="card-datatable">
                    <table id="DataTable" class="datatables-ajax table">
                        <thead>
                            <tr>
                                <th>{{ __('locale.ID') }}</th>
                                <th>{{ __('locale.Short Code') }}</th>
                                <th>{{ __('locale.Description') }}</th>
                                <th>{{ __('locale.Symbol') }}</th>
                                <th>{{ __('locale.Action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Currencies -->

@include('modals.create-currency')
@include('modals.edit-currency')
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
        ajax: '/api/currency/list-datatable',
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
            'create-short-code': {
                required: true,
            },
            'create-description': {
                required: true,
            },
        }
    });

    createForm.submit(() => {
        event.preventDefault();

        if(!createForm.valid()){
            return;
        }

        let short_code = $('#create-short-code').val();
        let description = $('#create-description').val();
        
        AxiosPOST('/api/currency/create', {short_code, description}, (r) => {

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
            'edit-short-code': {
                required: true,
            },
            'edit-description': {
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
        let short_code = $('#edit-short-code').val();
        let description = $('#edit-description').val();
        
        AxiosPOST('/api/currency/edit/' + id, {short_code, description}, (r) => {

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

        AxiosGET('/api/currency/detail/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){

                $('#edit-id').val(response.data.id);
                $('#edit-short-code').val(response.data.short_code);
                $('#edit-description').val(response.data.description);

                editModal.show();
            }

        })
    }

    window.onCreate = () => {
        ResetForms('#createForm');
        createModal.show();
    }

    window.onDelete = (ID) => {
        AxiosAskGET('/api/currency/delete/' + ID, (r) => {

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