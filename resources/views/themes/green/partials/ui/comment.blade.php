{{--
  NLP UI: Comment
  Props:
    $avatar   = null
    $initials = 'NL'
    $author   = 'Nguyễn Lan'
    $time     = '10 phút trước'
    $text     = 'Nội dung bình luận...'
    $dark     = false
    $actions  = true
--}}
@props([
    'avatar'   => null,
    'initials' => '',
    'author'   => '',
    'time'     => '',
    'text'     => '',
    'dark'     => false,
    'actions'  => true,
])

<div class="nlp-comment {{ $dark ? 'dark' : '' }}">
    <div class="nlp-comment-avatar">
        @if($avatar)
            <img src="{{ $avatar }}" alt="{{ $author }}">
        @else
            {{ $initials ?: mb_substr($author,0,2) }}
        @endif
    </div>
    <div class="nlp-comment-body">
        <div class="nlp-comment-info">
            <span class="nlp-comment-author">{{ $author }}</span>
            @if($time)<span class="nlp-comment-time">{{ $time }}</span>@endif
        </div>
        <p class="nlp-comment-text">{{ $text }}</p>
        @if($actions)
        <div class="nlp-comment-actions">
            <button class="nlp-comment-action-btn"><i class="fa-regular fa-thumbs-up"></i> @lang('Thích')</button>
            <button class="nlp-comment-action-btn"><i class="fa-regular fa-reply"></i> @lang('Trả lời')</button>
        </div>
        @endif
    </div>
</div>
