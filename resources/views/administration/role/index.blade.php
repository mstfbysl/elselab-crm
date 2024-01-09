@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Roles'))

@section('vendor-style')
  {{-- vendor css files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
@endsection

@section('content')
<!-- User Roles -->
<section id="ajax-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">{{ __('locale.Roles') }}</h4>
                    <button
                        class="btn btn-success"
                        type="button"
                        onclick="onCreate()"
                    >
                    {{ __('locale.Create New User Role') }}
                    </button>
                </div>
                <div class="card-datatable">
                    <table id="DataTable" class="datatables-ajax table">
                        <thead>
                            <tr>
                                <th>{{ __('locale.ID') }}</th>
                                <th>{{ __('locale.Title') }}</th>
                                <th>{{ __('locale.Action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ User Roles -->

@include('modals.create-user-role')
@include('modals.edit-user-role')
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
        ajax: '/api/role/list-datatable',
        language: {
            paginate: {
                previous: '&nbsp;',
                next: '&nbsp;'
            }
        }
    });

    var createForm = $('#createForm');
    var editForm = $('#editForm');

    createForm.validate({
        rules: {
            'create-title': {
                required: true,
            },
        }
    });

    editForm.validate({
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

        AxiosPOST('/api/role/create', {title}, (r) => {

            let response = r.data;

            if(response.status == true){

                createModal.hide();
                ToastAlert(response.message, 'success');

                DataTable.ajax.reload();

            }

        });
    });

    editForm.submit(() => {
        event.preventDefault();

        if(!editForm.valid()){
            return;
        }

        let id = $('#edit-id').val();
        let title = $('#edit-title').val();
        let permissions = $('#edit-permissions').val();

        AxiosPOST('/api/role/edit/' + id, {title, permissions}, (r) => {

            let response = r.data;

            if(response.status == true){

                editModal.hide();
                ToastAlert(response.message, 'success');

                DataTable.ajax.reload();

            }

        });
    });

    window.onCreate = () => {
        ResetForms('#createForm');
        createModal.show();
    }

    window.onEdit = (ID) => {
        ResetForms();

        AxiosGET('/api/role/detail/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){

                if(response.data.role.id != 1){
                    $('#edit-permissions-box').show();
                    FillSelect2('#edit-permissions', '/api/system-permission/list-all', true, response.data.permissions);
                }else{
                    $('#edit-permissions-box').hide();
                }

                $('#edit-id').val(response.data.role.id);
                $('#edit-title').val(response.data.role.title);

                editModal.show();
            }

        })
    }

    window.onDelete = (ID) => {
        AxiosAskGET('/api/role/delete/' + ID, (r) => {

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