const { default: axios } = require('axios');
require('inputmask');
import 'bootstrap-icons/font/bootstrap-icons.css';

window.base_path = 'https://dashboard.elselab.io';

(function (window) {
    'use strict';

    var default_axios_config = {
        headers: {
            "Content-Type": "application/json"
        }
    };

    var upload_axios_config = {
        headers: {
          "Content-Type": "multipart/form-data",
        }
    };

    window.language_string = {
        "Client must be selected!": "Client must be selected!",
        "User must be selected!": "User must be selected!",
        "You have to at least 1 valid item to this invoice!": "You have to at least 1 valid item to this invoice!",
    };

    window.AreYouSure = (Callback, title = 'Are you sure?', text = '', confirm = '') => {
        return Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: confirm,
            customClass: {
              confirmButton: 'btn btn-primary',
              cancelButton: 'btn btn-outline-danger ms-1'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                Callback();
            }
        })

    }

    window.CurrencyMask = (selector) => {
        Inputmask("currency", {rightAlign: false}).mask(selector);
    }

    window.GetCurrencyUnmask = (selector) => {
        return document.querySelector(selector).inputmask.unmaskedvalue();
    }

    window.ToastAlert = (text = '', type = 'info') => {
        let default_config = {
            closeButton: true,
            tapToDismiss: false,
            positionClass: "toast-top-right"
        };

        switch (type) {
            case 'info':
                return toastr.info(text, 'Info!', default_config);
                break;

            case 'success':
                return toastr.success(text, 'Success!', default_config);
                break;

            case 'warning':
                return toastr.warning(text, 'Warning!', default_config);
                break;

            case 'error':
                return toastr.error(text, 'Error!', default_config);
                break;

            default:
                return toastr.info(text, 'Info!', default_config);
                break;
        }
    }

    // Add a request interceptor
    axios.interceptors.request.use(function (config) {
        // Do something before request is sent
        return config;
    }, function (error) {
        // Do something with request error
        return Promise.reject(error);
    });

    // Add a response interceptor
    axios.interceptors.response.use(function (response) {
        // Any status code that lie within the range of 2xx cause this function to trigger
        // Do something with response data

        return response;
    }, function (error) {
        // Any status codes that falls outside the range of 2xx cause this function to trigger
        // Do something with response error
        if(error.response.data.message !== undefined){
            ToastAlert(error.response.data.message, 'error');
        }
        return Promise.reject(error);
    });

    window.AxiosGET = (path, succeed, failed) => {
        return axios.get(base_path + path).then(succeed).catch(failed);
    }

    window.AxiosAskGET = (path, succeed, title, text, confirm) => {
        return AreYouSure(() => {
            return axios.get(base_path + path).then(succeed);
        }, title, text, confirm);
    }

    window.AxiosPOST = (path, body, succeed) => {
        return axios.post(base_path + path, body, default_axios_config).then(succeed);
    }

    window.AxiosUPLOAD = (path, form_data, succeed) => {
        return axios.post(base_path + path, form_data, upload_axios_config).then(succeed);
    }

    window.FillSelect2 = async (selectId, url, multiple = false, selectedValues = [], requestBody = null, onChangeEvent = null) => {

        let selectObj = $(selectId);

        selectObj.html('');
        selectObj.wrap('<div class="position-relative"></div>');

        if (requestBody == null)
        {
            let selectData = await axios.get(url);

            selectObj.select2({
                data: selectData.data.data,
                multiple: multiple,
                placeholder: 'Select option',
                // minimumResultsForSearch: -1,
                dropdownAutoWidth: true,
                width: '100%',
                dropdownParent: selectObj.parent()
            });
        }
        else
        {
            let selectData = await axios.post(url, requestBody);

            selectObj.select2({
                data: selectData.data.data,
                multiple: multiple,
                placeholder: 'Select option',
                // minimumResultsForSearch: -1,
                dropdownAutoWidth: true,
                width: '100%',
                dropdownParent: selectObj.parent()
            });
        }

        if(selectedValues.length > 0){
            selectObj.val(selectedValues);
            selectObj.trigger('change');
        }else{
            selectObj.val(0);
            selectObj.trigger('change');
        }

        if(onChangeEvent != null){
            selectObj.on('change', onChangeEvent);
        }

        return selectObj;

    }

    window.FillSelect2Manual = async (selectId, data, multiple = false, selectedValues = []) => {

        let selectObj = $(selectId);
        let selectData = data;

        selectObj.wrap('<div class="position-relative"></div>');
        selectObj.select2({
            data: data,
            multiple: multiple,
            placeholder: 'Select option',
            minimumResultsForSearch: -1,
            dropdownAutoWidth: true,
            width: '100%',
            dropdownParent: selectObj.parent()
        });

        if(selectedValues.length > 0){
            selectObj.val(selectedValues);
            selectObj.trigger('change');
        }

    }

    window.ResetForms = (FormId = null) => {
        if(FormId == null)
        {
            $('input').val('');
            $('checkbox').removeAttr('checked');
            $('select').val(-1);
        }
        else
        {
            $(FormId + ' input').val('');
            $(FormId + ' checkbox').removeAttr('checked');
            $(FormId + ' select').val(-1).trigger('change');
        }
    }

    window.CalculateItem = (c, q, t) => {

        let cost = parseFloat(c);
        let tax = parseInt(t);
        let quantity = parseInt(q);

        let item_sub_total = cost * quantity;
        let item_tax_total = item_sub_total / 100 * tax;
        let item_total = item_sub_total + item_tax_total;

        return [
            item_sub_total,
            item_tax_total,
            item_total
        ];

    }

    window.CalculateAll = (allItems) => {

        let subTotal = 0;
        let taxTotal = 0;
        let total = 0;

        allItems.items.forEach((element, index) => {

            let info_price = $('.item_info_price').eq(index);

            if(element.item_cost == '' || element.item_quantity == '')
            {
                let cost = 0;
                subTotal += 0;
                taxTotal += 0;
                total += 0;

                info_price.html(cost.toFixed(2));

                return;
            }
            else
            {
                let cost_without = element.item_cost.replace(',', '');
                let item = window.CalculateItem(cost_without, element.item_quantity, element.item_tax);

                let cost = item[0] + item[1];;
                subTotal += item[0];
                taxTotal += item[1];
                total += item[0] + item[1];

                info_price.html(cost.toFixed(2));
            }

        })

        $('#create-invoice-total').val(total);

        $('#edit-invoice-total').val(total);

        $('#info-summary-subtotal').html(subTotal.toFixed(2));
        $('#info-summary-tax').html(taxTotal.toFixed(2));
        $('#info-summary-total').html(total.toFixed(2));

    }

    window.upload_form_files = async (name, folder) => {
        let form_data = new FormData();

        form_data.append("upload_file", $(name).prop('files')[0]);
        form_data.append("folder", folder);

        await AxiosUPLOAD('/api/system-file/upload', form_data, (r) => {
            let response = r.data;

            if(response.status == true){
                return $(name).attr('data-file-id', response.data.id);
            }
        });
    };

    /*
    $('.fuploader').change(async function() {
        let form_data = new FormData();
        form_data.append("upload_file", $(this).prop('files')[0]);

        await AxiosUPLOAD('/api/system-file/upload', form_data, (r) => {
            let response = r.data;

            if(response.status == true){
                $(this).attr('data-file-id', response.data.id);
            }
        });
    })
    */
    /*
    NOTE:
    ------
    PLACE HERE YOUR OWN JAVASCRIPT CODE IF NEEDED
    WE WILL RELEASE FUTURE UPDATES SO IN ORDER TO NOT OVERWRITE YOUR JAVASCRIPT CODE PLEASE CONSIDER WRITING YOUR SCRIPT HERE.  */

})(window);
