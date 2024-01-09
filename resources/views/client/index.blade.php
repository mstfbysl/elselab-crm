@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Clients'))

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
@endsection

@section('content')
<!-- Clients -->
<section id="ajax-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">{{ __('locale.Clients') }}</h4>
                    <button
                        class="btn btn-success permission-selector"
                        type="button"
                        onclick="onCreate()"
                        data-permission-id="10"
                        style="display: none"
                    >
                    {{ __('locale.New Client') }}
                    </button>
                </div>
                <div class="card-datatable">
                    <table id="DataTable" class="datatables-ajax table">
                        <thead>
                            <tr>
                                <th>{{ __('locale.ID') }}</th>
                                <th>{{ __('locale.Name') }}</th>
                                <th>{{ __('locale.Code') }}</th>
                                <th>{{ __('locale.Phone') }}</th>
                                <th>{{ __('locale.Email') }}</th>
                                <th>{{ __('locale.Vat') }}</th>
                                <th>{{ __('locale.Action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Client -->

@include('modals.create-new-client')
@include('modals.edit-client')
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

    var createModal = new bootstrap.Modal($('#createClientModal'));
    var editModal = new bootstrap.Modal($('#editClientModal'));

    var DataTable = $('#DataTable').DataTable({
        processing: true,
        dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        ajax: '/api/client/list-datatable',
        order: [[0, 'desc']],
        language: {
            paginate: {
                previous: '&nbsp;',
                next: '&nbsp;'
            }
        }
    });

    var imageId = null;
    var createForm = $('#createClientForm');
    var editForm = $('#editClientForm');


    /** Client Document - Tab */
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

        let client_id = $('#edit-id').val();
        let title = $('#create-document-title').val();

        (async () => {

            if($('#create-document-file').val()){
                await upload_form_files('#create-document-file', 'docs');
            }

            let file_id = $('#create-document-file').attr('data-file-id');

            AxiosPOST('/api/client-document/create', {client_id, title, file_id}, (r) => {
                let response = r.data;

                if(response.status == true){

                    ToastAlert(response.message, 'success');
                    ResetForms('#createDocumentForm');

                    window.listDocuments(client_id);
                }
            });
        })();
    });

    window.listDocuments = async(client_id) => {

        let documents_response = await AxiosGET('/api/client-document/list-by-client/' + client_id);
        let documents = documents_response.data.data;

        let document_list_html = '';

        documents.forEach(doc => {
            
            document_list_html += `
            <li class="list-group-item d-flex align-items-center">
                <a target="_blank" class="me-1" href="#" onclick="onDeleteDocument(${doc.id}, ${doc.client_id})" title="Delete"><i class="bi bi-x"></i></a>
                <span>${doc.title}</span>
                <a target="_blank" class="ms-auto" href="${doc.file_link}"><i class="bi bi-cloud-download"></i> Download</a>
            </li>`;

        });

        $('#document-list').html(document_list_html);

    }

    window.onDeleteDocument = (ID, client_id) => {

        AxiosAskGET('/api/client-document/delete/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){

                ToastAlert(response.message, 'success');
                window.listDocuments(client_id);

            }

        }, window.JSLang['Are you sure?'], window.JSLang['This will deleted and you will not be able to revert this!'], window.JSLang['Yes, do it!'])

    }


    createForm.validate({
        rules: {
            'create-client-name': {
                required: true,
            },
            'create-client-address': {
                required: true
            }
        }
    });

    editForm.validate({
        rules: {
            'edit-name': {
                required: true,
            },
            'edit-address': {
                required: true
            }
        }
    });

    createForm.submit(() => {
        event.preventDefault();

        if(!createForm.valid()){
            return;
        }

        let type = $('#create-client-type').val();
        let name = $('#create-client-name').val();
        let code = $('#create-client-code').val();
        let address = $('#create-client-address').val();
        let phone = $('#create-client-phone').val();
        let email = $('#create-client-email').val();
        let vat_number = $('#create-client-vat-number').val();
        let comment = $('#create-client-comment').val();
        
        AxiosPOST('/api/client/create', {type, name, code, address, phone, email, vat_number, comment}, (r) => {

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
        let type = $('#edit-type').val();
        let name = $('#edit-name').val();
        let code = $('#edit-code').val();
        let address = $('#edit-address').val();
        let phone = $('#edit-phone').val();
        let email = $('#edit-email').val();
        let vat_number = $('#edit-vat-number').val();
        let comment = $('#edit-comment').val();
        let contract = $('#edit-contract').attr('data-file-id');

        AxiosPOST('/api/client/edit/' + id, {type, name, code, address, phone, email, vat_number, comment, contract}, (r) => {

            let response = r.data;

            if(response.status == true){

                editModal.hide();
                ToastAlert(response.message, 'success');

                DataTable.ajax.reload();

            }


        });
    });

    window.onCreate = () => {
        ResetForms('#createClientForm');
        createModal.show();

        FillSelect2('#create-client-type', '/api/system-definition/list-filter', false, [], {type: 'client_type'}); 
    }

    window.onEdit = (ID) => {
        ResetForms('#editClientForm');

        AxiosGET('/api/client/detail/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){

                FillSelect2('#edit-type', '/api/system-definition/list-filter', false, [response.data.type], {type: 'client_type'}); 

                $('#edit-id').val(response.data.id);
                $('#edit-type').val(response.data.type);
                $('#edit-name').val(response.data.name);
                $('#edit-code').val(response.data.code);
                $('#edit-address').val(response.data.address);
                $('#edit-phone').val(response.data.phone);
                $('#edit-email').val(response.data.email);
                $('#edit-vat-number').val(response.data.vat_number);
                $('#edit-comment').val(response.data.comment);

                if(response.data.file){
                    $('#client-contract').html('<a class="btn btn-info mb-1 d-grid w-100" href="'+response.data.file_preview+'" target="_blank"> Preview</a><br/>');
                }else{
                    $('#client-contract').html('');
                }
                
                window.listDocuments(response.data.id);
                editModal.show();
            }

        })
    }

    window.onDelete = (ID) => {
        AxiosAskGET('/api/client/delete/' + ID, (r) => {

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