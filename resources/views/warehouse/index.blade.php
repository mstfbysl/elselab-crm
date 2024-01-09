@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Warehouse Items'))

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
@endsection

@section('content')
<!-- Warehouse Items -->
<section id="ajax-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">{{ __('locale.Warehouse Items') }}</h4>
                </div>
                {{-- 
                <div class="card-body">
                    <div class="mt-2 col-3">
                        <label for="warehouse-type" class="form-label">{{ __('locale.Type') }}</label>
                        <select class="form-control" name="warehouse-type" id="warehouse-type"></select>
                    </div>
                </div> --}}
                <div class="card-body">
                    <div class="card-datatable">
                        <table id="DataTable" class="datatables-ajax table">
                            <thead>
                                <tr>
                                    <th>{{ __('locale.ID') }}</th>
                                    <th>{{ __('locale.Client') }}</th>
                                    <th>{{ __('locale.Invoice') }}</th>
                                    <th>{{ __('locale.Title') }}</th>
                                    <th>{{ __('locale.Quantity') }}</th>
                                    <th>{{ __('locale.Cost pcs') }}</th>
                                    <th>{{ __('locale.Total Cost') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Warehouse Items -->
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

    window.getCurrentType = () => {
        return $('#warehouse-type').val();
    } 

    var DataTable = $('#DataTable').DataTable({
        processing: true,
        dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        ajax: {
            url: '/api/warehouse/list-datatable-filter',
            method: 'POST',
            "data": function(d) {
                d.type = $('#warehouse-type').val();
            },
        },
        order: [[0, 'desc']],
        language: {
            paginate: {
                previous: '&nbsp;',
                next: '&nbsp;'
            }
        }
    });

    // FillSelect2('#warehouse-type', '/api/system-definition/list-filter', false, [], {type: 'purchase_invoice_item_type'}, () => {
    //     DataTable.ajax.reload();
    // }); 

});
</script>
@endsection