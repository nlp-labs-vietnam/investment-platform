{{--
  NLP UI: Button
  Props:
    $variant  = 'primary' | 'white' | 'deactive' | 'outline'
    $size     = '' | 'sm' | 'lg'
    $href     = null (renders <a>) | null (renders <button>)
    $icon     = null | 'fa-solid fa-arrow-right'
    $iconPos  = 'right' | 'left'
    $label    = 'Button'
    $type     = 'button' | 'submit'
    $class    = ''
--}}
@props([
    'variant' => 'primary',
    'size'    => '',
    'href'    => null,
    'icon'    => null,
    'iconPos' => 'right',
    'label'   => 'Button',
    'type'    => 'button',
    'class'   => '',
])
@php
    $cls = 'nlp-btn nlp-btn-' . $variant;
    if ($size) $cls .= ' nlp-btn-' . $size;
    if ($class) $cls .= ' ' . $class;
@endphp

@if($href)
<a href="{{ $href }}" class="{{ $cls }}">
    @if($icon && $iconPos === 'left')<i class="{{ $icon }}"></i>@endif
    {{ $label }}
    @if($icon && $iconPos === 'right')<i class="{{ $icon }}"></i>@endif
</a>
@else
<button type="{{ $type }}" class="{{ $cls }}" {{ $attributes }}>
    @if($icon && $iconPos === 'left')<i class="{{ $icon }}"></i>@endif
    {{ $label }}
    @if($icon && $iconPos === 'right')<i class="{{ $icon }}"></i>@endif
</button>
@endif
