@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Offers'))

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
@endsection

@section('content')
<!-- Offers -->
<section id="ajax-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">{{ __('locale.Offers') }}</h4>
                    <button 
                        class="btn btn-success permission-selector" 
                        type="button" 
                        onclick="onCreate()"
                        data-permission-id="27"
                        style="display: none"
                        >{{ __('locale.New Offer') }}</button>
                </div>
                <div class="card-datatable">
                    <table id="DataTable" class="datatables-ajax table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('locale.Offer No') }}</th>
                                <th>{{ __('locale.Client') }}</th>
                                <th>{{ __('locale.Total') }}</th>
                                <th class="text-truncate">{{ __('locale.Date') }}</th>
                                <th>{{ __('locale.isSend') }}</th>
                                <th class="cell-fit">{{ __('locale.Action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Offers -->
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

    var DataTable = $('#DataTable').DataTable({
        processing: true,
        dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        ajax: '/api/offer/list-datatable',
        order: [[0, 'desc']],
        language: {
            paginate: {
                previous: '&nbsp;',
                next: '&nbsp;'
            }
        },
        initComplete: function(settings, json) {
            feather.replace();
        }
    });

    window.onCreate = () => {
        location.href = '/offer/create'
    }

    window.onEdit = (ID) => {

        location.href = 'purchase-offer/edit/' + ID;
        
    }

    window.onDelete = (ID) => {
        AxiosAskGET('/api/purchase-offer/delete/' + ID, (r) => {

            let response = r.data;

            if(response.status == true){
                ToastAlert(response.message, 'success');
                DataTable.ajax.reload();
            }

        }, window.JSLang['Are you sure?'], window.JSLang['This will deleted and you will not be able to revert this!'], window.JSLang['Yes, do it!'])
    }

    DataTable.on('click', '.issend', function (e) {

        const elem = document.getElementById(e.target.id);

        let ID = elem.getAttribute('data-id');
        let is_send = (elem.checked == true) ? 1 : 0;

        AxiosPOST('/api/offer/set-is-send/' + ID, {is_send: is_send}, (r) => {

            let response = r.data;

            if(response.status == true){
                ToastAlert(response.message, 'success');
                DataTable.ajax.reload();
            }

        })

    });

});
</script>
@endsection