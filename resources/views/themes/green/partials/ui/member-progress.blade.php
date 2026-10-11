{{--
  NLP UI: Member with Progress Bar
  Props:
    $avatar   = null
    $initials = 'NL'
    $name     = 'Nguyễn Lan'
    $role     = 'Engineer'
    $percent  = 75
    $variant  = '' | 'success' | 'warning' | 'danger'
--}}
@props([
    'avatar'   => null,
    'initials' => '',
    'name'     => '',
    'role'     => '',
    'percent'  => 0,
    'variant'  => '',
])

<div class="nlp-member-item">
    <div class="nlp-member-avatar">
        @if($avatar)
            <img src="{{ $avatar }}" alt="{{ $name }}">
        @else
            {{ $initials ?: mb_substr($name,0,2) }}
        @endif
    </div>
    <div class="nlp-member-info">
        <div class="nlp-member-name">{{ $name }}</div>
        @if($role)<div class="nlp-member-role">{{ $role }}</div>@endif
        <div style="display:flex;align-items:center;gap:8px;margin-top:6px;">
            <div class="nlp-progress" style="flex:1">
                <div class="nlp-progress-bar {{ $variant }}" style="width:{{ $percent }}%"></div>
            </div>
            <span style="font-size:12px;font-weight:600;color:rgb(var(--heading-color));white-space:nowrap;">{{ $percent }}%</span>
        </div>
    </div>
</div>
