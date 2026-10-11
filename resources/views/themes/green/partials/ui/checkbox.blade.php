{{--
  NLP UI: Checkbox
  Props:
    $name    = 'checkbox'
    $value   = '1'
    $label   = 'Checkbox label'
    $checked = false
    $id      = null (auto-generated)
--}}
@props([
    'name'    => 'checkbox',
    'value'   => '1',
    'label'   => '',
    'checked' => false,
    'id'      => null,
])
@php $uid = $id ?? 'nlp-chk-' . uniqid(); @endphp

<label class="nlp-checkbox" for="{{ $uid }}">
    <input type="checkbox"
           id="{{ $uid }}"
           name="{{ $name }}"
           value="{{ $value }}"
           {{ $checked ? 'checked' : '' }}
           {{ $attributes }}>
    <span class="nlp-check-box">
        <i class="fa-solid fa-check"></i>
    </span>
    @if($label)<span>{{ $label }}</span>@endif
</label>
