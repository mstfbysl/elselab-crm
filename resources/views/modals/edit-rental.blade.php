<!-- Edit Rental -->
<div class="modal modal-slide-in fade" id="editModal" aria-hidden="true">
    <div class="modal-dialog sidebar-lg">
        <div class="modal-content p-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title">
                    <span class="align-middle">{{ __('locale.Edit Rental') }}</span>
                </h5>
            </div>
            <div class="modal-body flex-grow-1">
                <form id="editForm" class="mt-2" method="POST">
                    <input type="hidden" name="edit-id" id="edit-id">
                    <div class="mb-1">
                        <label for="edit-title" class="form-label">{{ __('locale.Title') }}</label>
                        <input type="text" class="form-control" id="edit-title" name="edit-title"/>
                    </div>
                    <div class="mb-1">
                        <label for="edit-price" class="form-label">{{ __('locale.Rent price') }}</label>
                        <div class="input-group input-group-merge mb-2">
                            <span class="input-group-text">€</span>
                            <input type="text" class="form-control" id="edit-price" name="edit-price">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mb-1 d-grid w-100">{{ __('locale.Save') }}</button>
                </form>
                <button type="button" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="modal">
                    {{ __('locale.Cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>
<!--/ Edit Rental -->