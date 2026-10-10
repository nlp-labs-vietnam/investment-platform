@extends('admin.layouts.app')
@section('page_title', __('Create New Rank'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item">@lang('Manage Rank')</li>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang("Create Rank")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Create New Rank")</h1>
                </div>
            </div>
        </div>


        <form action="{{ route("admin.rank.store") }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row d-flex justify-content-center">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="d-grid gap-3 gap-lg-5">
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="card-title h4">@lang("Add Rank Details")</h2>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label for="rank_name" class="form-label">@lang("Rank Name")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control"
                                                           name="rank_name" value="{{ old("rank_name") }}"
                                                           placeholder="@lang("Rank Name")" autocomplete="off">
                                                </div>
                                                @error("rank_name")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>


                                            <div class="col-md-6 mb-3">
                                                <label for="rank_level" class="form-label">@lang("Rank Level")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control"
                                                           name="rank_level" value="{{ old("rank_level") }}"
                                                           placeholder="@lang('Rank Level')"
                                                           autocomplete="off">
                                                </div>
                                                @error("rank_level")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="min_invest" class="form-label">@lang("Min Invest")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control"
                                                           onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                           name="min_invest" value="{{ old("min_invest") }}"
                                                           placeholder="@lang('Minimum Invest Amount')"
                                                           autocomplete="off">
                                                </div>
                                                @error("min_invest")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="min_deposit" class="form-label">@lang("Min Deposit")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control"
                                                           onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                           name="min_deposit" value="{{ old("min_deposit") }}"
                                                           placeholder="@lang('Minimum Deposit Amount')"
                                                           autocomplete="off">
                                                </div>
                                                @error("min_invest")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="min_earning" class="form-label">@lang("Min Earning")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control"
                                                           onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                           name="min_earning" value="{{ old("min_earning") }}"
                                                           placeholder="@lang('Minimum Earning')"
                                                           autocomplete="off">
                                                </div>
                                                @error("min_earning")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="bonus" class="form-label">@lang("Bonus")</label>
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control"
                                                           onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                           name="bonus" value="{{ old("bonus") }}"
                                                           placeholder="@lang('Bonus')"
                                                           autocomplete="off">
                                                </div>
                                                @error("bonus")
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label for="" class="form-label">@lang("Description")</label>
                                                <textarea class="form-control" name="description" id="summernote"
                                                          rows="30" cols="50"
                                                          style="height: 1000px !important;">{{ old("description") }}</textarea>
                                                @error("details")
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-4">
                    <div class="d-grid gap-3 gap-lg-5 res-order">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang("Publish")</h2>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-start gap-2">
                                    <button type="submit" class="btn btn-primary">@lang("Save")</button>
                                    <a href="{{ route('admin.ranks') }}"
                                       class="btn btn-info">@lang("Cancel")</a>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang("Rank Icon")</h2>
                            </div>
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="col-md-12 mb-3 mb-md-0">
                                        <label class="form-check form-check-dashed" for="ImageUploader">
                                            <img id="rankIcon"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ asset("assets/admin/img/oc-browse-file.svg") }}"
                                                 alt="@lang("Rank Icon")"
                                                 data-hs-theme-appearance="default">

                                            <img id="rankIcon"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ asset("assets/admin/img/oc-browse-file-light.svg") }}"
                                                 alt="@lang("Rank Icon")"
                                                 data-hs-theme-appearance="dark">
                                            <span class="d-block">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach form-check-input"
                                                   name="rank_icon" id="ImageUploader"
                                                   data-hs-file-attach-options='{
                                                  "textTarget": "#rankIcon",
                                                  "mode": "image",
                                                  "targetAttr": "src",
                                                  "allowTypes": [".png", ".jpeg", ".jpg", ".svg"]
                                           }'>
                                        </label>
                                        @error("rank_icon")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang("Status")</h2>
                            </div>
                            <div class="card-body">
                                <label class="row form-check form-switch mt-5" for="breadcrumbSwitch">
                                    <span class="col-8 col-sm-9 ms-0">
                                      <span class="text-dark">@lang("Status")
                                          <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                             data-bs-placement="top"
                                             aria-label="@lang("Enable or Disable Property Status")"
                                             data-bs-original-title="@lang("Enable or Disable Rank Status")"></i></span>
                                    </span>
                                    <span class="col-4 col-sm-3 text-end">
                                        <input type="hidden" name="status" value="0">
                                        <input type="checkbox" class="form-check-input" name="status"
                                               id="status" value="1"
                                               {{ old('status') == "1" ? 'checked' : '' }} checked>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-bs5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/image-uploader.css') }}"/>
    <link href="{{ asset('assets/global/css/flatpickr.min.css') }}" rel="stylesheet">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
    <script src="{{ asset('assets/global/js/image-uploader.js') }}"></script>
    <script src="{{ asset('assets/global/js/flatpickr.js') }}"></script>
@endpush

@push('script')
    <script>
        $(document).ready(function () {

            $(".flatpickr").flatpickr({
                wrap: true,
                enableTime: true,
                altInput: true,
                dateFormat: "Y-m-d H:i",
            });


            HSCore.components.HSTomSelect.init('.js-select');
            new HSFileAttach('.js-file-attach')
            $(document).on('input', ".change_name_input", function (e) {
                let inputValue = $(this).val();
                let final_value = inputValue.toLowerCase().replace(/\s+/g, '-');
                $('.set-slug').val(final_value);
            });

            $('#summernote').summernote({
                placeholder: 'Describe how to make a manual payment.',
                height: 160,
                callbacks: {
                    onBlurCodeview: function () {
                        let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable').val();
                        $(this).val(codeviewHtml);
                    }
                },
            });

        });

    </script>
@endpush








