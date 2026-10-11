{{--
  NLP UI: Navigation
  Props:
    $orientation = 'horizontal' | 'vertical'
    $items = [
        ['label' => 'Trang Chủ', 'href' => '/', 'icon' => 'fa-regular fa-house', 'active' => true, 'badge' => null],
        ['divider' => true],
        ['label' => 'Tin Tức',   'href' => '/blog', 'icon' => 'fa-regular fa-newspaper'],
    ]
    $dark = false
--}}
@props([
    'orientation' => 'horizontal',
    'items'       => [],
    'dark'        => false,
])
@php
    $navClass = $orientation === 'vertical' ? 'nlp-nav-v' : 'nlp-nav-h';
    if ($dark) $navClass .= ' dark';
@endphp

<ul class="{{ $navClass }}">
    @foreach($items as $item)
        @if(!empty($item['divider']))
            <li class="nlp-nav-divider" role="separator"></li>
        @else
            <li class="nlp-nav-item {{ !empty($item['active']) ? 'active' : '' }}">
                <a class="nlp-nav-link" href="{{ $item['href'] ?? '#' }}">
                    @if(!empty($item['icon']))<i class="{{ $item['icon'] }}"></i>@endif
                    <span>{{ $item['label'] }}</span>
                    @if(!empty($item['badge']))
                        <span class="nlp-nav-badge">{{ $item['badge'] }}</span>
                    @endif
                </a>
            </li>
        @endif
    @endforeach
</ul>
