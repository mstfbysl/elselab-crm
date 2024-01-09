<!-- Create Project -->
<div class="modal modal-slide-in fade" id="createModal" aria-hidden="true">
    <div class="modal-dialog sidebar-lg">
        <div class="modal-content p-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title">
                    <span class="align-middle">{{ __('locale.Create New Project') }}</span>
                </h5>
            </div>
            <div class="modal-body flex-grow-1">
                <form id="createForm" class="mt-2" method="POST">
                    <div class="mb-1">
                        <label class="form-label" for="create-client-id">{{ __('locale.Client') }}</label>
                        <select class="select2 form-select" id="create-client-id" required />
                            <option></option>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label for="create-title" class="form-label">{{ __('locale.Title') }}</label>
                        <input type="text" class="form-control" id="create-title" name="create-title" required />
                    </div>
                    <div class="mb-1">
                        <label for="create-start-date" class="form-label">{{ __('locale.Start Date') }}</label>
                        <input type="date" class="form-control" id="create-start-date" name="create-start-date" required />
                    </div>
                    <div class="mb-1">
                        <label for="create-finish-date" class="form-label">{{ __('locale.Approximate Deadline') }}</label>
                        <input type="date" class="form-control" id="create-finish-date" name="create-finish-date" />
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
<!--/ Create Invoice -->
