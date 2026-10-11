{{--
  NLP UI: Dropdown
  Props:
    $label   = 'Chọn...'
    $size    = '' | 'medium'
    $id      = null
    $items   = [
        ['label' => 'Option 1', 'href' => '#', 'icon' => null],
        ['divider' => true],
        ['label' => 'Option 2', 'href' => '#'],
    ]
--}}
@props([
    'label' => 'Chọn...',
    'size'  => '',
    'id'    => null,
    'items' => [],
])
@php $uid = $id ?? 'nlp-dd-' . uniqid(); @endphp

<div class="nlp-dropdown {{ $size }}" id="{{ $uid }}" x-data="{ open: false }" @click.away="open = false">
    <button type="button"
            class="nlp-dropdown-toggle"
            @click="open = !open"
            :aria-expanded="open"
            :class="{ 'open': open }">
        <span>{{ $label }}</span>
        <i class="fa-regular fa-angle-down nlp-dd-arrow"></i>
    </button>
    <ul class="nlp-dropdown-menu" :class="{ open: open }" x-show="open" x-cloak>
        @foreach($items as $item)
            @if(!empty($item['divider']))
                <li class="divider" role="separator"></li>
            @else
                <li>
                    <a href="{{ $item['href'] ?? '#' }}">
                        @if(!empty($item['icon']))<i class="{{ $item['icon'] }}"></i>@endif
                        {{ $item['label'] }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</div>
