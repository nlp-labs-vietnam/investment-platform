<div id="twoStepVerificationSection" class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h4 class="mb-0">@lang('Two-step verification')</h4>
            <span
                class="badge bg-soft-{{ $user->two_fa_verify == 1 ? 'success' : 'danger' }} text-{{ $user->two_fa_verify == 1 ? 'success' : 'danger' }} ms-2">{{ $user->two_fa_verify == 1 ? 'Enable' : 'Disabled' }}</span>
        </div>

    </div>

    <div class="card-body">
        <p class="card-text">
            @lang('Your security is our top priority, and Two-Step Verification is a powerful tool to keep your account safe. We highly recommend enabling it today.')
        </p>
        <form action="{{ route('admin.user.twoFa.update', $user->id) }}" method="post">
            @csrf
            <div class="d-flex justify-content-start">
                <button type="submit" class="btn btn-{{ $user->two_fa_verify == 1 ? 'danger' : 'primary'}}"
                        name="two_fa_security"
                        value="{{ $user->two_fa_verify }}">{{ $user->two_fa_verify == 1 ? 'Disable' : 'Enable'}}</button>
            </div>
        </form>
    </div>
</div>
