<!-- Create Purchase Invoice -->
<div class="modal modal-slide-in fade" id="createModal" aria-hidden="true">
    <div class="modal-dialog sidebar-lg">
        <div class="modal-content p-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title">
                    <span class="align-middle">{{ __('locale.Create New Purchase Invoice') }}</span>
                </h5>
            </div>
            <div class="modal-body flex-grow-1">
                <form id="createForm" class="mt-2 form-with-upload" method="POST">
                    <div class="mb-1">
                        <label class="form-label" for="create-client-id">{{ __('locale.Client') }}</label>
                        <select class="select2 form-select" id="create-client-id" required>
                            <option></option>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label for="create-title" class="form-label">{{ __('locale.Title') }}</label>
                        <input type="text" class="form-control" id="create-title" name="create-title"/>
                    </div>
                    <div class="mb-1">
                        <label for="create-serie" class="form-label">{{ __('locale.Serie') }}</label>
                        <input type="text" class="form-control" id="create-serie" name="create-serie"/>
                    </div>
                    <div class="mb-1">
                        <label for="create-date" class="form-label">{{ __('locale.Date') }}</label>
                        <input type="date" class="form-control" id="create-date" name="create-date"/>
                    </div>
                    <div class="mb-1">
                        <label for="create-total" class="form-label">{{ __('locale.Total without VAT') }}</label>
                        <div class="input-group input-group-merge mb-2">
                            <span class="input-group-text">€</span>
                            <input type="text" class="form-control" id="create-total" name="create-total">
                        </div>
                    </div>
                    <div class="mb-1">
                        <label for="create-total-with-vat" class="form-label">{{ __('locale.Total with VAT') }}</label>
                        <div class="input-group input-group-merge mb-2">
                            <span class="input-group-text">€</span>
                            <input type="text" class="form-control" id="create-total-with-vat" name="create-total-with-vat">
                        </div>
                    </div>
                    <div class="mb-1">
                        <div class="d-flex flex-column">
                            <label class="form-check-label mb-50" for="create-is-paid">{{ __('locale.Paid') }}</label>
                            <div class="form-check form-switch form-check-success">
                                <input type="checkbox" class="form-check-input" id="create-is-paid" />
                                <label class="form-check-label" for="create-is-paid">
                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                    <span class="switch-icon-right"><i class="bi bi-x"></i></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-1">
                        <div class="d-flex flex-column">
                            <label class="form-check-label mb-50" for="create-is-accountant">{{ __('locale.Accountant') }}</label>
                            <div class="form-check form-switch form-check-success">
                                <input type="checkbox" class="form-check-input" id="create-is-accountant" />
                                <label class="form-check-label" for="create-is-accountant">
                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                    <span class="switch-icon-right"><i class="bi bi-x"></i></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-1">
                        <label for="create-document" class="form-label">{{ __('locale.File') }}</label>
                        <input type="file" class="form-control fuploader" name="create-document" id="create-document" data-file-id=""/>
                    </div>
                    <button type="submit" class="btn btn-primary mb-1 d-grid w-100">{{ __('locale.Create') }}</button>
                </form>
                <button type="button" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="modal">
                    {{ __('locale.Cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>
<!--/ Create Purchase Invoice -->
