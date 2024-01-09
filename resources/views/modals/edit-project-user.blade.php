<!-- Add User -->
<div class="modal modal-slide-in fade" id="editUserModal" aria-hidden="true">
    <div class="modal-dialog sidebar-lg">
        <div class="modal-content p-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title">
                    <span class="align-middle">{{ __('locale.Edit Project User') }}</span>
                </h5>
            </div>
            <div class="modal-body flex-grow-1">
                <form id="editUserForm" class="mt-2" method="POST">
                    <input type="hidden" id="edit-project-user-id">
                    <div class="mb-1">
                        <label for="edit-user-id" class="form-label">{{ __('locale.User') }}</label>
                        <select class="form-control" name="edit-user-id" id="edit-user-id" disabled></select>
                    </div>
                    <div class="mb-1">
                        <label for="edit-user-percentage" class="form-label">{{ __('locale.Percentage') }}</label>
                        <div class="input-group input-group-merge mb-2">
                            <span class="input-group-text">%</span>
                            <input type="number" max="100" min="1" class="form-control" id="edit-user-percentage" name="edit-user-percentage">
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
<!-- /Add User -->