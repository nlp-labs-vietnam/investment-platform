<div class="modal fade" id="accountInvoiceReceiptModal" tabindex="-1" role="dialog" aria-hidden="true"
     data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form role="form" method="POST" class="actionRoute" action="" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="modal-body">
                    <div class="text-center mb-5">
                        <h3 class="mb-1">@lang('Payment Information')</h3>
                    </div>

                    <div class="row mb-6">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <small class="text-cap text-secondary mb-0">@lang('Amount paid:')</small>
                            <h5 class="amount"></h5>
                        </div>

                        <div class="col-md-4 mb-3 mb-md-0">
                            <small class="text-cap text-secondary mb-0">@lang('Date paid:')</small>
                            <span class="text-dark date"></span>
                        </div>

                        <div class="col-md-4">
                            <small class="text-cap text-secondary mb-0">@lang('Payment method:')</small>
                            <div class="d-flex align-items-center">
                                <img class="avatar avatar-xss me-2 gateway_modal_image" src="" alt="Image Description">
                                <span class="text-dark method"></span>
                            </div>
                        </div>
                    </div>

                    <small class="text-cap mb-2">@lang('Summary')</small>
                    <ul class="list-group mb-4 payment_information">
                    </ul>

                    <div class="get-feedback">


                    </div>



                    <div class="modal-footer-text mt-3">
                        <div class="d-flex justify-content-end gap-3 status-buttons">
                            <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                            <input type="hidden" class="action_id" name="id">
                            <button type="submit" class="btn btn-success btn-sm" name="status"
                                    value="1">@lang('Approved')</button>
                            <button type="submit" class="btn btn-danger btn-sm" name="status"
                                    value="3"> @lang('Rejected')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@push('script')
    <script>
        "use strict";
        $(document).on("click", '.edit_btn', function (e) {
            let id = $(this).data('id');
            let status = $(this).data('status');
            let feedback = $(this).data('feedback')
            $('.gateway_modal_image').attr('src', $(this).data('gatewayimage'))

            $(".status-buttons button[name='status']").toggle(status != 1);

            $(".action_id").val(id);
            $(".actionRoute").attr('action', $(this).data('action'));

            let details = Object.entries($(this).data('detailsinfo'));
            let list = details.map(([key, value]) => {

                let field_name = value.field_name;
                let field_value = value.field_value;
                let field_name_text = field_name.replace(/_/g, ' ');


                if (value.type === 'file') {
                    return `<li class="list-group-item text-dark">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-capitalize">${field_name_text}</span>
                                        <a href="${field_value}" target="_blank"><img src="${field_value}" alt="Image Description" class="rounded-1" width="100"></a>
                                    </div>
                                </li>`;
                } else {
                    return `<li class="list-group-item text-dark">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-capitalize">${field_name_text}</span>
                                        <span>${field_value}</span>
                                    </div>
                                </li>`;
                }
            })

            let feedbackField = "";
            if (feedback == '') {
                feedbackField = `
                                <div class="mb-3">
                                    <small class="text-cap mb-2">@lang('Send Your Feedback')</small>
                                    <textarea name="feedback" class="form-control feedback" placeholder="Feedback" rows="3" >{{old('feedback')}}</textarea>
                                    @error('feedback')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>`;

            } else {
                feedbackField = `<div class="mb-3">
                                    <small class="text-cap mb-2">@lang('Feedback')</small>
                                    <p>${feedback}</p>
                                 </div>`;
            }
            $('.get-feedback').html(feedbackField)
            $('.payment_information').html(list);
            $('.image').html(list);
            $('.amount').html($(this).data('amount'));
            $('.method').html($(this).data('method'));
            $('.date').html($(this).data('datepaid'));

        });
    </script>
@endpush

