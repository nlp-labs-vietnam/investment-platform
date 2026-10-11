{{--
  NLP UI: File Card (single or double)
  Props:
    $files = [
        ['icon' => 'fa-regular fa-file-pdf', 'name' => 'Ho-so-nang-luc.pdf', 'meta' => '2.4 MB · PDF', 'sync' => false],
        ['icon' => 'fa-regular fa-file-image', 'name' => 'Logo-NLP.png', 'meta' => '84 KB · PNG', 'sync' => true],
    ]
    $double = true
--}}
@props([
    'files'  => [],
    'double' => true,
])

<div class="{{ $double ? 'nlp-double-card' : '' }}">
    @foreach($files as $file)
    <div class="nlp-file-card">
        <div class="nlp-file-card-icon">
            <i class="{{ $file['icon'] ?? 'fa-regular fa-file' }}"></i>
        </div>
        <div class="nlp-file-card-info">
            <div class="nlp-file-card-name" title="{{ $file['name'] }}">{{ $file['name'] }}</div>
            @if(!empty($file['meta']))<div class="nlp-file-card-meta">{{ $file['meta'] }}</div>@endif
        </div>
        <button type="button"
                class="nlp-file-card-sync {{ !empty($file['sync']) ? 'syncing' : '' }}"
                title="@lang('Đồng bộ')">
            <i class="fa-regular fa-arrows-rotate"></i>
        </button>
    </div>
    @endforeach
</div>
