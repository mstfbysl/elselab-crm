<!-- Add Income -->
<div class="modal modal-slide-in fade" id="addIncomeModal" aria-hidden="true">
    <div class="modal-dialog sidebar-lg">
        <div class="modal-content p-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title">
                    <span class="align-middle">{{ __('locale.Add Income to Project') }}</span>
                </h5>
            </div>
            <div class="modal-body flex-grow-1">
                <form id="addIncomeForm" class="mt-2" method="POST">
                    <div class="mb-1">
                        <label for="add-income-item-id" class="form-label">{{ __('locale.Item') }}</label>
                        <select class="form-control" name="add-income-item-id" id="add-income-item-id"></select>
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
<!-- /Add Income -->