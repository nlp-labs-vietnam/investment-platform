<div class="modal fade" id="automatic_translate_modal" tabindex="-1" role="dialog" data-bs-backdrop="static"
     aria-labelledby="TranslateModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="TranslateModalLabel">
                    <i class="bi bi-check2-square"></i> @lang("Confirmation")
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.all.keyword.translate', $language->short_name) }}" method="post">
                @csrf
                <div class="modal-body">
                    <span>@lang('Would you like to automatically translate all the keywords?')</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary ">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
