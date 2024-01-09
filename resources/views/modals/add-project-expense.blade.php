<!-- Add Expense -->
<div class="modal modal-slide-in fade" id="addExpenseModal" aria-hidden="true">
    <div class="modal-dialog sidebar-lg">
        <div class="modal-content p-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title">
                    <span class="align-middle">{{ __('locale.Add Expense to Project') }}</span>
                </h5>
            </div>
            <div class="modal-body flex-grow-1">
                <form id="addExpenseForm" class="mt-2" method="POST">
                    <div class="mb-1">
                        <label for="add-expense-item-id" class="form-label">{{ __('locale.Item') }}</label>
                        <select class="form-control" name="add-expense-item-id" id="add-expense-item-id"></select>
                    </div>
                    <div class="mb-1">
                        <label for="add-expense-quantity" class="form-label">{{ __('locale.Quantity') }}</label>
                        <input type="number" min="1" class="form-control" id="add-expense-quantity" name="add-expense-quantity"/>
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
<!-- /Add Expense -->