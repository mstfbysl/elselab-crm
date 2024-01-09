<!-- Add Service -->
<div class="modal modal-slide-in fade" id="addServiceModal" aria-hidden="true">
    <div class="modal-dialog sidebar-lg">
        <div class="modal-content p-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title">
                    <span class="align-middle">{{ __('locale.Add Service to a Project') }}</span>
                </h5>
            </div>
            <div class="modal-body flex-grow-1">
                <form id="addServiceForm" class="mt-2" method="POST">
                    <div class="mb-1">
                        <label for="add-service-id" class="form-label">{{ __('locale.Service') }}</label>
                        <select class="form-control" name="add-service-id" id="add-service-id"></select>
                    </div>
                    <div class="mb-1">
                        <label for="add-service-quantity" class="form-label">{{ __('locale.Quantity') }}</label>
                        <input type="number" min="1" class="form-control" id="add-service-quantity" name="add-service-quantity"/>
                    </div>
                    <div class="mb-1">
                        <label for="add-service-price" class="form-label">{{ __('locale.Price Per Unit') }}</label>
                        <div class="input-group input-group-merge mb-2">
                            <span class="input-group-text">€</span>
                            <input type="text" class="form-control" id="add-service-price" name="add-service-price" value="0.00">
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
<!-- /Add Service -->