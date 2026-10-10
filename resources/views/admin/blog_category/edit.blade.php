@extends('admin.layouts.app')
@section('page_title', __('Blog Category Setting'))
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
                            <li class="breadcrumb-item active" aria-current="page">@lang('Blog Category Setting')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Blog Category')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Blog Category')</h1>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="col-lg-8">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card pb-3">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title m-0">@lang('Edit Blog Category')</h4>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{ route('admin.blog-category.update', $blogCategory->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-md-12">
                                        <label for="nameLabel" class="form-label">@lang('Category Name')</label>
                                        <input type="text" class="form-control  @error('name') is-invalid @enderror"
                                               name="name" id="nameLabel" placeholder="Name" aria-label="Name"
                                               autocomplete="off"
                                               value="{{ old('name', $blogCategory->name) }}">
                                        @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit"
                                            class="btn btn-primary submit_btn">@lang('Save changes')</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection








