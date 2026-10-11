@extends(template().'layouts.user')
@section('title')
    @lang('Pay with NLP Group (MB Bank)')
@endsection

@section('content')
<section class="transaction-history mt-4">
    <div class="container">

        <div class="row">
            <div class="col ms-2">
                <div class="header-text-full">
                    <h3 class="dashboard_breadcurmb_heading">@lang('Pay with NLP Group — MB Bank')</h3>
                </div>
            </div>
        </div>

        <div class="row justify-content-center g-4">

            {{-- ── QR Card ──────────────────────────────────────────────── --}}
            <div class="col-md-5">
                <div class="card secbg br-4 text-center">
                    <div class="card-body bg-light py-4">

                        <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                            <img src="{{ $data->bank_logo }}"
                                 alt="MB Bank"
                                 style="height:36px;object-fit:contain;"
                                 onerror="this.style.display='none'">
                            <span class="badge rounded-pill px-3 py-2"
                                  style="background:#1a6f3f;color:#fff;font-size:12px;letter-spacing:.04em;">
                                NLP GROUP
                            </span>
                            @if($data->is_sandbox)
                                <span class="badge bg-warning text-dark">SANDBOX</span>
                            @endif
                        </div>

                        <h5 class="mb-1">@lang('Scan to Pay')</h5>
                        <p class="text-muted small mb-3">
                            @lang('Open your banking app and scan the QR code')
                        </p>

                        <div class="mx-auto mb-3 position-relative" style="max-width:240px;">
                            <img id="nlpgroup-qr-img"
                                 src="{{ $data->qr_url }}"
                                 alt="NLP Group QR Code"
                                 class="img-fluid rounded border"
                                 style="width:240px;height:240px;object-fit:contain;">
                            <div id="nlpgroup-qr-overlay"
                                 class="position-absolute top-0 start-0 w-100 h-100 d-none
                                        d-flex align-items-center justify-content-center rounded"
                                 style="background:rgba(25,135,84,.85);">
                                <i class="fa fa-check-circle text-white" style="font-size:64px;"></i>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                            <i class="fa fa-clock text--base"></i>
                            <span class="fw-semibold">@lang('Expires in'):</span>
                            <span id="nlpgroup-countdown" class="text--base fw-bold fs-6">
                                {{ gmdate('i:s', $data->timeout) }}
                            </span>
                        </div>

                        <div id="nlpgroup-success" class="mb-2" style="display:none;">
                            <span class="badge bg-success fs-6 px-3 py-2">
                                <i class="fa fa-check-circle me-1"></i>
                                @lang('Payment Confirmed!')
                            </span>
                        </div>

                        <p class="text-muted small mb-0">
                            @lang('Do not close this page until payment is confirmed.')
                        </p>
                    </div>
                </div>
            </div>

            {{-- ── Transfer Details Card ──────────────────────────────────── --}}
            <div class="col-md-5">
                <div class="card secbg br-4">
                    <div class="card-body bg-light py-4">
                        <h5 class="mb-3">@lang('Transfer Details')</h5>

                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">@lang('Bank')</span>
                                <span class="fw-semibold">{{ $data->bank_name }}</span>
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
                                <span class="fw-bold text--base fs-5">
                                    {{ number_format($data->amount) }} {{ $data->currency }}
                                </span>
                            </li>
                            <li class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span class="text-muted flex-shrink-0 me-2">@lang('Transfer Content')</span>
                                    <div class="text-end">
                                        <div class="input-group input-group-sm">
                                            <input id="nlpgroup-content"
                                                   type="text"
                                                   class="form-control font-monospace fw-bold text-end"
                                                   value="{{ $data->content }}"
                                                   readonly>
                                            <button class="btn btn-outline-secondary"
                                                    type="button"
                                                    onclick="nlpgroupCopy()"
                                                    title="@lang('Copy')">
                                                <i class="fa fa-copy"></i>
                                            </button>
                                        </div>
                                        <small class="text-danger">@lang('Enter exactly as shown')</small>
                                    </div>
                                </div>
                            </li>
                        </ul>

                        <div class="alert alert-info small mb-3 py-2">
                            <i class="fa fa-shield-alt me-1"></i>
                            @lang('This payment is verified directly via MB Bank Business API by NLP Group — no third-party intermediary.')
                        </div>

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
    'use strict';

    let remaining = {{ (int) $data->timeout }};
    const countdownEl = document.getElementById('nlpgroup-countdown');

    const tick = setInterval(function () {
        if (--remaining <= 0) {
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

    const checkUrl  = '{{ route("nlpgroup.check", $deposit->trx_id) }}';
    const successUrl = '{{ route("success") }}';

    const poller = setInterval(function () {
        fetch(checkUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(function (res) {
                if (res.status === 1) {
                    clearInterval(tick);
                    clearInterval(poller);
                    document.getElementById('nlpgroup-qr-overlay').classList.remove('d-none');
                    document.getElementById('nlpgroup-qr-overlay').classList.add('d-flex');
                    document.getElementById('nlpgroup-success').style.display = 'block';
                    setTimeout(() => window.location.href = successUrl, 1800);
                }
            })
            .catch(function () {});
    }, 5000);

    window.nlpgroupCopy = function () {
        const el = document.getElementById('nlpgroup-content');
        navigator.clipboard.writeText(el.value).then(function () {
            if (typeof Notiflix !== 'undefined') {
                Notiflix.Notify.success('Đã sao chép: ' + el.value);
            }
        });
    };
})();
</script>
@endpush
