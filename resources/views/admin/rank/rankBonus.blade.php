@extends('admin.layouts.app')
@section('page_title', __('Rank Bonus'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">@lang('Dashboard')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Manage Rank')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Rank Bonus')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Rank Bonus')</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 mb-3 mb-lg-5">
                <div class="card h-100">
                    <div class="card-header card-header-content-between">
                        <h4 class="card-header-title">@lang('Rank Bonus Activity')</h4>
                    </div>
                    <form action="{{ route('admin.rank.bonus.update') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <ul class="list-group list-group-flush list-group-no-gutters">
                                <li class="list-group-item">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <h5 class="mb-0">@lang('Is Rank Bonus')</h5>
                                                    <span class="d-block fs-6 text-body">
                                                        @lang('Unlocking the Rewards of Ranking Up.')
                                                    </span>
                                                </div>
                                                <div class="col-auto">
                                                    <label class="row form-check form-switch mb-3" for="is_rank_bonus">
                                                    <span class="col-4 col-sm-3 text-end">
                                                        <input type='hidden' value='0' name='is_rank_bonus'>
                                                        <input
                                                            class="form-check-input @error('is_rank_bonus') is-invalid @enderror"
                                                            type="checkbox"
                                                            name="is_rank_bonus"
                                                            id="is_rank_bonus"
                                                            value="1" {{($basicControl->is_rank_bonus == 1) ? 'checked' : ''}}>
                                                        </span>
                                                        @error('is_rank_bonus')
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                        @enderror
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                @if(adminAccessRoute(config('permissionList.Manage_Rank.Rank_Bonus.permission.edit')))
                                    <div class="d-flex justify-content-start mt-3">
                                        <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
                                    </div>
                                @endif
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush
@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 500
            })
        })
    </script>
@endpush
