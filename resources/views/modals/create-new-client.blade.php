<!-- Add New Client Sidebar -->
<div class="modal modal-slide-in fade" id="createClientModal" aria-hidden="true">
    <div class="modal-dialog sidebar-lg">
        <div class="modal-content p-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title">
                    <span class="align-middle">{{ __('locale.Create New Client') }}</span>
                </h5>
            </div>
            <div class="modal-body flex-grow-1">
                <form id="createClientForm" class="mt-2 form-with-upload" method="POST">
                    <div class="mb-1">
                        <label class="form-label" for="create-client-type">{{ __('locale.Client Type') }}</label>
                        <select class="select2 form-select" id="create-client-type" required>
                            <option></option>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label for="create-client-name" class="form-label">{{ __('locale.Name') }}</label>
                        <input type="text" class="form-control" id="create-client-name" name="create-client-name"/>
                    </div>
                    <div class="mb-1">
                        <label for="create-client-code" class="form-label">{{ __('locale.Code') }}</label>
                        <input type="text" class="form-control" id="create-client-code" name="create-client-code"/>
                    </div>
                    <div class="mb-1">
                        <label for="create-client-vat-number" class="form-label">{{ __('locale.VAT Number') }}</label>
                        <input type="text" class="form-control" id="create-client-vat-number" name="create-client-vat-number"/>
                    </div>
                    <div class="mb-1">
                        <label for="create-client-address" class="form-label">{{ __('locale.Address') }}</label>
                        <input type="text" class="form-control" id="create-client-address" name="create-client-address"/>
                    </div>
                    <div class="mb-1">
                        <label for="create-client-phone" class="form-label">{{ __('locale.Phone') }}</label>
                        <input type="text" class="form-control" id="create-client-phone" name="create-client-phone"/>
                    </div>
                    <div class="mb-1">
                        <label for="create-client-email" class="form-label">{{ __('locale.Email') }}</label>
                        <input type="text" class="form-control" id="create-client-email" name="create-client-email"/>
                    </div>
                    <div class="mb-1">
                        <label for="create-client-comment" class="form-label">{{ __('locale.Comment') }}</label>
                        <textarea class="form-control" name="create-client-comment" id="create-client-comment"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mb-1 d-grid w-100">{{ __('locale.Create') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Add New Client Sidebar -->