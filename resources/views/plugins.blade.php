<!--Start of Google analytic Script-->
@if(basicControl()->analytic_status)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{basicControl()->measurement_id}}"></script>
    <script>
        "use strict";
        var MEASUREMENT_ID = "{{ basicControl()->measurement_id }}";
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', MEASUREMENT_ID);
    </script>
@endif
<!--End of Google analytic Script-->


<!--Start of Tawk.to Script-->
@if(basicControl()->tawk_status)
    <script type="text/javascript">
        // $(document).ready(function () {
        var Tawk_SRC = 'https://embed.tawk.to/' + "{{ trim(basicControl()->tawk_id) }}";
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        (function () {
            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = Tawk_SRC;
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
        // });
    </script>
@endif


<!--start of Facebook Messenger Script-->
@if(basicControl()->fb_messenger_status)
    <div id="fb-root"></div>
    <script>
        "use strict";
        var fb_app_id = "{{ basicControl()->fb_app_id }}";
        window.fbAsyncInit = function () {
            FB.init({
                appId: fb_app_id,
                autoLogAppEvents: true,
                xfbml: true,
                version: 'v10.0'
            });
        };
        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
    <div class="fb-customerchat" page_id="{{ basicControl()->fb_page_id }}"></div>
@endif
<!--End of Facebook Messenger Script-->


<!--Start of Popup Chat B2B Widget-->
@if(basicControl()->popup_chat_status)
@php
    $pc          = basicControl();
    $pcGreeting  = $pc->popup_chat_greeting  ?: 'Xin chào! Chúng tôi có thể hỗ trợ gì cho bạn?';
    $pcTagline   = $pc->popup_chat_tagline   ?: 'Đội ngũ NLP Group phản hồi trong 15 phút.';
    $pcAgent     = $pc->popup_chat_agent_name   ?: ($pc->site_title ?: 'NLP Group');
    $pcAvatar    = $pc->popup_chat_agent_avatar ?? '';
    $pcChannels  = [];
    if (!empty($pc->popup_chat_channels)) {
        $decoded = json_decode($pc->popup_chat_channels, true);
        if (is_array($decoded)) $pcChannels = $decoded;
    }
    // Build href from each channel
    $pcHrefs = [];
    foreach ($pcChannels as $ch) {
        $val  = $ch['value'] ?? '';
        $type = $ch['type']  ?? 'custom';
        $pcHrefs[] = match($type) {
            'zalo'     => (str_starts_with($val, 'http') ? $val : 'https://zalo.me/' . $val),
            'whatsapp' => 'https://wa.me/' . preg_replace('/\D/', '', $val),
            'phone'    => 'tel:' . $val,
            'email'    => 'mailto:' . $val,
            default    => $val,
        };
    }
@endphp
<style>
/* ── Popup Chat B2B ─────────────────────────────────── */
#nlpChatLauncher {
    position: fixed;
    bottom: 28px;
    right: 24px;
    z-index: 9998;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: #0d1b4b;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 18px rgba(0,0,0,.3);
    transition: transform .2s ease, box-shadow .2s ease;
    outline: none;
}
#nlpChatLauncher:hover { transform: scale(1.08); box-shadow: 0 8px 28px rgba(0,0,0,.35); }
#nlpChatLauncher svg  { transition: transform .25s ease, opacity .25s ease; }
#nlpChatLauncher .ico-chat  { position:absolute; }
#nlpChatLauncher .ico-close { position:absolute; opacity:0; transform:rotate(-90deg); }
#nlpChatLauncher.open .ico-chat  { opacity:0; transform:rotate(90deg); }
#nlpChatLauncher.open .ico-close { opacity:1; transform:rotate(0deg); }

/* Badge */
#nlpChatBadge {
    position: absolute;
    top: -4px; right: -4px;
    width: 18px; height: 18px;
    background: #E53935;
    border-radius: 50%;
    font-size: 10px;
    font-weight: 700;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
}

/* Panel */
#nlpChatPanel {
    position: fixed;
    bottom: 96px;
    right: 24px;
    z-index: 9999;
    width: 320px;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 8px 40px rgba(0,0,0,.22);
    font-family: -apple-system,'Segoe UI',system-ui,sans-serif;
    overflow: hidden;
    transform: scale(.92) translateY(16px);
    transform-origin: bottom right;
    opacity: 0;
    pointer-events: none;
    transition: transform .22s cubic-bezier(.34,1.56,.64,1), opacity .18s ease;
}
#nlpChatPanel.open {
    transform: scale(1) translateY(0);
    opacity: 1;
    pointer-events: auto;
}

/* Panel header */
.nlp-chat-header {
    background: #0d1b4b;
    padding: 18px 18px 52px;
    position: relative;
}
.nlp-chat-header-inner {
    display: flex;
    align-items: center;
    gap: 12px;
}
.nlp-chat-avatar {
    width: 46px; height: 46px;
    border-radius: 50%;
    background: rgba(255,255,255,.18);
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
    border: 2px solid rgba(255,255,255,.35);
}
.nlp-chat-avatar img { width:100%; height:100%; object-fit:cover; }
.nlp-chat-agent-name  { font-weight:700; font-size:15px; color:#fff; }
.nlp-chat-tagline     { font-size:11px;  color:rgba(255,255,255,.7); margin-top:2px; }

/* Wave card */
.nlp-chat-wave {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 48px;
    background: #fff;
    border-radius: 18px 18px 0 0;
}

/* Greeting bubble */
.nlp-chat-greeting {
    margin: -14px 16px 10px;
    background: #fff;
    border-radius: 14px;
    padding: 12px 14px;
    font-size: 13px;
    color: #333;
    line-height: 1.55;
    box-shadow: 0 2px 12px rgba(0,0,0,.10);
    position: relative;
    z-index: 1;
}

/* Channel list */
.nlp-chat-channels {
    padding: 6px 16px 16px;
    display: flex;
    flex-direction: column;
    gap: 9px;
}
.nlp-chat-channel-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    border-radius: 12px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    color: #1a1a2e;
    border: 1.5px solid transparent;
    transition: transform .15s ease, box-shadow .15s ease;
    background: #f8f9fc;
}
.nlp-chat-channel-btn:hover {
    transform: translateX(4px);
    box-shadow: 0 3px 10px rgba(0,0,0,.12);
    color: #1a1a2e;
    text-decoration: none;
}
.nlp-chat-channel-icon {
    width: 34px; height: 34px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-size: 16px;
    color: #fff;
}
.nlp-chat-channel-meta { line-height: 1.2; }
.nlp-chat-channel-meta small { font-size:10px; font-weight:400; color:#888; }

@media (max-width: 420px) {
    #nlpChatPanel { width: calc(100vw - 32px); right: 16px; }
    #nlpChatLauncher { right: 16px; bottom: 16px; }
}
</style>

{{-- Launcher button --}}
<button id="nlpChatLauncher" aria-label="Mở chat hỗ trợ" aria-expanded="false"
        aria-controls="nlpChatPanel">
    {{-- chat icon --}}
    <svg class="ico-chat" width="26" height="26" viewBox="0 0 24 24" fill="none"
         xmlns="http://www.w3.org/2000/svg">
        <path d="M20 2H4C2.9 2 2 2.9 2 4V22L6 18H20C21.1 18 22 17.1 22 16V4C22 2.9 21.1 2 20 2Z"
              fill="white"/>
    </svg>
    {{-- close icon --}}
    <svg class="ico-close" width="22" height="22" viewBox="0 0 24 24" fill="none">
        <path d="M18 6L6 18M6 6L18 18" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
    </svg>
    {{-- pulse badge --}}
    <span id="nlpChatBadge">{{ count($pcChannels) ?: 1 }}</span>
</button>

{{-- Panel --}}
<div id="nlpChatPanel" role="dialog" aria-label="Hỗ trợ NLP Group" aria-hidden="true">
    {{-- Header --}}
    <div class="nlp-chat-header">
        <div class="nlp-chat-header-inner">
            <div class="nlp-chat-avatar">
                @if($pcAvatar)
                    <img src="{{ $pcAvatar }}" alt="{{ $pcAgent }}">
                @else
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 5C13.66 5 15 6.34 15 8C15 9.66 13.66 11 12 11C10.34 11 9 9.66 9 8C9 6.34 10.34 5 12 5ZM12 19.2C9.5 19.2 7.29 17.92 6 15.98C6.03 13.99 10 12.9 12 12.9C13.99 12.9 17.97 13.99 18 15.98C16.71 17.92 14.5 19.2 12 19.2Z" fill="rgba(255,255,255,.85)"/>
                    </svg>
                @endif
            </div>
            <div>
                <div class="nlp-chat-agent-name">{{ $pcAgent }}</div>
                <div class="nlp-chat-tagline">{{ $pcTagline }}</div>
            </div>
        </div>
        <div class="nlp-chat-wave"></div>
    </div>

    {{-- Greeting bubble --}}
    <div class="nlp-chat-greeting">{{ $pcGreeting }}</div>

    {{-- Channel list --}}
    @if(count($pcChannels))
    <div class="nlp-chat-channels">
        @foreach($pcChannels as $i => $ch)
        @php
            $chColor = $ch['color'] ?? '#0068FF';
            $chIcon  = $ch['icon']  ?? 'fa-solid fa-comments';
            $chLabel = $ch['label'] ?? 'Liên hệ';
            $chHref  = $pcHrefs[$i] ?? '#';
            // sub-label per type
            $chSub = match($ch['type'] ?? 'custom') {
                'zalo'     => 'Chat qua Zalo',
                'whatsapp' => 'Nhắn tin WhatsApp',
                'phone'    => 'Gọi điện trực tiếp',
                'email'    => 'Gửi email',
                default    => 'Liên hệ ngay',
            };
        @endphp
        <a href="{{ $chHref }}" class="nlp-chat-channel-btn"
           target="{{ in_array($ch['type'] ?? '', ['phone','email']) ? '_self' : '_blank' }}"
           rel="noopener noreferrer"
           style="border-color: {{ $chColor }}22;">
            <div class="nlp-chat-channel-icon" style="background:{{ $chColor }};">
                <i class="{{ $chIcon }}"></i>
            </div>
            <div class="nlp-chat-channel-meta">
                {{ $chLabel }}
                <br><small>{{ $chSub }}</small>
            </div>
            <svg style="margin-left:auto;flex-shrink:0;opacity:.4" width="16" height="16" viewBox="0 0 24 24" fill="none">
                <path d="M9 18L15 12L9 6" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
        @endforeach
    </div>
    @endif
</div>

<script>
(function () {
    'use strict';
    var btn   = document.getElementById('nlpChatLauncher');
    var panel = document.getElementById('nlpChatPanel');
    var badge = document.getElementById('nlpChatBadge');
    var open  = false;

    function toggle() {
        open = !open;
        panel.classList.toggle('open', open);
        btn.classList.toggle('open', open);
        btn.setAttribute('aria-expanded', open);
        panel.setAttribute('aria-hidden', !open);
        if (open && badge) badge.style.display = 'none';
    }

    btn.addEventListener('click', toggle);

    // Close on outside click
    document.addEventListener('click', function (e) {
        if (open && !panel.contains(e.target) && e.target !== btn && !btn.contains(e.target)) {
            toggle();
        }
    });
})();
</script>
@endif
<!--End of Popup Chat B2B Widget-->


@if(basicControl()->cookie_status == 1 && auth()->user())
    <script>
        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + value + expires + "; path=/";
        }
        function hasAcceptedCookiePolicy() {
            return document.cookie.indexOf("cookie_policy_accepted=true") !== -1;
        }
        function acceptCookiePolicy() {
            setCookie("cookie_policy_accepted", "true", 365);
            document.getElementById("cookiesAlert").style.display = "none";
        }
        function closeCookieBanner() {
            document.getElementById("cookiesAlert").style.display = "none";
        }
        document.addEventListener('DOMContentLoaded', function () {
            if (!hasAcceptedCookiePolicy()) {
                document.getElementById("cookiesAlert").style.display = "block";
            }
        });
    </script>

    <div class="cookies-alert" id="cookiesAlert">
        <img src="{{ asset('assets/global/img/cookie.svg') }}" height="50" width="50"
             alt="{{ basicControl()->site_title }} cookies">
        <h4 class="mt-2">@lang(basicControl()->cookie_title)</h4>
        <span class="d-block mt-2">@lang(basicControl()->cookie_sub_title).
            <a href="{{ basicControl()->cookie_url }}" class="link">@lang('see more')</a>
        </span>
        <a href="javascript:void(0);" class="mt-3 cmn-btn " type="button"
           onclick="acceptCookiePolicy()">@lang('Accept')</a>
        <a href="javascript:void(0);" class="mt-2 cmn-btn3" type="button" onclick="closeCookieBanner()">@lang('Close')</a>
    </div>
@endif


<script>

    let root = document.querySelector(':root');
    @if(getTheme() == 'light' )
    root.style.setProperty('--primary', '{{basicControl()->primary_color??'#cc54f4'}}');
    {{--root.style.setProperty('--secondary', '{{'#488ff9'}}');--}}

    @else
    root.style.setProperty('--primary-color', hexToRGB('{{basicControl()->secondary_color??'#B3BD2C'}}'));
    @endif

    function hexToRGB(hex) {
        hex = hex.replace(/^#/, '');

        if (hex.length === 3) {
            hex = hex.split('').map(c => c + c).join('');
        }

        const r = parseInt(hex.slice(0, 2), 16);
        const g = parseInt(hex.slice(2, 4), 16);
        const b = parseInt(hex.slice(4, 6), 16);

        return `${r}, ${g}, ${b}`;
    }

</script>
