<!-- Add User Payment -->
<div class="modal modal-slide-in fade" id="createModal" aria-hidden="true">
    <div class="modal-dialog sidebar-lg">
        <div class="modal-content p-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title">
                    <span class="align-middle">{{ __('locale.Create User Payment') }}</span>
                </h5>
            </div>
            <div class="modal-body flex-grow-1">
                <form id="createForm" class="mt-2" method="POST">
                    <div class="mb-1">
                        <label for="create-user-payment-user-id" class="form-label">{{ __('locale.User') }}</label>
                        <select class="form-control" name="create-user-payment-user-id" id="create-user-payment-user-id"></select>
                    </div>
                    <div class="mb-1">
                        <label for="create-user-payment-type" class="form-label">{{ __('locale.Type') }}</label>
                        <select class="form-control" name="create-user-payment-type" id="create-user-payment-type"></select>
                    </div>
                    <div class="mb-1 uptotal" style="display: none">
                        <label for="create-user-payment-total" class="form-label">{{ __('locale.Total') }}</label>
                        <input class="form-control" type="text" name="create-user-payment-total" id="create-user-payment-total">
                    </div>
                    <div class="mb-1 upitem" style="display: none">
                        <label for="create-user-payment-item-id" class="form-label">{{ __('locale.Purchase Invoice Item') }}</label>
                        <select class="form-control" name="create-user-payment-item-id" id="create-user-payment-item-id"></select>
                    </div>
                    <div class="mb-1 upitem" style="display: none">
                        <label for="create-user-payment-quantity" class="form-label">{{ __('locale.Quantity') }}</label>
                        <input type="number" min="1" class="form-control" id="create-user-payment-quantity" name="create-user-payment-quantity" value="1"/>
                    </div>
                    <button type="submit" class="btn btn-primary mb-1 d-grid w-100">{{ __('locale.Add') }}</button>
                </form>
                <button type="button" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="modal">
                    {{ __('locale.Cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Add User Payment -->
