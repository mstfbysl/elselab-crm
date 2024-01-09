@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Labels'))

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
@endsection

@section('content')
<!-- Labels -->
<section id="ajax-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">{{ __('locale.Labels') }}</h4>
                    <button
                        class="btn btn-success"
                        type="button"
                        onclick="onCreate()"
                    >
                    {{ __('locale.Create New Label') }}
                    </button>
                </div>
                <div class="card-datatable">
                    <table id="DataTable" class="datatables-ajax table">
                        <thead>
                            <tr>
                                <th>{{ __('locale.ID') }}</th>
                                <th>{{ __('locale.Title') }}</th>
                                <th>{{ __('locale.Color') }}</th>
                                <th>{{ __('locale.Action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Labels -->

@include('modals.create-label')
@include('modals.edit-label')
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
        ajax: '/api/labels/list-datatable',
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
            'create-color': {
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
        let color = $('#create-color').val();
        
        AxiosPOST('/api/labels/create', {title, color}, (r) => {

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
            'edit-color': {
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
        let color = $('#edit-color').val();
        
        AxiosPOST('/api/labels/edit/' + id, {title, color}, (r) => {

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

        AxiosGET('/api/labels/detail/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){

                $('#edit-id').val(response.data.id);
                $('#edit-title').val(response.data.title);
                $('#edit-color').val(response.data.color);

                editModal.show();
            }

        })
    }

    window.onCreate = () => {
        ResetForms('#createForm');
        createModal.show();
    }

    window.onDelete = (ID) => {
        AxiosAskGET('/api/labels/delete/' + ID, (r) => {

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