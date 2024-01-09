@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Fix Assets'))

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
@endsection

@section('content')
<!-- Fix Assets -->
<section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">{{ __('locale.Fix Assets') }}</h4>
                    <button
                        class="btn btn-success permission-selector"
                        type="button"
                        onclick="onCreate()"
                        data-permission-id="39"
                        style="display: none"
                    >
                    {{ __('locale.Create Fix Asset') }}
                    </button>
                </div>
                <div class="card-datatable">
                    <table id="DataTable" class="datatables-ajax table">
                        <thead>
                            <tr>
                                <th>{{ __('locale.ID') }}</th>
                                <th>{{ __('locale.Invoice Title') }}</th>
                                <th>{{ __('locale.Item Title') }}</th>
                                <th>{{ __('locale.Serial') }}</th>
                                <th>{{ __('locale.Valid Date') }}</th>
                                <th>{{ __('locale.Item Cost') }}</th>
                                <th>{{ __('locale.Action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Fix Assets -->

@include('modals.create-fix-asset')
@include('modals.edit-fix-asset')
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

    var createModal = new bootstrap.Modal($('#createModal'));
    var editModal = new bootstrap.Modal($('#editModal'));

    var DataTable = $('#DataTable').DataTable({
        processing: true,
        dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        ajax: '/api/fix-asset/list/datatable',
        order: [[0, 'desc']],
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
            'create-fix-asset-item-id': {
                required: true,
            },
            'create-fix-asset-valid-date': {
                required: true,
            },
        }
    });

    createForm.submit(() => {
        event.preventDefault();

        if(!createForm.valid()){
            return;
        }

        let item_id = $('#create-fix-asset-item-id').val();
        let valid_date = $('#create-fix-asset-valid-date').val();
        let comment = $('#create-fix-asset-comment').val();
        
        AxiosPOST('/api/fix-asset/create', {item_id, valid_date, comment}, (r) => {

            let response = r.data;

            if(response.status == true){

                createModal.hide();
                ToastAlert(response.message, 'success');

                DataTable.ajax.reload();

            }

        });
    });

    window.onCreate = () => {
        ResetForms('#createForm');
        createModal.show();

        $('#create-fix-asset-quantity').val(1);

        FillSelect2('#create-fix-asset-item-id', '/api/purchase-invoice-item/list-fix-asset', false, []);
    }

    var editForm = $('#editForm');

    editForm.validate({
        rules: {
            'edit-fix-asset-valid-date': {
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
        let valid_date = $('#edit-fix-asset-valid-date').val();
        let comment = $('#edit-fix-asset-comment').val();
        
        AxiosPOST('/api/fix-asset/edit/' + id, {valid_date, comment}, (r) => {

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

        AxiosGET('/api/fix-asset/detail/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){

                $('#edit-id').val(response.data.id);
                $('#edit-fix-asset-valid-date').val(response.data.valid_date);
                $('#edit-fix-asset-comment').val(response.data.comment);

                editModal.show();
            }

        })
    }

    window.onDelete = (ID) => {
        AxiosAskGET('/api/fix-asset/delete/' + ID, (r) => {

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