@extends('admin.layouts.app')
@section('page_title', __('Referral Commissions'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item">@lang('Manage Commission')</li>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang("Commission List")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("All Commissions")</h1>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-12 mb-4">
                        <div class="d-grid gap-3 gap-lg-5">
                            <div class="card">
                                <div class="card-header">
                                    <h2 class="card-title h4">@lang("Commissions")</h2>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-5">
                                        <h5 class="card-title">@lang('Deposit Commissions')</h5>
                                        <div class="table-responsive">
                                            <table
                                                class="categories-show-table table table-hover table-striped table-bordered"
                                                id="zero_config">
                                                <thead class="thead-dark">
                                                <tr>
                                                    <th scope="col">@lang('Level')</th>
                                                    <th scope="col">@lang('Bonus')</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($referrals->where('commission_type','deposit') as $item)
                                                    <tr>
                                                        <td data-label="Level">@lang('LEVEL')# {{ $item->level }}</td>
                                                        <td data-label="Bonus">
                                                            {{ $item->percent }} %
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="2" class="text-center p-4">
                                                            <img class="dataTables-image mb-3"
                                                                 src="{{ asset('assets/admin/img/oc-error.svg') }}"
                                                                 alt="Image Description"
                                                                 data-hs-theme-appearance="default">
                                                            <img class="dataTables-image mb-3"
                                                                 src="{{ asset('assets/admin/img/oc-error-light.svg') }}"
                                                                 alt="Image Description"
                                                                 data-hs-theme-appearance="dark">
                                                            <p class="mb-0">@lang('No data to show')</p>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row mb-5">
                                        <h5 class="card-title">@lang('Investment Commission')</h5>
                                        <div class="table-responsive">
                                            <table
                                                class="categories-show-table table table-hover table-striped table-bordered"
                                                id="zero_config">
                                                <thead class="thead-dark">
                                                <tr>
                                                    <th scope="col">@lang('Level')</th>
                                                    <th scope="col">@lang('Bonus')</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($referrals->where('commission_type','invest') as $item)
                                                    <tr>
                                                        <td data-label="Level">@lang('LEVEL')# {{ $item->level }}</td>
                                                        <td data-label="Bonus">
                                                            {{ $item->percent }} %
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="2" class="text-center p-4">
                                                            <img class="dataTables-image mb-3"
                                                                 src="{{ asset('assets/admin/img/oc-error.svg') }}"
                                                                 alt="Image Description"
                                                                 data-hs-theme-appearance="default">
                                                            <img class="dataTables-image mb-3"
                                                                 src="{{ asset('assets/admin/img/oc-error-light.svg') }}"
                                                                 alt="Image Description"
                                                                 data-hs-theme-appearance="dark">
                                                            <p class="mb-0">@lang('No data to show')</p>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <h5 class="card-title">@lang('Profit Commission')</h5>
                                        <div class="table-responsive">
                                            <table
                                                class="categories-show-table table table-hover table-striped table-bordered"
                                                id="zero_config">
                                                <thead class="thead-dark">
                                                <tr>
                                                    <th scope="col">@lang('Level')</th>
                                                    <th scope="col">@lang('Bonus')</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($referrals->where('commission_type','profit_commission') as $item)
                                                    <tr>
                                                        <td data-label="Level">@lang('LEVEL')# {{ $item->level }}</td>
                                                        <td data-label="Bonus">
                                                            {{ $item->percent }} %
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="2" class="text-center p-4">
                                                            <img class="dataTables-image mb-3"
                                                                 src="{{ asset('assets/admin/img/oc-error.svg') }}"
                                                                 alt="Image Description"
                                                                 data-hs-theme-appearance="default">
                                                            <img class="dataTables-image mb-3"
                                                                 src="{{ asset('assets/admin/img/oc-error-light.svg') }}"
                                                                 alt="Image Description"
                                                                 data-hs-theme-appearance="dark">
                                                            <p class="mb-0">@lang('No data to show')</p>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-6">
                <div class="d-grid gap-3 gap-lg-5">
                    <form method="post" action="{{route('admin.referral.commission.action')}}"
                          enctype="multipart/form-data">
                        @csrf

                        @if(adminAccessRoute(config('permissionList.Manage_Commission.Referral.permission.edit')))
                            <div class="card">
                                <div class="card-header">
                                    <h2 class="card-title h4">@lang("Commission Status")</h2>
                                </div>
                                <div class="card-body">
                                    <label class="row form-check form-switch mt-5" for="breadcrumbSwitch">
                                    <span class="col-8 col-sm-9 ms-0">
                                      <span class="text-dark d-block">@lang("Deposit Commission")</span>
                                        <span class="d-block fs-5">@lang('Enable or Disable Deposit Commission').</span>
                                    </span>

                                        <span class="col-4 col-sm-3 text-end">
                                        <input type="hidden" name="deposit_commission" value="0">
                                        <input type="checkbox" class="form-check-input" name="deposit_commission"
                                               id="deposit_commission" value="1"
                                               {{ old('deposit_commission', $basicControl->deposit_commission) == "1" ? 'checked' : '' }}>
                                    </span>
                                    </label>


                                    <label class="row form-check form-switch mt-5" for="breadcrumbSwitch">
                                    <span class="col-8 col-sm-9 ms-0">
                                      <span class="text-dark">@lang("Investment Commission")</span>
                                        <span
                                            class="d-block fs-5">@lang('Enable or Disable Investment Commission').</span>
                                    </span>

                                        <span class="col-4 col-sm-3 text-end">
                                        <input type="hidden" name="investment_commission" value="0">
                                        <input type="checkbox" class="form-check-input" name="investment_commission"
                                               id="investment_commission" value="1"
                                               {{ old('investment_commission', $basicControl->investment_commission) == "1" ? 'checked' : '' }}>
                                    </span>
                                    </label>

                                    <label class="row form-check form-switch mt-5" for="breadcrumbSwitch">
                                        <span class="col-8 col-sm-9 ms-0">
                                          <span class="text-dark">@lang("Profit Commission")</span>
                                            <span class="d-block fs-5">@lang('Enable or Disable Profit Commission').</span>
                                        </span>

                                        <span class="col-4 col-sm-3 text-end">
                                        <input type="hidden" name="profit_commission" value="0">
                                        <input type="checkbox" class="form-check-input" name="profit_commission"
                                               id="profit_commission" value="1"
                                               {{ old('profit_commission', $basicControl->profit_commission) == "1" ? 'checked' : '' }}>
                                        </span>
                                    </label>

                                    <label class="row form-check form-switch mt-5" for="breadcrumbSwitch">
                                        <span class="col-8 col-sm-9 ms-0">
                                          <span class="text-dark">@lang("Rank Commission")</span>
                                            <span class="d-block fs-5">@lang('Enable or Disable Rank Commission').</span>
                                        </span>

                                        <span class="col-4 col-sm-3 text-end">
                                        <input type="hidden" name="rank_commission" value="0">
                                        <input type="checkbox" class="form-check-input" name="rank_commission"
                                               id="rank_commission" value="1"
                                               {{ old('rank_commission', $basicControl->rank_commission) == "1" ? 'checked' : '' }}>
                                        </span>
                                    </label>


                                    <div class="col-md-12 mt-5">
                                        <button type="submit"
                                                class="btn btn-primary btn-rounded btn-block">@lang('Update')</button>
                                    </div>

                                </div>
                            </div>

                            <div class="card mt-5">
                                <div class="card-header">
                                    <h2 class="card-title h4">@lang("Set Commission")</h2>
                                </div>
                                <form action="{{ route('admin.referral.commission.store') }}" method="post"
                                      class="form-row">
                                    @csrf
                                    <div class="card-body">
                                        <div class="row formFiled justify-content-between">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">@lang('Select Bonus Type')</label>
                                                    <div class="tom-select-custom">
                                                        <select class="js-select form-select" autocomplete="off"
                                                                name="commission_type"
                                                                data-hs-tom-select-options='{
                                                      "placeholder": "Select Commission Type",
                                                      "hideSearch": true
                                                    }'>
                                                            <option value="" disabled>@lang('Select Type')</option>
                                                            <option value="deposit">@lang('Deposit Commission')</option>
                                                            <option
                                                                value="invest">@lang('Investment Commission')</option>
                                                            <option
                                                                value="profit_commission">@lang('Profit Commission')</option>
                                                        </select>
                                                    </div>
                                                    @error('commission_type')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">@lang('Set Level')</label>
                                                    <input type="number" name="level"
                                                           placeholder="@lang('Number Of Level')"
                                                           class="form-control numberOfLevel">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="opacity-0">&nbsp;@lang('Generate') </label>
                                                    <div class="d-flex justify-content-start gap-2">
                                                        <button type="button"
                                                                class="btn btn-primary makeForm">@lang("GENERATE")</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-md-12 newFormContainer">

                                        </div>

                                        <div class="col-md-12">
                                            <button type="submit"
                                                    class="btn btn-primary btn-rounded btn-block mt-3 submit-btn w-100 d-none">@lang('Submit')</button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
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

            $('.submit-btn').addClass('d-none');

            $(".makeForm").on('click', function () {

                var levelGenerate = $(this).parents('.formFiled').find('.numberOfLevel').val();
                var selectType = $('.type :selected').val();

                if (selectType == '') {
                    Notiflix.Notify.failure("{{trans('Please Select a type')}}");
                    return 0;
                }

                // $('input[name=commission_type]').val(selectType)
                var value = 1;
                var viewHtml = '';
                if (levelGenerate !== '' && levelGenerate > 0) {
                    for (var i = 0; i < parseInt(levelGenerate); i++) {
                        viewHtml += `<div class="input-group mt-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text no-right-border">LEVEL</span>
                            </div>
                            <input name="level[]" class="form-control" type="number" readonly value="${value++}" required placeholder="@lang('Level')">
                            <input name="percent[]" class="form-control" type="text" required placeholder="@lang("Level Bonus (%)")">
                            <span class="input-group-btn">
                            <button class="btn btn-danger removeForm" type="button"><i class='fa fa-trash'></i></button></span>
                            </div>`;
                    }

                    $('.newFormContainer').html(viewHtml);
                    $('.submit-btn').addClass('d-block');
                    $('.submit-btn').removeClass('d-none');

                } else {

                    $('.submit-btn').addClass('d-none');
                    $('.submit-btn').removeClass('d-block');
                    $('.newFormContainer').html(``);
                    Notiflix.Notify.failure("{{trans('Please Set number of level')}}");
                }
            });

            $(document).on('click', '.removeForm', function () {
                $(this).closest('.input-group').remove();
            });

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
