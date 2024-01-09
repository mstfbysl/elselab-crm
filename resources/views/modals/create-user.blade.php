<!-- Create User -->
<div class="modal modal-slide-in fade" id="createModal" aria-hidden="true">
    <div class="modal-dialog sidebar-lg">
        <div class="modal-content p-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title">
                    <span class="align-middle">{{ __('locale.Create New User') }}</span>
                </h5>
            </div>
            <div class="modal-body flex-grow-1">
                <form id="createForm" class="mt-2" method="POST">
                    <div class="mb-1">
                        <label class="form-label" for="create-user-role-id">{{ __('locale.User Role') }}</label>
                        <select class="select2 form-select" id="create-user-role-id" required>
                            <option></option>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label for="create-fullname" class="form-label">{{ __('locale.Fullname') }}</label>
                        <input type="text" class="form-control" id="create-fullname" name="create-fullname"/>
                    </div>
                    <div class="mb-1">
                        <label for="create-email" class="form-label">{{ __('locale.E-mail') }}</label>
                        <input type="text" class="form-control" id="create-email" name="create-email"/>
                    </div>
                    <div class="mb-1">
                        <label for="create-password" class="form-label">{{ __('locale.Password') }}</label>
                        <input type="text" class="form-control" id="create-password" name="create-password"/>
                    </div>
                    <div class="mb-1">
                        <label for="create-profit-share" class="form-label">{{ __('locale.Profit Share') }}</label>
                        <div class="input-group input-group-merge mb-2">
                            <span class="input-group-text">%</span>
                            <input type="number" max="100" min="0" class="form-control" id="create-profit-share" name="create-profit-share">
                        </div>
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
<!--/ Create User -->
