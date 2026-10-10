<div class="modal fade" id="editKeywordModal" tabindex="-1" role="dialog" aria-labelledby="editKeywordModalLabel"
     data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content pulse-loader">
            <div class="modal-header">
                <h4 class="modal-title" id="editKeywordModalLabel"><i class="fa fa-edit"></i> @lang("Edit Keyword")
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" class="edit-keyword-form">
                @csrf
                @method('put')
                <div class="modal-body">
                    <div class="form-group ">
                        <label class="edit-key form-label" for="nameLabel"></label>
                        <div class="input-group">
                            <input type="text" class="form-control edit-value input-field" name="value"
                                   placeholder="Value" aria-label="Value"
                                   aria-describedby="basic-addon2">
                            <button type="button" class="input-group-text translate_btn"
                                    data-route="{{ route('admin.single.keyword.translate') }}"
                                    id="basic-addon2">
                                <i class="fa-sharp fa-light fa-language"></i>
                            </button>
                        </div>
                        <span class="text-danger value-error"></span>
                    </div>
                    <input type="hidden" name="key">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-white"
                            data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-sm btn-primary">@lang('Save Changes')</button>
                </div>
            </form>
        </div>
    </div>
</div>
