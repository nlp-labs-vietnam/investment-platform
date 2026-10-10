<!-- Card -->
<div id="emailSection" class="card">
    <div class="card-header">
        <h4 class="card-title">@lang('Email')</h4>
    </div>
    <div class="card-body">
        <p>@lang('Your current email address is') <span class="fw-semibold">{{ $user->email }}</span></p>
        <form action="{{ route('admin.user.email.update', $user->id) }}" method="post">
            @csrf
            <div class="row mb-4">
                <label for="newEmailLabel" class="col-sm-3 col-form-label form-label">
                    @lang('New email address')
                </label>
                <div class="col-sm-9">
                    <input type="email" class="form-control" name="new_email" id="newEmailLabel"
                           placeholder="@lang('Enter new email address')"
                           aria-label="Enter new email address" autocomplete="off">
                    @error('new_email')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="d-flex justify-content-start">
                <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
            </div>
        </form>
    </div>
</div>

