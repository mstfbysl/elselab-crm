<!-- Create Status -->
<div class="modal modal-slide-in fade" id="createModal" aria-hidden="true">
    <div class="modal-dialog sidebar-lg">
        <div class="modal-content p-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title">
                    <span class="align-middle">{{ __('locale.Create New Status') }}</span>
                </h5>
            </div>
            <div class="modal-body flex-grow-1">
                <form id="createForm" class="mt-2" method="POST">
                    <div class="mb-1">
                        <label for="create-title" class="form-label">{{ __('locale.Title') }}</label>
                        <input type="text" class="form-control" id="create-title" name="create-title" />
                    </div>
                    <div class="mb-1">
                        <label for="create-color" class="form-label">{{ __('locale.Color') }}</label>
                        <input type="color" class="form-control" id="create-color" name="create-color" />
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
<!--/ Create Status -->