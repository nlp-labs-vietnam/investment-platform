{{--
  NLP UI: Info Board
  Props:
    $title   = 'Tiêu đề'
    $date    = '11/10/2026'
    $time    = '09:00'
    $icon    = 'fa-regular fa-calendar'
    $content = '' (optional slot)
--}}
@props([
    'title'   => '',
    'date'    => '',
    'time'    => '',
    'icon'    => 'fa-regular fa-calendar',
    'content' => '',
])

<div class="nlp-info-board">
    <div class="nlp-info-board-title">{{ $title }}</div>
    @if($date || $time)
    <div class="nlp-info-board-meta">
        <i class="{{ $icon }}"></i>
        @if($date)<span>{{ $date }}</span>@endif
        @if($time)<span>{{ $time }}</span>@endif
    </div>
    @endif
    @if($content || $slot->isNotEmpty())
    <div style="margin-top:10px;font-size:13px;color:#8898a4;line-height:1.6;">
        {{ $content ?: $slot }}
    </div>
    @endif
</div>
