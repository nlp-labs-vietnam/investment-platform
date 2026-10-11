{{--
  NLP UI: Circle Progress
  Props:
    $percent = 72
    $size    = 64   (px)
    $stroke  = 5
    $label   = null (shows percent by default)
--}}
@props([
    'percent' => 0,
    'size'    => 64,
    'stroke'  => 5,
    'label'   => null,
])
@php
    $r = ($size / 2) - $stroke;
    $circ = round(2 * M_PI * $r, 2);
    $offset = round($circ - ($percent / 100) * $circ, 2);
@endphp

<div class="nlp-circle-progress" style="width:{{ $size }}px;height:{{ $size }}px;">
    <svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 {{ $size }} {{ $size }}">
        <circle class="track"
            cx="{{ $size/2 }}" cy="{{ $size/2 }}" r="{{ $r }}"
            stroke-width="{{ $stroke }}"/>
        <circle class="bar"
            cx="{{ $size/2 }}" cy="{{ $size/2 }}" r="{{ $r }}"
            stroke-width="{{ $stroke }}"
            stroke-dasharray="{{ $circ }}"
            stroke-dashoffset="{{ $offset }}"/>
    </svg>
    <span class="label">{{ $label ?? $percent . '%' }}</span>
</div>
