<div class="chat-header mb-3">
    <h4>Chat con {{ $chat->users->where('id', '!=', auth()->id())->first()->name }}</h4>
</div>

<div class="chat-messages" style="height: calc(100vh - 200px); overflow-y: auto; padding: 10px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;">
    @forelse ($messages as $message)
        <div class="message {{ $message->user_id == auth()->id() ? 'sent' : 'received' }}" style="margin-bottom: 10px;">
            <p>
                <strong>{{ $message->user->name }}:</strong>
                {{ $message->content }}
            </p>
            <span class="text-muted" style="font-size: 0.8em;">{{ $message->created_at->diffForHumans() }}</span>
        </div>
    @empty
        <p class="text-muted">No hay mensajes en este chat.</p>
    @endforelse
</div>

<div class="chat-input mt-3">
    <form id="send-message-form">
        <div class="input-group">
            <input type="hidden" id="chat-id" value="{{ $chat->id }}">
            <input type="text" id="message-content" class="form-control" placeholder="Escribe un mensaje...">
            <button type="button" class="btn btn-primary" onclick="sendMessage()">Enviar</button>
        </div>
    </form>
</div>
@section('scripts')
<script>
    // Enviar mensaje por AJAX
    function sendMessage() {
        const chatId = $('#chat-id').val();
        const messageContent = $('#message-content').val();

        if (messageContent.trim() === '') return;

        $.ajax({
            url: '{{ route('chats.sendMessage') }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                chat_id: chatId,
                message: messageContent
            },
            success: function(response) {
                // Agregar mensaje al chat
                $('.chat-messages').append(`
                    <div class="message sent" style="margin-bottom: 10px;">
                        <p><strong>${response.user.name}:</strong> ${response.message}</p>
                        <span class="text-muted" style="font-size: 0.8em;">Ahora</span>
                    </div>
                `);
                $('#message-content').val('');
                $('.chat-messages').scrollTop($('.chat-messages')[0].scrollHeight); // Desplazar al final del chat
            },
            error: function() {
                alert('Error al enviar el mensaje.');
            }
        });
    }
</script>
@endsection
<style>
    .message.sent {
        text-align: right;
        background-color: #d4f1f9;
        padding: 8px;
        border-radius: 8px;
        display: inline-block;
        max-width: 75%;
    }

    .message.received {
        text-align: left;
        background-color: #f1f0f0;
        padding: 8px;
        border-radius: 8px;
        display: inline-block;
        max-width: 75%;
    }
</style>
