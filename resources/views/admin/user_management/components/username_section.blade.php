<!-- Card -->
<div id="usernameSection" class="card">
    <div class="card-header">
        <h4 class="card-title">@lang('Username')</h4>
    </div>
    <div class="card-body">
        <p>@lang('Your current username is') <span class="fw-semibold">{{ '@' . $user->username }}</span></p>
        <form action="{{ route('admin.user.username.update', $user->id) }}" method="post">
            @csrf
            <div class="row mb-4">
                <label for="newUserLabel" class="col-sm-3 col-form-label form-label">
                    @lang('New Username')
                </label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="username" id="newUserLabel"
                           placeholder="@lang('Enter Username')"
                           aria-label="@lang("Enter Username")" autocomplete="off">
                    @error('username')
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


