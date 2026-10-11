{{--
  NLP UI: Circle Button
  Props:
    $icon     = 'fa-regular fa-plus'
    $size     = '' | 'sm' | 'lg'
    $shadow   = false
    $hideBg   = false
    $active   = false
    $title    = ''
    $class    = ''
--}}
@props([
    'icon'   => 'fa-regular fa-plus',
    'size'   => '',
    'shadow' => false,
    'hideBg' => false,
    'active' => false,
    'title'  => '',
    'class'  => '',
])
@php
    $cls = 'nlp-btn-circle';
    if ($size)   $cls .= ' ' . $size;
    if ($shadow) $cls .= ' shadow';
    if ($hideBg) $cls .= ' hide-bg';
    if ($active) $cls .= ' active';
    if ($class)  $cls .= ' ' . $class;
@endphp
<button type="button" class="{{ $cls }}" @if($title) title="{{ $title }}" @endif {{ $attributes }}>
    <i class="{{ $icon }}"></i>
</button>
