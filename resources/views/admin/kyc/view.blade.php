@extends('admin.layouts.app')
@section('page_title', __('KYC Verification'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">
                                    @lang('Dashboard')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('KYC')</li>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang($userKyc->kyc_type.' Information')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang($userKyc->kyc_type.' Information')</h1>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-3 mb-lg-5">
                    <div class="card-header card-header-content-between">
                        <h4 class="card-header-title">@lang("User Profile")</h4>
                        <a class="btn btn-white btn-sm"
                           href="{{ route('admin.user.view.profile', optional($userKyc->user)->id)  }}"><i
                                class="bi-eye me-1"></i> @lang("View Profile")</a>
                    </div>

                    <div class="card-body">
                        <ul class="list-unstyled list-py-2 text-dark mb-0">
                            <li class="pb-0"><span class="card-subtitle">@lang("About")</span></li>
                            <li>
                                <i class="bi-person dropdown-item-icon"></i> @lang(optional($userKyc->user)->firstname . " " . optional($userKyc->user)->lastname)
                            </li>
                            <li>
                                <i class="bi-briefcase dropdown-item-icon"></i> @lang('@'.optional($userKyc->user)->username)
                            </li>


                            <li class="pt-4 pb-0"><span class="card-subtitle">@lang("Contacts")</span></li>
                            <li><i class="bi-at dropdown-item-icon"></i> @lang(optional($userKyc->user)->email)</li>
                            <li><i class="bi-phone dropdown-item-icon"></i> @lang(optional($userKyc->user)->phone)</li>

                            @if(optional($userKyc->user)->country)
                                <li class="pt-4 pb-0"><span class="card-subtitle">@lang("Address")</span></li>
                                <li class="fs-6 text-body">
                                    <i class="bi bi-geo-alt dropdown-item-icon"></i> @lang(optional($userKyc->user)->country)
                                </li>
                            @endif
                        </ul>
                    </div>

                </div>
            </div>


            <div class="col-lg-8">
                <div class="card mb-3 mb-lg-5">
                    <div class="card-header card-header-content-between">
                        <h4 class="card-header-title">@lang($userKyc->kyc_type.' Information')</h4>
                        @if($userKyc->status == 0)
                            <span class="badge bg-soft-warning text-warning">
                                    <span class="legend-indicator bg-warning"></span>@lang("Pending")
                                </span>
                        @elseif($userKyc->status == 1)
                            <span class="badge bg-soft-success text-success">
                            <span class="legend-indicator bg-success"></span>@lang("Verified")
                            </span>
                        @elseif($userKyc->status == 2)
                            <span class="badge bg-soft-danger text-danger">
                            <span class="legend-indicator bg-danger"></span>@lang("Rejected")
                            </span>
                        @endif
                    </div>

                    @if($userKyc->kyc_info)
                        <div class="card-body">
                            <ul class="list-group list-group-flush list-group-no-gutters">
                                @foreach($userKyc->kyc_info as $key => $value)
                                    @if($value->type == "text" || $value->type === "textarea" ||  $value->type == "number" || $value->type == "date")
                                        <li class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5>@lang(snake2Title($value->field_label))</h5>
                                                <ul class="list-unstyled list-py-2 text-body">
                                                    <li>{{ $value->field_value }}</li>
                                                </ul>
                                            </div>
                                        </li>
                                    @endif
                                    @if($value->type == "file")
                                        <li class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5>@lang(snake2Title($value->field_name))</h5>
                                                <ul class="list-unstyled list-py-2 text-body">
                                                    <li>
                                                        <a href="{{ getFile($value->field_driver,$value->field_value) }}"
                                                           target="_blank">
                                                            <img
                                                                src="{{ getFile($value->field_driver,$value->field_value) }}"
                                                                class="kycInfoImg">
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                                    @if($userKyc->reason)
                                        <li class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5>@lang("Rejected Reason")</h5>
                                                <ul class="list-unstyled list-py-2 text-body">
                                                    <li>
                                                        @lang($userKyc->reason)
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    @endif

                            </ul>
                            @if($userKyc->status == 0)
                                <div class="d-flex justify-content-end gap-3 mt-2">
                                    <button type="button" class="btn btn-success approve-btn"
                                            data-value="1"
                                            data-message="@lang("Do you want to approved this kyc information?")"
                                            data-bs-toggle="modal"
                                            data-bs-target="#kycStatusChangeModal">@lang('Approved')</button>
                                    <button type="button" class="btn btn-danger rejected-btn" data-bs-toggle="modal"
                                            data-message="@lang("Do you want to rejected this kyc information?")"
                                            data-value="2"
                                            data-bs-target="#kycStatusChangeModal">@lang('Rejected')</button>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if(count((array)$userKyc->kyc_info) == 1)
                        <div class="card-body card-body-height text-center">
                            <img class="avatar avatar-xxl mb-3"
                                 src="{{ asset('assets/admin/img/oc-error.svg') }}"
                                 alt="Image Description" data-hs-theme-appearance="default"/>
                            <img class="avatar avatar-xxl mb-3"
                                 src="{{ asset('assets/admin/img/oc-error.svg') }}"
                                 alt="Image Description" data-hs-theme-appearance="dark"/>
                            <p class="card-text">@lang('No data to show')</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <div class="modal fade" id="kycStatusChangeModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
             aria-labelledby="kycStatusChangeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="statusModalLabel"><i
                                class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{route('admin.kyc.action',$userKyc->id)}}" method="post">
                        @csrf
                        <div class="modal-body">
                            <p class="message"></p>
                            <input type="hidden" name="status" id="status" value="">
                            <div class="reject_area mt-2">
                                <span class="mb-1">@lang('Rejection Reason')</span>
                                <textarea class="form-control @error('title') is-invalid @enderror" id="rejected_area" placeholder="@lang("Message")">{{ old('rejected_reason') }}</textarea>
                                @error('rejected_reason')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {

            @error('rejectedMessage')
            let kycStatusChangeModal = new bootstrap.Modal(document.getElementById("kycStatusChangeModal"), {});
            document.onreadystatechange = function () {
                kycStatusChangeModal.show();
            };
            $(".message").text("Do you want to rejected this kyc information?");
            @enderror

            $('.approve-btn, .rejected-btn').on('click', function () {
                let message = $(this).data('message');
                $(".message").text(message);
                $(".reject_area").toggle($(this).hasClass('rejected-btn'));
                $('#status').val($(this).data('value'));
            });

            $(document).on('click', '.rejected-btn', function () {
                $('#rejected_area').attr('name', 'rejected_reason');
            })

            HSCore.components.HSTomSelect.init('.js-select')
        });
    </script>
@endpush



