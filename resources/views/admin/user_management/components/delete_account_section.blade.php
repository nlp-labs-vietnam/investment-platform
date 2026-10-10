<div id="deleteAccountSection" class="card">
    <div class="card-header">
        <h2 class="card-title h4">@lang("Delete your account")</h2>
    </div>
    <div class="card-body">
        <p class="card-text">@lang("When you delete your account, you lose access to Front account
            services, and we permanently delete your personal data. You can cancel the deletion for
            14 days.")</p>

        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input delete-checkbox" type="checkbox" id="deleteAccountCheckbox">
                <label class="form-check-label" for="deleteAccountCheckbox">
                    @lang("Confirm that I want to delete my account.")
                </label>
            </div>
        </div>

        <div class="d-flex justify-content-start gap-3">
            <button type="submit" class="btn btn-danger delete-btn" data-bs-toggle="modal" data-bs-target="#deleteModal">@lang("Delete")
            </button>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="deleteModalLabel"><i
                        class="fa-light fa-square-check"></i> @lang('Confirmation')</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.user.delete', $user->id) }}" method="post">
                @csrf
                @method('delete')
                <div class="modal-body">
                    @lang('Are you sure delete this user ?')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal -->
