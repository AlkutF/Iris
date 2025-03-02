@foreach ($comments as $comment)
    <div class="comment mb-2" id="comment-{{ $comment->id }}">
        <p><strong>{{ $comment->user->name }}</strong>:</p>
        <p class="comment-content">{{ $comment->content }}</p>
    </div>
@endforeach
