@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Users'))

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
@endsection

@section('content')
<!-- Users -->
<section id="ajax-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">{{ __('locale.Users') }}</h4>
                    <button
                        class="btn btn-success"
                        type="button"
                        onclick="onCreate()"
                    >
                    {{ __('locale.Create New User') }}
                    </button>
                </div>
                <div class="card-datatable">
                    <table id="DataTable" class="datatables-ajax table">
                        <thead>
                            <tr>
                                <th>{{ __('locale.ID') }}</th>
                                <th>{{ __('locale.Role') }}</th>
                                <th>{{ __('locale.Name Surname') }}</th>
                                <th>{{ __('locale.Email') }}</th>
                                <th>{{ __('locale.Balance') }}</th>
                                <th>{{ __('locale.Action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Users -->

@include('modals.create-user')
@include('modals.edit-user')
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

    FillSelect2('#create-user-role-id', '/api/role/list-all', false, []);

    var DataTable = $('#DataTable').DataTable({
        processing: true,
        dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        ajax: '/api/user/list-datatable',
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
            'create-user-role-id': {
                required: true,
            },
            'create-fullname': {
                required: true,
            },
            'create-email': {
                required: true,
                email: true
            },
            'create-password': {
                required: true
            }
        }
    });

    editForm.validate({
        rules: {
            'edit_user-role-id': {
                required: true,
            },
            'edit_user-fullname': {
                required: true,
            },
            'edit_user-email': {
                required: true,
                email: true
            }
        }
    });

    createForm.submit(() => {
        event.preventDefault();

        if(!createForm.valid()){
            return;
        }

        let user_role_id = $('#create-user-role-id').val();
        let name_surname = $('#create-fullname').val();
        let email = $('#create-email').val();
        let password = $('#create-password').val();
        let profit_share = $('#create-profit-share').val();

        AxiosPOST('/api/user/create', {user_role_id, name_surname, email, password, profit_share}, (r) => {

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
        let user_role_id = $('#edit-user-role-id').val();
        let name_surname = $('#edit-fullname').val();
        let email = $('#edit-email').val();
        let password = $('#edit-password').val();
        let profit_share = $('#edit-profit-share').val();

        AxiosPOST('/api/user/edit/' + id, {user_role_id, name_surname, email, password, profit_share}, (r) => {

            let response = r.data;

            if(response.status == true){

                editModal.hide();
                ToastAlert(response.message, 'success');

                DataTable.ajax.reload();

            }

        });

    });

    /** User Document - Tab */
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

        let user_id = $('#edit-id').val();
        let title = $('#create-document-title').val();

        (async () => {

            if($('#create-document-file').val()){
                await upload_form_files('#create-document-file', 'docs');
            }

            let file_id = $('#create-document-file').attr('data-file-id');

            AxiosPOST('/api/user-document/create', {user_id, title, file_id}, (r) => {
                let response = r.data;

                if(response.status == true){

                    ToastAlert(response.message, 'success');
                    ResetForms('#createDocumentForm');

                    window.listDocuments(user_id);
                }
            });
        })();
    });

    window.listDocuments = async(user_id) => {

        let documents_response = await AxiosGET('/api/user-document/list-by-user/' + user_id);
        let documents = documents_response.data.data;

        let document_list_html = '';

        documents.forEach(doc => {
            
            document_list_html += `
            <li class="list-group-item d-flex align-items-center">
                <a target="_blank" class="me-1" href="#" onclick="onDeleteDocument(${doc.id}, ${doc.user_id})" title="Delete"><i class="bi bi-x"></i></a>
                <span>${doc.title}</span>
                <a target="_blank" class="ms-auto" href="${doc.file_link}"><i class="bi bi-cloud-download"></i> Download</a>
            </li>`;

        });

        $('#document-list').html(document_list_html);

        feather.replace();
    }

    window.onDeleteDocument = (ID, user_id) => {

        AxiosAskGET('/api/user-document/delete/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){

                ToastAlert(response.message, 'success');
                window.listDocuments(user_id);

            }

        }, window.JSLang['Are you sure?'], window.JSLang['This will deleted and you will not be able to revert this!'], window.JSLang['Yes, do it!'])

    }
    /** User Document - Tab */

    window.onCreate = () => {
        ResetForms('#createForm');
        createModal.show();
    }

    window.onEdit = (ID) => {
        ResetForms();

        AxiosGET('/api/user/detail/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){

                FillSelect2('#edit-user-role-id', '/api/role/list-all', false, [response.data.user_role_id]);

                $('#edit-id').val(response.data.id);
                $('#edit-fullname').val(response.data.name_surname);
                $('#edit-email').val(response.data.email);
                $('#edit-profit-share').val(response.data.profit_share);

                window.listDocuments(response.data.id);

                editModal.show();
            }

        });
    }

    window.onDelete = (ID) => {
        AxiosAskGET('/api/user/delete/' + ID, (r) => {

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