{{--
  NLP UI: Inbox List
  Props:
    $messages = [
        ['avatar' => null, 'initials' => 'NL', 'name' => 'Nguyễn Lan', 'time' => '10:30',
         'preview' => 'Tin nhắn mẫu...', 'unread' => true, 'dark' => false, 'disabled' => false],
    ]
--}}
@props(['messages' => []])

<ul class="nlp-inbox-list">
    @foreach($messages as $msg)
    <li class="nlp-inbox-item {{ !empty($msg['unread']) ? 'unread' : '' }} {{ !empty($msg['disabled']) ? 'opacity-50 pointer-events-none' : '' }}">
        <div class="nlp-inbox-avatar">
            @if(!empty($msg['avatar']))
                <img src="{{ $msg['avatar'] }}" alt="{{ $msg['name'] }}">
            @else
                {{ $msg['initials'] ?? substr($msg['name'],0,2) }}
            @endif
        </div>
        <div style="flex:1;min-width:0">
            <div class="nlp-inbox-name-time">
                <span class="nlp-inbox-name">{{ $msg['name'] }}</span>
                <span class="nlp-inbox-time">{{ $msg['time'] }}</span>
            </div>
            <div class="nlp-inbox-preview">{{ $msg['preview'] }}</div>
        </div>
    </li>
    @endforeach
</ul>
