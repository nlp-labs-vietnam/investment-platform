{{--
  NLP UI: Vertical Double Bar Chart Element
  Props:
    $data = [
        ['primary' => 70, 'secondary' => 40],
        ['primary' => 55, 'secondary' => 30],
        ...
    ]
    $height = 80   (px container height)
--}}
@props([
    'data'   => [],
    'height' => 80,
])

<div class="nlp-chart-vdouble" style="height:{{ $height }}px;">
    @foreach($data as $group)
    <div class="bar-group">
        <div class="bar primary"
             style="height:{{ $group['primary'] ?? 0 }}%"
             title="{{ $group['primary'] ?? 0 }}%"></div>
        <div class="bar secondary"
             style="height:{{ $group['secondary'] ?? 0 }}%"
             title="{{ $group['secondary'] ?? 0 }}%"></div>
    </div>
    @endforeach
</div>
