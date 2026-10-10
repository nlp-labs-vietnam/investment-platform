<!-- Receipt Invoice Modal -->
<div class="modal fade" id="accountInvoiceReceiptModal" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form role="form" method="POST" class="actionRoute" action="" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="modal-body">
                    <div class="text-center mb-5">
                        <h3 class="mb-1">@lang('Withdraw Information')</h3>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <small class="text-cap text-secondary mb-0">@lang('Sender Name:')</small>
                            <h5 class="text-dark sender_name"></h5>
                            <input type="hidden" name="user_id" class="user-id">
                        </div>

                        <div class="col-md-4 mb-3 mb-md-0">
                            <small class="text-cap text-secondary mb-0">@lang('Transaction Id:')</small>
                            <span class="text-dark transaction_id"></span>
                        </div>

                        <div class="col-md-4">
                            <small class="text-cap text-secondary mb-0">@lang('Payment method:')</small>
                            <div class="d-flex align-items-center">
                                <img class="avatar avatar-xss me-2 gateway_modal_image" src="" alt="Image Description">
                                <span class="text-dark method"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <small class="text-cap text-secondary mb-0">@lang('Amount paid:')</small>
                            <h5 class="text-dark amount"></h5>
                        </div>

                        <div class="col-md-4 mb-3 mb-md-0">
                            <small class="text-cap text-secondary mb-0">@lang('Date paid:')</small>
                            <span class="text-dark date"></span>
                        </div>

                        <div class="col-md-4">
                            <small class="text-cap text-secondary mb-0">@lang('Status:')</small>
                            <div class="d-flex align-items-center">
                                <span id="status" class="status"></span>
                            </div>
                        </div>


                    </div>

                    <small class="text-cap mb-2">@lang('Summary')</small>
                    <ul class="list-group mb-4 payment_information">
                    </ul>
                    <div class="get-feedback">

                    </div>
                    <div class="modal-footer-text mt-2">
                        <div class="d-flex justify-content-end gap-3 status-buttons">
                            <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                            <input type="hidden" class="action_id" name="id">
                            <button type="submit" class="btn btn-success btn-sm" name="status"
                                    value="2">@lang('Approved')</button>
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
        $(document).on("click", '.edit_btn', function (e) {
            let id = $(this).data('id');
            let amount = $(this).data('amount');
            let status = $(this).data('status');
            let method = $(this).data('method');
            let date = $(this).data('datepaid');
            let senderName = $(this).data('sendername');
            let transactionID = $(this).data('transactionid');
            let userId = $(this).data('userid');
            let status_color = $(this).data('status_color');
            let status_text = $(this).data('status_text');

            $('.user-id').val(userId);
            $('.sender_name').html(senderName);
            $('.transaction_id').html(transactionID);
            $('.amount').html(amount);
            $('.method').html(method);
            $('.date').html(date);

            $("#status").attr('class', status_color);
            $("#status").text(status_text);

            $(".status-buttons button[name='status']").toggle(status != 2 && status != 3);

            let feedback = $(this).data('feedback');
            let gatewayImage = $(this).data('gatewayimage');
            $('.gateway_modal_image').attr('src', gatewayImage)

            $(".action_id").val(id);
            $(".actionRoute").attr('action', $(this).data('action'));

            let details = Object.entries($(this).data('info'));

            let list = details.map(([key, value]) => {

                let field_name = value.field_name;
                let field_value = value.field_value;
                let field_name_text = field_name.replace(/_/g, ' ');

                if (value.type == 'file') {
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
                feedbackField = `<div class="mb-3">
                                        <small class="text-cap mb-2">@lang('Send You Feedback')</small>
                                        <textarea name="feedback" class="form-control" placeholder="Feedback" rows="3">{{old('feedback')}}</textarea>
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

        });

    </script>
@endpush
