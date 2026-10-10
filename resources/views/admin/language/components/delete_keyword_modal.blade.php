<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
     data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>@lang('Do you want to delete this') <span class="keyword"></span> @lang("keyword?")</p>
            </div>
            <form action="{{ route('admin.delete.language.keyword',[$language->short_name, urlencode($key)]) }}"
                  method="post">
                @csrf
                @method('delete')
                <input type="hidden" name="key">
                <input type="hidden" name="value">
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-white" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-sm btn-primary ">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
