<!-- Modal -->
<div class="modal fade" id="blockProfileModal" tabindex="-1" role="dialog" aria-labelledby="blockProfileModalLabel" data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="blockProfileModalLabel">
                    <i class="bi bi-check2-square"></i> @lang('Confirmation')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="" class="blockProfileAction" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @lang('Do you want to block this user profile?')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn-primary">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal -->

