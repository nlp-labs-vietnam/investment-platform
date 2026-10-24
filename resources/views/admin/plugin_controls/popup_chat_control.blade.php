@extends('admin.layouts.app')
@section('page_title', __('Popup Chat B2B'))
@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-sm mb-2 mb-sm-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-no-gutter">
                        <li class="breadcrumb-item"><a class="breadcrumb-link" href="javascript:void(0)">@lang('Dashboard')</a></li>
                        <li class="breadcrumb-item active"><a class="breadcrumb-link" href="{{ route('admin.settings') }}">@lang('Settings')</a></li>
                        <li class="breadcrumb-item active"><a class="breadcrumb-link" href="{{ route('admin.plugin.config') }}">@lang('Plugin Controls')</a></li>
                        <li class="breadcrumb-item active">@lang('Popup Chat B2B')</li>
                    </ol>
                </nav>
                <h1 class="page-header-title">@lang('Popup Chat B2B')</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3">
            @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.plugin'), 'suffix' => ''])
        </div>
        <div class="col-lg-9">
            <form action="{{ route('admin.popup.chat.configuration.update') }}" method="post" id="popupChatForm">
                @csrf

                {{-- ── SECTION 1: Trạng thái & Hiển thị ────────────────────── --}}
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-header-title mb-0">@lang('Hiển thị & Lời chào')</h4>
                        <div class="form-check form-switch mb-0">
                            <input type="hidden" name="popup_chat_status" value="0">
                            <input class="form-check-input" type="checkbox" name="popup_chat_status"
                                   id="popup_chat_status_switch" value="1"
                                   {{ $basicControl->popup_chat_status == 1 ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="popup_chat_status_switch">@lang('Bật Widget')</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">

                            {{-- Agent Name --}}
                            <div class="col-sm-6">
                                <label class="form-label">@lang('Tên nhân viên / Nhóm hỗ trợ')
                                    <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                       title="Hiển thị ở đầu popup. vd: NLP Group Support"></i>
                                </label>
                                <input type="text" name="popup_chat_agent_name" class="form-control"
                                       placeholder="vd: NLP Group Support"
                                       value="{{ old('popup_chat_agent_name', $basicControl->popup_chat_agent_name) }}">
                                @error('popup_chat_agent_name')<span class="text-danger small">{{ $message }}</span>@enderror
                            </div>

                            {{-- Agent Avatar URL --}}
                            <div class="col-sm-6">
                                <label class="form-label">@lang('URL Avatar Agent')
                                    <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                       title="URL ảnh đại diện. Để trống dùng icon mặc định."></i>
                                </label>
                                <input type="text" name="popup_chat_agent_avatar" class="form-control"
                                       placeholder="https://... hoặc để trống"
                                       value="{{ old('popup_chat_agent_avatar', $basicControl->popup_chat_agent_avatar) }}">
                                @error('popup_chat_agent_avatar')<span class="text-danger small">{{ $message }}</span>@enderror
                            </div>

                            {{-- Greeting --}}
                            <div class="col-sm-12">
                                <label class="form-label">@lang('Lời chào (Greeting)')
                                    <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                       title="Dòng chính hiển thị trên popup. vd: Xin chào! Chúng tôi có thể hỗ trợ gì?"></i>
                                </label>
                                <input type="text" name="popup_chat_greeting" class="form-control"
                                       placeholder="vd: Xin chào! Chúng tôi có thể hỗ trợ gì cho bạn?"
                                       value="{{ old('popup_chat_greeting', $basicControl->popup_chat_greeting) }}">
                                @error('popup_chat_greeting')<span class="text-danger small">{{ $message }}</span>@enderror
                            </div>

                            {{-- Tagline --}}
                            <div class="col-sm-12">
                                <label class="form-label">@lang('Tagline')
                                    <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                       title="Dòng phụ nhỏ dưới greeting. vd: Đội ngũ phản hồi trong 15 phút."></i>
                                </label>
                                <input type="text" name="popup_chat_tagline" class="form-control"
                                       placeholder="vd: Đội ngũ NLP Group phản hồi trong 15 phút làm việc."
                                       value="{{ old('popup_chat_tagline', $basicControl->popup_chat_tagline) }}">
                                @error('popup_chat_tagline')<span class="text-danger small">{{ $message }}</span>@enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ── SECTION 2: Kênh liên lạc (Channels) ─────────────────── --}}
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-header-title mb-0">@lang('Kênh liên lạc')
                            <small class="text-muted fw-normal ms-2">@lang('Tối đa 6 kênh')</small>
                        </h4>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addChannelBtn">
                            <i class="bi-plus-circle me-1"></i>@lang('Thêm kênh')
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-borderless table-sm align-middle mb-0" id="channelsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:130px">@lang('Loại')</th>
                                        <th>@lang('Nhãn nút')</th>
                                        <th>@lang('Giá trị (số / URL)')</th>
                                        <th style="width:160px">@lang('Icon FA Class')</th>
                                        <th style="width:90px">@lang('Màu')</th>
                                        <th style="width:50px"></th>
                                    </tr>
                                </thead>
                                <tbody id="channelsBody">
                                    @php
                                        $existingChannels = [];
                                        if (!empty($basicControl->popup_chat_channels)) {
                                            $decoded = json_decode($basicControl->popup_chat_channels, true);
                                            if (is_array($decoded)) $existingChannels = $decoded;
                                        }
                                        // Seed defaults if empty
                                        if (empty($existingChannels)) {
                                            $existingChannels = [
                                                ['type'=>'zalo',     'label'=>'Chat Zalo',      'value'=>'', 'icon'=>'fa-brands fa-square-phone-flip', 'color'=>'#0068FF'],
                                                ['type'=>'phone',    'label'=>'Hotline',         'value'=>'', 'icon'=>'fa-solid fa-phone',              'color'=>'#E53935'],
                                                ['type'=>'whatsapp', 'label'=>'WhatsApp',        'value'=>'', 'icon'=>'fa-brands fa-whatsapp',          'color'=>'#25D366'],
                                                ['type'=>'email',    'label'=>'Email',           'value'=>'', 'icon'=>'fa-solid fa-envelope',           'color'=>'#6C757D'],
                                            ];
                                        }
                                    @endphp
                                    @foreach($existingChannels as $i => $ch)
                                        <tr class="channel-row">
                                            <td>
                                                <select name="channels[{{ $i }}][type]" class="form-select form-select-sm channel-type">
                                                    @foreach(['zalo'=>'Zalo OA','whatsapp'=>'WhatsApp','phone'=>'Phone','email'=>'Email','custom'=>'Custom URL'] as $v => $l)
                                                        <option value="{{ $v }}" {{ ($ch['type'] ?? '') == $v ? 'selected' : '' }}>{{ $l }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="channels[{{ $i }}][label]"
                                                       class="form-control form-control-sm"
                                                       placeholder="vd: Chat Zalo"
                                                       value="{{ $ch['label'] ?? '' }}">
                                            </td>
                                            <td>
                                                <input type="text" name="channels[{{ $i }}][value]"
                                                       class="form-control form-control-sm channel-value"
                                                       placeholder="số / URL / email"
                                                       value="{{ $ch['value'] ?? '' }}">
                                                <div class="channel-hint text-muted" style="font-size:11px;margin-top:2px"></div>
                                            </td>
                                            <td>
                                                <input type="text" name="channels[{{ $i }}][icon]"
                                                       class="form-control form-control-sm"
                                                       placeholder="fa-brands fa-zalo"
                                                       value="{{ $ch['icon'] ?? '' }}">
                                            </td>
                                            <td>
                                                <input type="color" name="channels[{{ $i }}][color]"
                                                       class="form-control form-control-sm form-control-color w-100"
                                                       value="{{ $ch['color'] ?? '#0068FF' }}"
                                                       title="Màu nút">
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-ghost-danger remove-channel-btn"
                                                        title="Xoá kênh">
                                                    <i class="bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @error('channels')
                        <div class="px-3 pb-2"><span class="text-danger small">{{ $message }}</span></div>
                        @enderror
                    </div>
                </div>

                {{-- ── Preview ───────────────────────────────────────────────── --}}
                <div class="card mb-4">
                    <div class="card-header"><h4 class="card-header-title mb-0">@lang('Xem trước')</h4></div>
                    <div class="card-body" style="background:#f0f0f0;min-height:160px;position:relative;">
                        <div id="previewWidget"
                             style="display:inline-block;background:#fff;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,.18);
                                    padding:20px 20px 16px;max-width:320px;font-family:sans-serif;">
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
                                <div id="previewAvatar"
                                     style="width:44px;height:44px;border-radius:50%;background:#0068FF;
                                            display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;">
                                    <i class="fa-solid fa-headset" style="color:#fff;font-size:20px"></i>
                                </div>
                                <div>
                                    <div id="previewAgentName" style="font-weight:700;font-size:14px;color:#1a1a2e">NLP Group Support</div>
                                    <div id="previewTagline" style="font-size:11px;color:#888">Phản hồi trong 15 phút</div>
                                </div>
                            </div>
                            <div id="previewGreeting" style="font-size:13px;color:#333;margin-bottom:14px">
                                Xin chào! Chúng tôi có thể hỗ trợ gì cho bạn?
                            </div>
                            <div id="previewChannels" style="display:flex;flex-direction:column;gap:8px"></div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mb-5">
                    <a href="{{ route('admin.plugin.config') }}" class="btn btn-white">@lang('Huỷ')</a>
                    <button type="submit" class="btn btn-primary">@lang('Lưu thay đổi')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
'use strict';

/* ── Hint text per channel type ──────────────────────────── */
const HINTS = {
    zalo:     'Zalo OA ID, vd: 1234567890 hoặc https://zalo.me/oaid',
    whatsapp: 'Số quốc tế không dấu cộng, vd: 84901234567',
    phone:    'Số điện thoại, vd: 0901234567',
    email:    'Địa chỉ email, vd: contact@nlpgroup.vn',
    custom:   'URL đầy đủ, vd: https://meet.google.com/abc',
};
const DEFAULT_ICONS = {
    zalo:     'fa-brands fa-square-phone-flip',
    whatsapp: 'fa-brands fa-whatsapp',
    phone:    'fa-solid fa-phone',
    email:    'fa-solid fa-envelope',
    custom:   'fa-solid fa-link',
};

/* ── Update hint on type change ──────────────────────────── */
$(document).on('change', '.channel-type', function () {
    const row  = $(this).closest('tr');
    const type = $(this).val();
    row.find('.channel-hint').text(HINTS[type] || '');
    const iconField = row.find('input[name$="[icon]"]');
    if (!iconField.val()) iconField.val(DEFAULT_ICONS[type] || '');
});

// Init hints on load
$('.channel-row').each(function () {
    const type = $(this).find('.channel-type').val();
    $(this).find('.channel-hint').text(HINTS[type] || '');
});

/* ── Remove channel row ──────────────────────────────────── */
$(document).on('click', '.remove-channel-btn', function () {
    $(this).closest('tr').remove();
    reIndexChannels();
    renderPreview();
});

/* ── Add channel row ─────────────────────────────────────── */
$('#addChannelBtn').on('click', function () {
    const rows = $('#channelsBody tr').length;
    if (rows >= 6) {
        alert('Tối đa 6 kênh liên lạc.');
        return;
    }
    const idx = rows;
    const html = `
    <tr class="channel-row">
        <td>
            <select name="channels[${idx}][type]" class="form-select form-select-sm channel-type">
                <option value="zalo">Zalo OA</option>
                <option value="whatsapp">WhatsApp</option>
                <option value="phone">Phone</option>
                <option value="email">Email</option>
                <option value="custom">Custom URL</option>
            </select>
        </td>
        <td><input type="text" name="channels[${idx}][label]" class="form-control form-control-sm" placeholder="vd: Chat Zalo"></td>
        <td>
            <input type="text" name="channels[${idx}][value]" class="form-control form-control-sm channel-value" placeholder="số / URL / email">
            <div class="channel-hint text-muted" style="font-size:11px;margin-top:2px">${HINTS['zalo']}</div>
        </td>
        <td><input type="text" name="channels[${idx}][icon]" class="form-control form-control-sm" placeholder="fa-brands fa-zalo" value="fa-brands fa-square-phone-flip"></td>
        <td><input type="color" name="channels[${idx}][color]" class="form-control form-control-sm form-control-color w-100" value="#0068FF"></td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-ghost-danger remove-channel-btn"><i class="bi-trash"></i></button>
        </td>
    </tr>`;
    $('#channelsBody').append(html);
    renderPreview();
});

/* ── Re-index channel names after removal ────────────────── */
function reIndexChannels() {
    $('#channelsBody tr').each(function (i) {
        $(this).find('[name]').each(function () {
            const newName = $(this).attr('name').replace(/channels\[\d+\]/, `channels[${i}]`);
            $(this).attr('name', newName);
        });
    });
}

/* ── Live preview ────────────────────────────────────────── */
function renderPreview() {
    const agentName = $('[name="popup_chat_agent_name"]').val() || 'NLP Group Support';
    const tagline   = $('[name="popup_chat_tagline"]').val()    || 'Phản hồi trong 15 phút';
    const greeting  = $('[name="popup_chat_greeting"]').val()   || 'Xin chào! Chúng tôi có thể hỗ trợ gì cho bạn?';
    const avatarUrl = $('[name="popup_chat_agent_avatar"]').val();

    $('#previewAgentName').text(agentName);
    $('#previewTagline').text(tagline);
    $('#previewGreeting').text(greeting);

    if (avatarUrl) {
        $('#previewAvatar').html(`<img src="${avatarUrl}" style="width:100%;height:100%;object-fit:cover;border-radius:50%">`);
    } else {
        $('#previewAvatar').html('<i class="fa-solid fa-headset" style="color:#fff;font-size:20px"></i>');
    }

    let channelsHtml = '';
    $('#channelsBody tr').each(function () {
        const label = $(this).find('[name$="[label]"]').val() || 'Kênh';
        const icon  = $(this).find('[name$="[icon]"]').val()  || 'fa-solid fa-comments';
        const color = $(this).find('[name$="[color]"]').val() || '#0068FF';
        channelsHtml += `
        <div style="display:flex;align-items:center;gap:10px;padding:8px 12px;border-radius:10px;
                    background:${color}18;border:1px solid ${color}44;cursor:pointer">
            <i class="${icon}" style="color:${color};font-size:18px;width:22px;text-align:center"></i>
            <span style="font-size:13px;font-weight:600;color:#333">${label}</span>
        </div>`;
    });
    $('#previewChannels').html(channelsHtml || '<em style="font-size:12px;color:#aaa">Chưa có kênh nào.</em>');
}

/* bind live inputs */
$(document).on('input change', '#popupChatForm input, #popupChatForm select', renderPreview);

// Initial render
renderPreview();
</script>
@endpush
