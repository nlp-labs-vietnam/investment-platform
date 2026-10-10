<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addLabel"
     data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addLabel"><i class="fa-light fa-square-plus"></i> @lang("Add Keyword")
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.add.language.keyword', $language->short_name) }}" method="post"
                  class="add-keyword-form">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label" for="exampleFormControlInput1">@lang('Key')</label>
                        <input type="text" class="form-control input-field" id="key" name="key"
                               placeholder="@lang('Enter key')"
                               value="{{ old('key') }}" autocomplete="off">
                        <span class="text-danger value-error"></span>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="exampleFormControlInput1">@lang('Value')</label>
                        <input type="text" class="form-control input-field" id="value" name="value"
                               placeholder="@lang('Enter value')" value="{{ old('value') }}" autocomplete="off">
                        <span class="text-danger value-error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"> @lang('Save Changes')</button>
                </div>
            </form>
        </div>
    </div>
</div>
