{{--
  NLP UI: Page Title
  Props:
    $title       = 'Tên Trang'
    $breadcrumbs = [['label'=>'Trang Chủ','href'=>'/'],['label'=>'Tên Trang']]
    $actions     = false (slot for action buttons on right)
--}}
@props([
    'title'       => '',
    'breadcrumbs' => [],
])

<div class="nlp-page-title">
    <div class="nlp-page-title-left">
        <h1>{{ $title }}</h1>
        @if(count($breadcrumbs))
        <ul class="nlp-breadcrumb" aria-label="breadcrumb">
            @foreach($breadcrumbs as $crumb)
            <li>
                @if(!empty($crumb['href']) && !$loop->last)
                    <a href="{{ $crumb['href'] }}">{{ $crumb['label'] }}</a>
                @else
                    {{ $crumb['label'] }}
                @endif
            </li>
            @endforeach
        </ul>
        @endif
    </div>
    @if($slot->isNotEmpty())
    <div class="nlp-page-title-right" style="display:flex;align-items:center;gap:8px;">
        {{ $slot }}
    </div>
    @endif
</div>
