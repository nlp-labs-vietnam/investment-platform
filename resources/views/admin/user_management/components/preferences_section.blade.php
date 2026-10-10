<div id="preferencesSection" class="card">
    <div class="card-header">
        <h2 class="card-title h4">@lang('Preferences')</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.user.preferences.update', $user->id) }}" method="post"
              enctype="multipart/form-data">
            @csrf
            <div class="row mb-4">
                <label for="languageLabel"
                       class="col-sm-3 col-form-label form-label">
                    @lang('Language')
                    <i class="bi bi-info-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top"
                       aria-label="Select the language for email template and others services."
                       data-bs-original-title="Select the language for email template and others services."></i>
                </label>
                <div class="col-sm-9">
                    <div class="tom-select-custom">
                        <select class="js-select form-select" id="languageLabel" name="language_id"
                                data-hs-tom-select-options='{
                                  "searchInDropdown": false
                                }'>
                            @forelse($languages as $lang)
                                <option value="{{ $lang->id }}" {{ $user->language_id == $lang->id ? 'selected' : '' }}
                                data-option-template='<span class="d-flex align-items-center"><span>{{ $lang->name }}</span></span>'>
                                    {{ $lang->name }}
                                </option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <label for="timeZoneLabel" class="col-sm-3 col-form-label form-label">
                    @lang('Time zone')
                </label>
                <div class="col-sm-9">
                    <div class="tom-select-custom">
                        <select
                            class="js-select form-select @error('time_zone') is-invalid @enderror"
                            id="timeZoneLabel" name="time_zone">
                            @foreach(timezone_identifiers_list() as $key => $value)
                                <option
                                    value="{{$value}}" {{  (old('time_zone',$user->time_zone) == $value ? ' selected' : '') }}>{{ __($value) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('time_zone')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>



            </div>
            <label class="row form-check form-switch mb-4" for="emailVerificationSwitch">
                    <span class="col-8 col-sm-9 ms-0">
                      <span class="d-block text-dark">@lang('Email Verification')</span>
                      <span
                          class="d-block fs-5">@lang('Email verification codes add reliable security to your systems.')</span>
                    </span>
                <span class="col-4 col-sm-3 text-end">
                     <input type="hidden" name="email_verification" value="0">
                      <input type="checkbox" class="form-check-input" name="email_verification"
                             id="emailVerificationSwitch" value="1" {{ $user->email_verification == 1 ? 'checked' : '' }}>
                    </span>
            </label>

            <label class="row form-check form-switch mb-4" for="SMSVerificationSwitch">
                    <span class="col-8 col-sm-9 ms-0">
                      <span class="d-block text-dark">@lang('SMS Verification')</span>
                      <span
                          class="d-block fs-5">@lang('SMS verification codes add reliable security to your systems.')</span>
                    </span>
                <span class="col-4 col-sm-3 text-end">
                        <input type="hidden" name="sms_verification" value="0">
                        <input type="checkbox" class="form-check-input" name="sms_verification"
                               id="SMSVerificationSwitch" value="1" {{ $user->sms_verification == 1 ? 'checked' : '' }}>
                    </span>
            </label>

            <div class="d-flex justify-content-start">
                <button type="submit" class="btn btn-primary">@lang('Save Changes')</button>
            </div>
        </form>
    </div>
</div>

