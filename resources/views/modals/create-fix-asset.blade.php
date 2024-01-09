<!-- Add Fix Asset -->
<div class="modal modal-slide-in fade" id="createModal" aria-hidden="true">
    <div class="modal-dialog sidebar-lg">
        <div class="modal-content p-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title">
                    <span class="align-middle">{{ __('locale.Create Fix Asset') }}</span>
                </h5>
            </div>
            <div class="modal-body flex-grow-1">
                <form id="createForm" class="mt-2" method="POST">
                    <div class="mb-1">
                        <label for="create-fix-asset-item-id" class="form-label">{{ __('locale.Purchase Invoice Item') }}</label>
                        <select class="form-control" name="create-fix-asset-item-id" id="create-fix-asset-item-id"></select>
                    </div>
                    <div class="mb-1">
                        <label for="create-fix-asset-valid-date" class="form-label">{{ __('locale.Valid Date') }}</label>
                        <input type="date" class="form-control" id="create-fix-asset-valid-date" name="create-fix-asset-valid-date"/>
                    </div>
                    <div class="mb-1">
                        <label for="create-fix-asset-asset-comment" class="form-label">{{ __('locale.Comment') }}</label>
                        <textarea class="form-control" name="create-fix-asset-asset-comment" id="create-fix-asset-asset-comment"></textarea>
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
<!-- Add Fix Asset -->
