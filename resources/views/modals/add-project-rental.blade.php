<!-- Add Rental -->
<div class="modal modal-slide-in fade" id="addRentalModal" aria-hidden="true">
    <div class="modal-dialog sidebar-lg">
        <div class="modal-content p-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title">
                    <span class="align-middle">{{ __('locale.Add Rental to Project') }}</span>
                </h5>
            </div>
            <div class="modal-body flex-grow-1">
                <form id="addRentalForm" class="mt-2" method="POST">
                    <div class="mb-1">
                        <label for="add-rental-id" class="form-label">{{ __('locale.Rental') }}</label>
                        <select class="form-control" name="add-rental-id" id="add-rental-id"></select>
                    </div>
                    <div class="mb-1">
                        <label for="add-rental-quantity" class="form-label">{{ __('locale.Quantity') }}</label>
                        <input type="number" min="1" class="form-control" id="add-rental-quantity" name="add-rental-quantity"/>
                    </div>
                    <div class="mb-1">
                        <label for="add-rental-price" class="form-label">{{ __('locale.Price Per Unit') }}</label>
                        <div class="input-group input-group-merge mb-2">
                            <span class="input-group-text">€</span>
                            <input type="text" class="form-control" id="add-rental-price" name="add-rental-price" value="0.00">
                        </div>
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
<!-- /Add Rental -->