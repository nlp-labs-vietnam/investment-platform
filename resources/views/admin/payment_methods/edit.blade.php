@extends('admin.layouts.app')
@section('page_title', __('Edit Payment Method'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0);">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Payment Method')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Edit ' . $method->name)</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Edit ' . $method->name)</h1>
                </div>
            </div>
        </div>

        <div class="row payment_method">
            <div class="col-lg-12">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="card-title mt-2">@lang('Edit ' . $method->name)</h3>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{ route('admin.update.payment.methods', $method->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row mb-4">
                                    <div class="col-sm-6">
                                        <label for="nameLabel" class="form-label">@lang('Name')</label>
                                        <input type="text" class="form-control  @error('name') is-invalid @enderror"
                                               name="name" id="nameLabel"
                                               placeholder="Name" aria-label="Name" autocomplete="off"
                                               value="{{ old('name', $method->name ?? '') }}">
                                        @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    @if($method->currencies)
                                        <div class="col-sm-6">
                                            <label for="currencyLabel"
                                                   class="form-label">@lang('Supported Currency')</label>
                                            <div class="tom-select-custom tom-select-custom-with-tags">
                                                <select class="js-select form-select supported_currency"
                                                        name="receivable_currencies[][name]" autocomplete="off"
                                                        multiple
                                                        data-hs-tom-select-options='{
                                                        "placeholder": "Select Currency",
                                                      }' required>
                                                    @php
                                                        $paymentMethodsCurrency = session()->has('selectedCurrencyList')
                                                            ? session('selectedCurrencyList')
                                                            : (isset($method->supported_currency) ? $method->supported_currency : []);
                                                    @endphp
                                                    @forelse($method->currencies as $key => $currency)
                                                        @foreach($currency as $curKey => $singleCurrency)
                                                            @php
                                                                $isSelected = in_array($curKey, (array) $paymentMethodsCurrency);
                                                            @endphp
                                                            <option
                                                                value="{{ $curKey }}" {{ $isSelected ? 'selected' : '' }}>
                                                                {{ trans($curKey) }}
                                                            </option>
                                                        @endforeach
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>
                                            @error('receivable_currencies.0.name')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif
                                </div>


                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-header-title">@lang('Parameters')</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach ($method->parameters as $key => $parameter)
                                                <div class="col-sm-6 mb-4">
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                               for="{{ $key }}">@lang(snake2Title($key))</label>
                                                        <input type="text" name="{{ $key }}"
                                                               value="{{ old($key, $parameter) }}"
                                                               id="{{ $key }}"
                                                               class="form-control @error($key) is-invalid @enderror">
                                                        <div class="invalid-feedback">
                                                            @error($key) @lang($message) @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        @if($method->extra_parameters)
                                            <div class="row">
                                                @foreach($method->extra_parameters as $key => $param)
                                                    <div class="col-sm-6 mb-5">
                                                        <label class="form-label"
                                                               for="{{ $key }}">@lang(snake2Title($key))</label>
                                                        <div class="input-group input-group-merge">
                                                            <input type="text" id="extraParameters" name="{{ $key }}"
                                                                   class="form-control @error($key) is-invalid @enderror"
                                                                   value="{{ old($key, route($param, $method->code )) }}"
                                                                   readonly>
                                                            <a class="js-clipboard input-group-append input-group-text"
                                                               href="javascript:void(0);" data-bs-toggle="tooltip"
                                                               title="Copy to clipboard!"
                                                               data-hs-clipboard-options='{
                                                               "type": "tooltip",
                                                               "successText": "Copied!",
                                                               "contentTarget": "#extraParameters"
                                                             }'>
                                                                <i class="bi-clipboard"></i>
                                                            </a>
                                                            <span class="invalid-feedback">
                                                    @error($key) @lang($message) @enderror
                                                </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-12 mt-4">
                                        <label class="form-label" for="descriptionArea">@lang("Description")</label>
                                        <textarea id="descriptionArea" class="form-control" name="description" placeholder="Description"
                                                  rows="3">{{ old('description', $method->description) }}</textarea>
                                        <span class="invalid-feedback">
                                            @error('description') @lang($message) @enderror
                                        </span>
                                    </div>
                                </div>


                                <div class="row my-lg-5">
                                    <div class="col-md-3">
                                        <label class="form-check form-check-dashed" for="logoUploader">
                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2 rounded"
                                                 src="{{ getFile($method->driver, $method->image, true) }}"
                                                 alt="Image Description" data-hs-theme-appearance="default">
                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2 rounded"
                                                 src="{{ getFile($method->driver, $method->image, true) }}"
                                                 alt="Image Description" data-hs-theme-appearance="dark">
                                            <span class="d-block">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach form-check-input" name="image"
                                                   id="logoUploader" data-hs-file-attach-options='{
                                                      "textTarget": "#logoImg",
                                                      "mode": "image",
                                                      "targetAttr": "src",
                                                      "allowTypes": [".png", ".jpeg", ".jpg"]
                                                   }'>
                                        </label>
                                        <span class="invalid-feedback d-block">
                                            @error('image') @lang($message) @enderror
                                        </span>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="list-group list-group-lg list-group-flush list-group-no-gutters">
                                            <div class="list-group-item">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <span class="d-block text-dark">@lang("Status")</span>
                                                                <p class="fs-5 text-body mb-0">@lang("Enable to your gateway status")</p>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="form-check form-switch">
                                                                    <input type="hidden" name="is_active" value="0">
                                                                    <input type="checkbox" class="form-check-input"
                                                                           name="is_active"
                                                                           id="isActiveSwitch"
                                                                           value="1" {{ $method->status == 1 ? 'checked' : '' }}>
                                                                    <label class="form-check-label"
                                                                           for="isActiveSwitch"></label>
                                                                </div>
                                                                <span class="invalid-feedback">
                                                                    @error('is_active') @lang($message) @enderror
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End List Item -->

                                            <!-- List Item -->
                                            <div class="list-group-item">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <span
                                                                    class="d-block text-dark">@lang("Test Environment")</span>
                                                                <p class="fs-5 text-body mb-0">@lang("To test your payment gateway, set up a sandbox environment.")</p>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="form-check form-switch">
                                                                    <input type="hidden" name="test_environment"
                                                                           value="live">
                                                                    <input type="checkbox" class="form-check-input"
                                                                           name="test_environment"
                                                                           id="testEnvironmentSwitch"
                                                                           value="test" {{ $method->environment == 'test' ? 'checked' : '' }}>
                                                                    <label class="form-check-label"
                                                                           for="testEnvironmentSwitch"></label>
                                                                </div>
                                                                <span class="invalid-feedback">
                                                                    @error('test_environment') @lang($message) @enderror
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card supported_currency_card mt-lg-5">
                                    <div class="card-header">
                                        <h4 class="card-header-title">@lang('Supported Currencies Configuration')</h4>
                                    </div>
                                    <div class="table-responsive position-relative">
                                        <table
                                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                            id="supported_currency_table">
                                            <thead class="thead-light">
                                            <tr>
                                                <th scope="col">@lang('Currency Symbol')</th>
                                                <th scope="col">@lang('Conversion Rate')</th>
                                                <th scope="col">@lang('Min Limit')</th>
                                                <th scope="col">@lang('Max Limit')</th>
                                                <th scope="col">@lang('Percentage Charge')</th>
                                                <th scope="col">@lang('Fixed Charge')</th>
                                            </tr>
                                            </thead>
                                            <tbody class="add_table_row">
                                            @php
                                                $oldReceivableCurrency = old('receivable_currencies', $method->receivable_currencies) ? count(old('receivable_currencies', $method->receivable_currencies)) : 0;
                                                $oldSelectedCurrency = session()->get('selectedCurrencyList');
                                            @endphp
                                            @if($oldReceivableCurrency > 0)
                                                @for($i = 0; $i < $oldReceivableCurrency; $i++)
                                                    <tr class="{{ $oldSelectedCurrency[$i] ?? $method->receivable_currencies[$i]->name }}-row">
                                                        <td>
                                                            <div class="mb-1">
                                                                <input type="text" class="form-control"
                                                                       name="receivable_currencies[{{ $i }}][currency_symbol]"
                                                                       placeholder="Currency Symbol"
                                                                       aria-label="Currency Symbol"
                                                                       value="{{ old("receivable_currencies.$i.currency_symbol",  $method->receivable_currencies[$i]->name ?? '') }}"
                                                                       autocomplete="off">
                                                                @error("receivable_currencies.$i.currency_symbol")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <span class="input-group-text"> 1 {{ $basicControl->base_currency ? : 'USD' }} = </span>
                                                                <input type="text"
                                                                       class="form-control @error('conversion_rate') is-invalid @enderror"
                                                                       name="receivable_currencies[{{ $i }}][conversion_rate]"
                                                                       value="{{ old("receivable_currencies.$i.conversion_rate", $method->receivable_currencies[$i]->conversion_rate ?? '') }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text">{{ old("receivable_currencies.$i.name", $method->currency_type == 1 ? $method->receivable_currencies[$i]->name : 'USD') }}</span>
                                                                @error("receivable_currencies.$i.conversion_rate")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <input type="text"
                                                                       class="form-control @error('min_limit') is-invalid @enderror"
                                                                       name="receivable_currencies[{{ $i }}][min_limit]"
                                                                       aria-label="Amount (to the nearest dollar)"
                                                                       value="{{ old("receivable_currencies.$i.min_limit", $method->receivable_currencies[$i]->min_limit ?? '')  }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text">{{ old("receivable_currencies.$i.name", $method->currency_type == 1 ? $method->receivable_currencies[$i]->name : "USD") }}</span>
                                                                @error("receivable_currencies.$i.min_limit")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <input type="text"
                                                                       class="form-control @error('max_limit') is-invalid @enderror"
                                                                       name="receivable_currencies[{{$i}}][max_limit]"
                                                                       aria-label="Amount"
                                                                       value="{{ old("receivable_currencies.$i.max_limit", $method->receivable_currencies[$i]->max_limit ?? '') }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text">{{ old("receivable_currencies.$i.name", $method->currency_type == 1 ? $method->receivable_currencies[$i]->name : "USD") }}</span>
                                                                @error("receivable_currencies.$i.max_limit")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <input type="text"
                                                                       class="form-control @error('percentage_charge') is-invalid @enderror"
                                                                       name="receivable_currencies[{{ $i }}][percentage_charge]"
                                                                       aria-label="Amount (to the nearest dollar)"
                                                                       value="{{ old("receivable_currencies.$i.percentage_charge", $method->receivable_currencies[$i]->percentage_charge ?? "") }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text">%</span>
                                                                @error("receivable_currencies.$i.percentage_charge")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <input type="text"
                                                                       class="form-control @error('fixed_charge') is-invalid @enderror"
                                                                       name="receivable_currencies[{{ $i }}][fixed_charge]"
                                                                       aria-label="Amount (to the nearest dollar)"
                                                                       value="{{ old("receivable_currencies.$i.fixed_charge", $method->receivable_currencies[$i]->fixed_charge ?? '') }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text">{{ old("receivable_currencies.$i.name", $method->currency_type == 1 ? $method->receivable_currencies[$i]->name : "USD") }}</span>
                                                                @error("receivable_currencies.$i.fixed_charge")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endfor
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start mt-4">
                                    <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
                                </div>
                            </form>
                        </div>
                    </div>
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
    <script src="{{ asset('assets/admin/js/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/clipboard.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {

            var eventHandler = function (name) {
                return function () {
                    if (name == 'onItemAdd') {
                        itemAppend(arguments[0]);
                    } else if (name == 'onItemRemove') {
                        itemRemove(arguments[0][0]);
                    }
                };
            };

            var tomSelect = new TomSelect('.js-select', {
                plugins: {
                    remove_button: {
                        title: '',
                    },
                },
                create: true,
                onItemAdd: eventHandler('onItemAdd'),
                onDelete: eventHandler('onItemRemove'),
            });


            let method = @json($method);
            function itemAppend(currency) {
                let rowCount = $('#supported_currency_table tr').length;
                currency = method.currency_type === 1 ? currency : 'USD';
                let markup = "";
                markup += `
                        <tr class="${currency}-row">
                                <td>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="receivable_currencies[${rowCount - 1}][currency_symbol]"
                                           placeholder="@lang("Currency Symbol")" aria-label="@lang("Currency Symbol")"
                                           autocomplete="off">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">1 {{ $basicControl->base_currency }} = </span>
                                        <input type="text"
                                            class="form-control"
                                            name="receivable_currencies[${rowCount - 1}][conversion_rate]"
                                            autocomplete="off">
                                            <span class="input-group-text">${currency}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control"
                                          name="receivable_currencies[${rowCount - 1}][min_limit]"
                                          autocomplete="off">
                                          <span class="input-group-text">${currency}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control"
                                            name="receivable_currencies[${rowCount - 1}][max_limit]"
                                            autocomplete="off">
                                            <span class="input-group-text">${currency}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control"
                                            name="receivable_currencies[${rowCount - 1}][percentage_charge]"
                                            autocomplete="off">
                                            <span class="input-group-text">%</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control"
                                             name="receivable_currencies[${rowCount - 1}][fixed_charge]"
                                             autocomplete="off">
                                             <span class="input-group-text">${currency}</span>
                                    </div>
                                </td>

                        </tr>`;

                $('.add_table_row').append(markup);
            }

            function itemRemove(currency) {
                $(`.${currency}-row`).remove();
                alignArrayIndexForSupportCurrency();
            }


            function alignArrayIndexForSupportCurrency() {
                $('.add_table_row tr').each(function (index) {
                    $(this).find('input[name^="receivable_currencies"]').each(function () {
                        var newName = $(this).attr('name').replace(/\[(\d+)\]/, '[' + index + ']');
                        $(this).attr('name', newName);
                    })
                })
            }

            new HSFileAttach('.js-file-attach')
            HSCore.components.HSClipboard.init('.js-clipboard')

        });

    </script>
@endpush

