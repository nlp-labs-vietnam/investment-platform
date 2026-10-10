<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
     data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="TranslateModalLabel"><i
                        class="fa-light fa-file-import"></i> @lang("Import Keywords")</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.language.import.json') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group ">
                        <label for="inputName" class="form-label form-title">@lang('Import Keywords')</label>
                        <div class="tom-select-custom">
                            <input type="hidden" name="my_lang" value="{{ $language->id }}">
                            <select class="js-select form-select" autocomplete="off" name="lang_id"
                                    data-hs-tom-select-options='{
                                          "placeholder": "Import Languages",
                                          "hideSearch": true
                                        }'>
                                @foreach($languages as $data)
                                    @if($data->id != $language->id)
                                        <option value="{{ $data->id }}">{{ __($data->name) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <small
                            class="text-info">@lang("If you import keywords from another language, Your present `$language->name` all keywords will remove.")</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-white"
                            data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-sm btn-primary import-language">@lang('Import')</button>
                </div>
            </form>
        </div>
    </div>
</div>
