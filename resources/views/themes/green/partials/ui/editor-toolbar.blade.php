{{--
  NLP UI: Editor Toolbar Icons
  Usage: @include('themes.green.partials.ui.editor-toolbar')
--}}
<div class="nlp-editor-toolbar" role="toolbar" aria-label="Text editor">
    <button type="button" class="nlp-editor-icon" title="Bold" data-cmd="bold">
        <i class="fa-solid fa-bold"></i>
    </button>
    <button type="button" class="nlp-editor-icon" title="Italic" data-cmd="italic">
        <i class="fa-solid fa-italic"></i>
    </button>
    <button type="button" class="nlp-editor-icon" title="Insert Image" data-cmd="img">
        <i class="fa-regular fa-image"></i>
    </button>
    <button type="button" class="nlp-editor-icon" title="Emoji" data-cmd="emo">
        <i class="fa-regular fa-face-smile"></i>
    </button>
    <span class="nlp-divider vertical" style="display:inline-block;"></span>
    <button type="button" class="nlp-editor-icon" title="Link" data-cmd="link">
        <i class="fa-regular fa-link"></i>
    </button>
    <button type="button" class="nlp-editor-icon" title="Unordered List" data-cmd="ul">
        <i class="fa-regular fa-list-ul"></i>
    </button>
</div>
