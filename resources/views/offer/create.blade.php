@extends('layouts/contentLayoutMaster')

@section('title', __('locale.Create New Offer'))

@section('vendor-style')
<link rel="stylesheet" href="{{asset('vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
@endsection
@section('page-style')
<link rel="stylesheet" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" href="{{asset('css/base/pages/app-invoice.css')}}">
@endsection

@section('content')

<section class="invoice-add-wrapper">
    <div class="row invoice-add">
        <div class="col-xl-9 col-md-8 col-12">
            <div class="card invoice-preview-card">
                <!-- Offer: Header -->
                <div class="card-body invoice-padding pb-0">
                    <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                        <div>
                            <h3 class="text-primary invoice-logo">{{ __('locale.Create New Offer') }}</h3>
                            <hr>
                            <p class="card-text mb-25" id="info-company-name">{{ session('company_name') }}</p>
                            <p class="card-text mb-25" id="info-company-code">{{ session('company_code') }}</p>
                            <p class="card-text mb-25" id="info-company-address">{{ session('company_address') }}</p>
                            <p class="card-text mb-0" id="info-company-email">{{ session('company_email') }}</p>
                        </div>
                        <div class="invoice-number-date mt-md-0 mt-2">
                            <div class="d-flex align-items-center justify-content-md-end mb-1">
                                <h5 class="invoice-title">{{ __('locale.Offer') }}</h5>
                                <div class="input-group input-group-merge">
                                    <input type="text" class="form-control" id="create-offer-no" placeholder="" disabled/>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-md-end mb-1">
                                <input type="text" class="form-control" id="create-offer-title" placeholder="{{ __('locale.Title') }}"/>
                            </div>
                            <div class="d-flex align-items-center mb-1">
                                <span class="title">{{ __('locale.Date') }}:</span>
                                <input type="text" class="form-control date-picker" id="create-offer-date"/>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="invoice-spacing" />
                <div class="card-body invoice-padding pt-0">
                    <div class="row row-bill-to invoice-spacing">
                        <div class="col-xl-12 mb-lg-1 col-bill-to ps-0">
                            <h6 class="invoice-to-title">{{ __('locale.Client') }}:</h6>
                            <div class="invoice-customer">
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createClientModal">{{ __('locale.Create New Client') }}</button>
                                <hr>
                                <select class="form-select" id="create-offer-client-id">
                                    <option></option>
                                </select>
                                <div id="info-selected-client" class="mt-1"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- / Offer: Header -->

                <!-- Offer: Items -->
                <div class="card-body invoice-padding invoice-product-details">
                    <div class="create-offer-item-list">
                        <div data-repeater-list="items">
                            <div class="repeater-wrapper" data-repeater-item>
                                <div class="row">
                                    <div class="col-12 d-flex product-details-border position-relative pe-0">
                                        <div class="row w-100 pe-lg-0 pe-1 py-2">
                                            <div class="col-lg-5 col-12 mb-lg-0 mb-2 mt-lg-0 mt-2">       
                                                <p class="card-text col-title mb-md-2 mb-0">{{ __('locale.Title') }} / {{ __('locale.Description') }}</p>
                                                <input type="text" class="form-control" placeholder="{{ __('locale.Item Title') }}" name="item_title">
                                                <textarea class="form-control mt-2" rows="1" placeholder="{{ __('locale.Item Description') }}" name="item_description"></textarea>
                                            </div>
                                            <div class="col-lg-3 col-12 my-lg-0 my-2">
                                                <p class="card-text col-title mb-md-2 mb-0">{{ __('locale.Cost') }} / {{ __('locale.Tax') }}</p>
                                                <div class="input-group input-group-merge mb-2">
                                                    <span class="input-group-text">€</span>
                                                    <input type="text" class="form-control item_cost ps-1" placeholder="{{ __('locale.Cost') }}" name="item_cost">
                                                </div>
                                                <div class="mt-2">
                                                    <select class="form-select item_tax bf-tax" placeholder="{{ __('locale.Tax') }}" name="item_tax">
                                                        <option value="0">{{ __('locale.0%') }}</option>
                                                        <option value="5">5%</option>
                                                        <option value="9">9%</option>
                                                        <option value="21" selected>21%</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-12 my-lg-0 my-2">
                                                <p class="card-text col-title mb-md-2 mb-0">{{ __('locale.Quantity') }}</p>
                                                <input type="text" class="form-control item_quantity bf-quantity" value="1" placeholder="1" name="item_quantity"/>
                                            </div>
                                            <div class="col-lg-2 col-12 mt-lg-0 mt-2">
                                                <p class="card-text col-title mb-md-50 mb-0">{{ __('locale.Price') }}</p>
                                                <p class="card-text mb-0 item_info_price" id="item_info_price">0</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column align-items-center justify-content-between border-start invoice-product-actions py-50 px-25">
                                            <i data-feather="x" class="cursor-pointer font-medium-3" data-repeater-delete></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-12 px-0">
                                <button type="button" class="btn btn-primary btn-sm btn-add-new" data-repeater-create>
                                    <i data-feather="plus" class="me-25"></i>
                                    <span class="align-middle">{{ __('locale.Add Item') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- / Offer: Items -->

                <!-- Offer: Footer -->
                <div class="card-body invoice-padding">
                    <div class="row invoice-sales-total-wrapper">
                        <div class="col-md-6 order-md-1 order-2 mt-md-0 mt-3">
                        </div>
                        <div class="col-md-6 d-flex justify-content-end order-md-2 order-1">
                            <div class="invoice-total-wrapper">
                                <div class="invoice-total-item">
                                    <p class="invoice-total-title">{{ __('locale.Subtotal') }}:</p>
                                    <p class="invoice-total-amount" id="info-summary-subtotal"></p>
                                </div>
                                <div class="invoice-total-item">
                                    <p class="invoice-total-title">{{ __('locale.Tax') }}:</p>
                                    <p class="invoice-total-amount" id="info-summary-tax"></p>
                                </div>
                                <hr class="my-50" />
                                <div class="invoice-total-item">
                                    <p class="invoice-total-title">{{ __('locale.Total') }}:</p>
                                    <p class="invoice-total-amount" id="info-summary-total"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="invoice-spacing mt-0" />
                <div class="card-body invoice-padding py-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-2">
                                <label for="note" class="form-label fw-bold">{{ __('locale.Note') }}:</label>
                                <textarea class="form-control" rows="2" id="note" placeholder="Offer Note" id="create-offer-note"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- / Offer: Footer -->
            </div>
        </div>

        <!-- Offer: Sidebar -->
        <div class="col-xl-3 col-md-4 col-12">
            <div class="card">
                <div class="card-body">    
                    <a href="/offers" class="btn btn-outline-info w-100 mb-75"><i class="bi bi-arrow-left"></i> {{ __('locale.Back') }}</a>    
                    <button type="button" class="btn btn-success w-100" onclick="onCreateOffer()"><i class="bi bi-check"></i> {{ __('locale.Create') }}</button>
                </div>
            </div>
            <div class="mt-2">
                <p class="mb-50">{{ __('pdf.Created by') }}</p>
                <select class="form-select" id="create-offer-user-id">
                    <option value=""></option>
                </select>
            </div>
        </div>
        <!-- / Offer: Sidebar -->
    </div>
</section>

@include('modals.create-new-client')
@endsection

@section('vendor-script')
<script src="{{asset('vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
@endsection

@section('page-script')
<script type="text/javascript">
$(async function() {
    'use strict';

    let logged_user_id = "{{ request()->user()->id }}";

    FillSelect2('#create-offer-client-id', '/api/client/list-all', false, []); 
    FillSelect2('#create-offer-user-id', '/api/user/list-all', false, [logged_user_id]); 
    FillSelect2('#create-client-type', '/api/system-definition/list-filter', false, [], {type: 'client_type'}); 
    
    setTimeout(() => {
        update_serie(1, $('#create-offer-date').val());
    }, 500);

    /* Client Changed */
    $('#create-offer-client-id').change(() => {

        let currentSelected = $('#create-offer-client-id').val();

        if(currentSelected != null){

            AxiosGET('/api/client/detail/' + currentSelected, (r) => {
                let res = r.data;
                let innerHtml = '';

                if(res.status == true){

                    $('#info-selected-client').html('');

                    innerHtml += `
                        <p>
                            ${res.data.name}<br>
                            <strong>Address: </strong>${res.data.address}<br>
                            <strong>VAT Number: </strong>${res.data.vat_number}<br>
                        </p>
                        `;

                    $('#info-selected-client').html(innerHtml);

                }
            });

        }

    });
    /* Client Changed */

    /* Offer Item List */
    var OfferItemList = $('.create-offer-item-list');

    OfferItemList.on('submit', function(e) {
        e.preventDefault();
    });
    
    window.itemCalculator = () => {
        CalculateAll(OfferItemList.repeaterVal());
    }

    var $repeater = OfferItemList.repeater({
        show: function() {

            CurrencyMask('.item_cost');

            $(this).slideDown();

            $(this).find('.bf-tax').val(21);
            $(this).find('.bf-quantity').val(1);

            $('.create-offer-item-list input').keyup(itemCalculator);
            $('.create-offer-item-list select').change(itemCalculator);
        },
        hide: function(e) {
            $(this).slideUp();
            $(this).remove();
        },
        ready: function (){

            CurrencyMask('.item_cost');

            $('.create-offer-item-list input').keyup(itemCalculator);
            $('.create-offer-item-list select').change(itemCalculator);
        },
    });
    /* Offer Item List */

    /* Create Offer */
    var date = new Date();
    var datepicker = $('.date-picker');
    var btnAddNewItem = $('.btn-add-new');

    if (datepicker.length) {
        datepicker.each(function() {
            $(this).flatpickr({
                defaultDate: date
            });
        });
    }
    
    if (btnAddNewItem.length) {
        btnAddNewItem.on('click', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    }

    window.onCreateOffer = async () => {

        let title = $('#create-offer-title').val();
        let client_id = $('#create-offer-client-id').val();
        let user_id = $('#create-offer-user-id').val();
        let offer_no = $('#create-offer-no').val();
        let type = $('#create-offer-type').val();
        let date = $('#create-offer-date').val();
        let note = $('#create-offer-note').val();

        if(!client_id){
            ToastAlert(window.JSLang['Client must be selected!'], 'warning');
            return;
        }

        if(!user_id){
            ToastAlert(window.JSLang['User must be selected!'], 'warning');
            return;
        }

        let check_offer_serie = await AxiosPOST('/api/offer/check-offer-serie', {offer_no});

        if(check_offer_serie.data !== null){
            ToastAlert(['Offer number changed.'], 'info');
            update_serie(1, $('#create-offer-date').val());
            offer_no = $('#create-offer-no').val();
        }
        
        try{
            let repeaterVal = $repeater.repeaterVal();
            let items = repeaterVal['items'];
            let project_id = null;
            
            if(items.length === 0){
                throw new Error('You have to at least 1 valid item to this offer!');
            }
            
            AxiosPOST('/api/offer/create', {
                title,
                client_id,
                user_id,
                offer_no,
                type,
                date,
                note,
                items,
                project_id
            }, (r) => {
                let res = r.data;

                if(res.status){
                    location.href = '/offers';
                }
            })

        }catch (error){
            ToastAlert(window.JSLang['You have to at least 1 valid item to this offer!'], 'warning');
            return;
        }
    }
    /* Create Offer */

    /* Create Client Modal */
    var createClientForm = $('#createClientForm');
    var createClientModal = new bootstrap.Modal(document.getElementById('createClientModal'));

    createClientForm.validate({
        rules: {
            'create-client-name': {
                required: true,
            },
            'create-client-code': {
                required: true,
            },
            'create-client-address': {
                required: true
            },
            'create-client-phone': {
                required: true
            },
            'create-client-email': {
                required: true,
                email: true
            },
            'create-client-vat-number': {
                required: true
            }
        }
    });

    createClientForm.submit(() => {
        event.preventDefault();

        if(!createClientForm.valid()){
            return;
        }

        let formData = {};

        $('#create-client-form input').each(function() {
            formData[this.id.replace('create-client-', '')] = $(this).val();
        });

        formData['contract'] = $('#create-client-contract').attr('data-file-id');

        // let title = $('#create-client-title').val();
        // let type = $('#create-client-type').val();
        // let name = $('#create-client-name').val();
        // let code = $('#create-client-code').val();
        // let address = $('#create-client-address').val();
        // let phone = $('#create-client-phone').val();
        // let email = $('#create-client-email').val();
        // let vat_number = $('#create-client-vat-number').val();
        // let comment = $('#create-client-comment').val();
        // let contract = $('#create-client-contract').attr('data-file-id');
        
        AxiosPOST('/api/client/create', {formData}, (r) => {

            let response = r.data;

            if(response.status == true){

                createClientModal.hide();
                ToastAlert(response.message, 'success');
                FillSelect2('#create-offer-client-id', '/api/client/list-all', false, []); 

            }

        });
    });   
    /* Create Client Modal */    

    /* Serie Generation */
    function update_serie(serie, date){
        AxiosPOST('/api/offer/new-offer-no', {serie, date}, (r) => {
            let response = r.data;
            if(response.status == true){
                $('#create-offer-no').val(response.data);
            }
        });
    }

    /* Serie Generation */ 

});
</script>
@endsection