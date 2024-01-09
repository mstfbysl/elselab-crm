<!-- Create Purchase Invoice Item -->
<div class="modal modal-slide-in fade" id="createItemModal" aria-hidden="true">
    <div class="modal-dialog sidebar-lg">
        <div class="modal-content p-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title">
                    <span class="align-middle">{{ __('locale.Create New Item') }}</span>
                </h5>
            </div>
            <div class="modal-body flex-grow-1">
                <div class="progress-wrapper">
                    <div id="example-caption">{{ __('locale.Remaining Total of Invoice') }}: <span class="edit-invoice-remaining"></span></div>
                    <div class="progress progress-bar-primary">
                        <div class="progress-bar edit-invoice-progressbar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="" aria-describedby="example-caption"></div>
                    </div>
                </div>
                <form id="createItemForm" class="mt-2" method="POST">
                    <div class="mb-1">
                        <label for="create-item-type" class="form-label">{{ __('locale.Type') }}</label>
                        <select class="form-control" name="create-item-type" id="create-item-type"></select>
                    </div>
                    <div class="mb-1">
                        <label for="create-item-title" class="form-label">{{ __('locale.Title') }}</label>
                        <input type="text" class="form-control" id="create-item-title" name="create-item-title"/>
                    </div>
                    <div class="mb-1">
                        <label for="create-item-quantity" class="form-label">{{ __('locale.Quantity') }}</label>
                        <input type="number" min="1" value="1" class="form-control" id="create-item-quantity" name="create-item-quantity"/>
                    </div>
                    <div class="mb-1">
                        <label for="create-item-cost" class="form-label">{{ __('locale.Cost') }}</label>
                        <div class="input-group input-group-merge mb-2">
                            <span class="input-group-text">€</span>
                            <input type="text" class="form-control" id="create-item-cost" name="create-item-cost">
                        </div>
                    </div>
                    <div class="mb-1">
                        <label for="create-item-serial" class="form-label">{{ __('locale.Serial') }}</label>
                        <input type="text" class="form-control" id="create-item-serial" name="create-item-serial"/>
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
<!--/ Create Purchase Invoice Item -->
