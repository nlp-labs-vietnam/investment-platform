<!-- Modal -->
<div class="modal fade" id="userDeleteMultipleModal" tabindex="-1" role="dialog" aria-labelledby="userDeleteMultipleModalLabel" data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="userDeleteMultipleModalLabel"><i
                        class="fa-light fa-square-check"></i> @lang('Confirmation')</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
                @csrf
                <div class="modal-body">
                    @lang('Do you want to delete all selected user data?')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary delete-multiple">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal -->
