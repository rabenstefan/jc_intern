@if (isset($content))
<span onclick="$(this).notify('{{ $content }}', helpBubble);" class="help-bubble">
    <i class="fa fa-question-circle"></i>
</span>
@endif