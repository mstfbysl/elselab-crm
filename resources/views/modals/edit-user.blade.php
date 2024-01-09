<!-- Edit User -->
<div class="modal modal-slide-in fade" id="editModal" aria-hidden="true">
    <div class="modal-dialog sidebar-lg">
        <div class="modal-content p-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title">
                    <span class="align-middle">{{ __('locale.Create New User') }}</span>
                </h5>
            </div>
            <div class="modal-body flex-grow-1">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="edit-tab" data-bs-toggle="tab" href="#edit" aria-controls="edit" role="tab" aria-selected="true">
                            <i data-feather="edit"></i> {{ __('locale.Edit User') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="documents-tab" data-bs-toggle="tab" href="#documents" aria-controls="documents" role="tab" aria-selected="false">
                            <i data-feather="file-plus"></i> {{ __('locale.Documents') }}
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="edit" aria-labelledby="edit-tab" role="tabpanel">
                        <form id="editForm" class="mt-2 form-with-upload" method="POST">
                            <input type="hidden" name="edit-id" id="edit-id">
                            <div class="mb-1">
                                <label class="form-label" for="edit-user-role-id">{{ __('locale.User Role') }}</label>
                                <select class="select2 form-select" id="edit-user-role-id" required>
                                    <option></option>
                                </select>
                            </div>
                            <div class="mb-1">
                                <label for="edit-fullname" class="form-label">{{ __('locale.Fullname') }}</label>
                                <input type="text" class="form-control" id="edit-fullname" name="edit-fullname"/>
                            </div>
                            <div class="mb-1">
                                <label for="edit-email" class="form-label">{{ __('locale.E-mail') }}</label>
                                <input type="text" class="form-control" id="edit-email" name="edit-email"/>
                            </div>
                            <div class="mb-1">
                                <label for="edit-password" class="form-label">{{ __('locale.Password') }}</label>
                                <input type="text" class="form-control" id="edit-password" name="edit-password"/>
                            </div>
                            <div class="mb-1">
                                <label for="edit-profit-share" class="form-label">{{ __('locale.Profit Share') }}</label>
                                <div class="input-group input-group-merge mb-2">
                                    <span class="input-group-text">%</span>
                                    <input type="number" max="100" min="0" class="form-control" id="edit-profit-share" name="edit-profit-share">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mb-1 d-grid w-100">{{ __('locale.Save') }}</button>
                        </form>
                    </div>
                    <div class="tab-pane" id="documents" aria-labelledby="documents-tab" role="tabpanel">
                        <form id="createDocumentForm" class="mt-2" method="POST">
                            <div class="mb-1">
                                <label for="create-document-title" class="form-label">{{ __('locale.Title') }}</label>
                                <input type="text" class="form-control" id="create-document-title" name="create-document-title"/>
                            </div>
                            <div class="mb-1">
                                <label for="create-document-file" class="form-label">{{ __('locale.File') }}</label>
                                <input type="file" class="form-control fuploader" name="create-document-file" id="create-document-file" data-file-id=""/>
                            </div>
                            <button type="submit" class="btn btn-primary mb-1 d-grid w-100">{{ __('locale.Save') }}</button>
                        </form>
                        <h4>{{ __('locale.Documents') }}</h4>
                        <ul class="list-group" id="document-list"></ul>
                        <hr>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="modal">
                    {{ __('locale.Cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>
<!--/ Edit User -->
