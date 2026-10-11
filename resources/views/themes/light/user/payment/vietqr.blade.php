@extends(template().'layouts.user')
@section('title')
    @lang('Pay with VietQR')
@endsection

@section('content')
<section class="transaction-history mt-4">
    <div class="container">

        <div class="row">
            <div class="col ms-2">
                <div class="header-text-full">
                    <h3 class="dashboard_breadcurmb_heading">@lang('Pay with VietQR')</h3>
                </div>
            </div>
        </div>

        <div class="row justify-content-center g-4">

            {{-- QR Code card --}}
            <div class="col-md-5">
                <div class="card secbg br-4 text-center">
                    <div class="card-body bg-light py-4">
                        <h5 class="mb-1">@lang('Scan to Pay')</h5>
                        <p class="text-muted small mb-3">
                            @lang('Use your banking app to scan the QR code below')
                        </p>

                        <div class="mx-auto mb-3" style="max-width:240px;">
                            <img id="vietqr-img"
                                 src="{{ $data->qr_url }}"
                                 alt="VietQR Code"
                                 class="img-fluid rounded border"
                                 style="width:240px;height:240px;object-fit:contain;">
                        </div>

                        <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                            <i class="fa fa-clock text--base"></i>
                            <span class="fw-semibold">@lang('Expires in'): </span>
                            <span id="vietqr-countdown" class="text--base fw-bold">{{ gmdate('i:s', $data->timeout) }}</span>
                        </div>

                        <div id="vietqr-status" class="mb-2" style="display:none;">
                            <span class="badge bg-success fs-6 px-3 py-2">
                                <i class="fa fa-check-circle me-1"></i> @lang('Payment Confirmed!')
                            </span>
                        </div>

                        <p class="text-muted small mb-0">@lang('Do not close this page until payment is confirmed.')</p>
                    </div>
                </div>
            </div>

            {{-- Payment details card --}}
            <div class="col-md-5">
                <div class="card secbg br-4">
                    <div class="card-body bg-light py-4">
                        <h5 class="mb-3">@lang('Transfer Details')</h5>

                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">@lang('Bank')</span>
                                <span class="fw-semibold">{{ strtoupper($data->bank_id) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">@lang('Account Number')</span>
                                <span class="fw-bold text--base font-monospace">{{ $data->account_no }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">@lang('Account Name')</span>
                                <span class="fw-semibold text-uppercase">{{ $data->account_name }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">@lang('Amount')</span>
                                <span class="fw-bold text--base fs-5">{{ number_format($data->amount) }} {{ $data->currency }}</span>
                            </li>
                            <li class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span class="text-muted">@lang('Transfer Content')</span>
                                    <div class="text-end">
                                        <div class="input-group input-group-sm">
                                            <input id="vietqr-content"
                                                   type="text"
                                                   class="form-control font-monospace fw-bold text-end"
                                                   value="{{ $data->content }}"
                                                   readonly>
                                            <button class="btn btn-outline-secondary"
                                                    type="button"
                                                    onclick="vietqrCopy()"
                                                    title="@lang('Copy')">
                                                <i class="fa fa-copy"></i>
                                            </button>
                                        </div>
                                        <small class="text-danger">@lang('Enter exactly as shown')</small>
                                    </div>
                                </div>
                            </li>
                        </ul>

                        <div class="alert alert-warning small mb-0 py-2">
                            <i class="fa fa-exclamation-triangle me-1"></i>
                            @lang('Enter the exact transfer content above. Incorrect content will not be automatically confirmed.')
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /row --}}
    </div>
</section>
@endsection

@push('script')
<script>
(function () {
    let remaining = {{ (int) $data->timeout }};
    const countdownEl = document.getElementById('vietqr-countdown');

    const tick = setInterval(function () {
        remaining--;
        if (remaining <= 0) {
            clearInterval(tick);
            clearInterval(poller);
            countdownEl.textContent = '00:00';
            countdownEl.classList.add('text-danger');
            window.location.href = '{{ route("failed") }}';
            return;
        }
        const m = String(Math.floor(remaining / 60)).padStart(2, '0');
        const s = String(remaining % 60).padStart(2, '0');
        countdownEl.textContent = m + ':' + s;
    }, 1000);

    const checkUrl = '{{ route("vietqr.check", $deposit->trx_id) }}';
    const successUrl = '{{ route("success") }}';

    const poller = setInterval(function () {
        fetch(checkUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(function (res) {
                if (res.status === 1) {
                    clearInterval(tick);
                    clearInterval(poller);
                    document.getElementById('vietqr-status').style.display = 'block';
                    setTimeout(() => window.location.href = successUrl, 1500);
                }
            })
            .catch(() => {});
    }, 5000);

    window.vietqrCopy = function () {
        const el = document.getElementById('vietqr-content');
        navigator.clipboard.writeText(el.value).then(function () {
            if (typeof Notiflix !== 'undefined') {
                Notiflix.Notify.success('Đã sao chép: ' + el.value);
            }
        });
    };
})();
</script>
@endpush
