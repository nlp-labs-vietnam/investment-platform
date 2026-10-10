<!-- Modal -->
<div class="modal fade" id="loginAsUserModal" tabindex="-1" role="dialog" aria-labelledby="loginAsUserModalLabel" data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="loginAsUserModalLabel"><i
                        class="bi bi-box-arrow-in-right"></i> @lang('Login As User')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="get" action="" class="loginAccountAction" enctype="multipart/form-data">
                <div class="modal-body">
                    @lang('Do you want to really login as user?')
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
