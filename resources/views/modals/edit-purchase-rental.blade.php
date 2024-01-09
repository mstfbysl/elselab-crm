<!--/ Edit Purchase Invoice Rental -->
<div class="modal modal-slide-in fade" id="editRentalModal" aria-hidden="true">
    <div class="modal-dialog sidebar-lg">
        <div class="modal-content p-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title">
                    <span class="align-middle">{{ __('locale.Edit Rental') }}</span>
                </h5>
            </div>
            <div class="modal-body flex-grow-1">
                <div class="progress-wrapper">
                    <div id="example-caption">{{ __('locale.Remaining Total of Invoice') }}: <span class="edit-invoice-remaining"></span></div>
                    <div class="progress progress-bar-primary">
                        <div class="progress-bar edit-invoice-progressbar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="" aria-describedby="example-caption"></div>
                    </div>
                </div>
                <form id="editRentalForm" class="mt-2" method="POST">
                    <div class="mb-1">
                        <label for="edit-rental-id" class="form-label">{{ __('locale.Rental') }}</label>
                        <select class="form-control" name="edit-rental-id" id="edit-rental-id"></select>
                    </div>
                    <div class="mb-1">
                        <label for="edit-rental-title" class="form-label">{{ __('locale.Title') }}</label>
                        <input type="text" class="form-control" id="edit-rental-title" name="edit-rental-title"/>
                    </div>
                    <div class="mb-1">
                        <label for="edit-rental-quantity" class="form-label">{{ __('locale.Quantity') }}</label>
                        <input type="number" min="1" value="1" class="form-control" id="edit-rental-quantity" name="edit-rental-quantity"/>
                    </div>
                    <div class="mb-1">
                        <label for="edit-rental-cost" class="form-label">{{ __('locale.Cost') }}</label>
                        <div class="input-group input-group-merge mb-2">
                            <span class="input-group-text">€</span>
                            <input type="text" class="form-control" id="edit-rental-cost" name="edit-rental-cost">
                        </div>
                    </div>
                    <div class="mb-1">
                        <label for="edit-rental-serial" class="form-label">{{ __('locale.Serial') }}</label>
                        <input type="text" class="form-control" id="edit-rental-serial" name="edit-rental-serial"/>
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
<!--/ Edit Purchase Invoice Rental -->
