<!-- Edit Rental -->
<div class="modal modal-slide-in fade" id="editRentalModal" aria-hidden="true">
    <div class="modal-dialog sidebar-lg">
        <div class="modal-content p-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title">
                    <span class="align-middle">{{ __('locale.Edit Rental to Project') }}</span>
                </h5>
            </div>
            <div class="modal-body flex-grow-1">
                <form id="editRentalForm" class="mt-2" method="POST">
                    <input type="hidden" id="edit-rental-id">
                    <div class="mb-1">
                        <label for="edit-rental-quantity" class="form-label">{{ __('locale.Quantity') }}</label>
                        <input type="number" min="1" class="form-control" id="edit-rental-quantity" name="edit-rental-quantity"/>
                    </div>
                    <div class="mb-1">
                        <label for="edit-rental-price" class="form-label">{{ __('locale.Price Per Unit') }}</label>
                        <div class="input-group input-group-merge mb-2">
                            <span class="input-group-text">€</span>
                            <input type="text" class="form-control" id="edit-rental-price" name="edit-rental-price" value="0.00">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mb-1 d-grid w-100">{{ __('locale.Edit') }}</button>
                </form>
                <button type="button" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="modal">
                    {{ __('locale.Cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- /Edit Rental -->