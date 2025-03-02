<ul class="list-group mb-3">
    @forelse ($unreadNotifications as $notification)
        @php
            $data = $notification->data;
            $notificationType = $data['type'] ?? null;
            $timeAgo = $notification->created_at->diffForHumans();
            $isFriendRequest = $notificationType === 'friend_request';
        @endphp
        @if($isFriendRequest)
            <form action="{{ route('notificaciones.destroy', $notification->id) }}" method="POST" class="w-100" id="form-{{ $notification->id }}">
                @csrf
                @method('DELETE')

                <li class="list-group-item d-flex justify-content-between align-items-center p-3 clickable-notification"
                    onclick="handleClick('{{ $notification->id }}', '{{ route('profile.show', $data['sender_id']) }}');">
                    <div class="d-flex align-items-center">
                        <!-- Ícono para solicitud de amistad -->
                        <div class="icon-container text-white rounded-circle me-3 p-2 bg-primary">
                            <i class="bi bi-person-plus fs-5"></i>
                        </div>

                        <div>
                            <strong>{{ $data['message'] }}</strong>
                            <small class="text-muted d-block">{{ $timeAgo }}</small>
                        </div>
                    </div>
                </li>
            </form>
        @endif
    @empty
        <li class="list-group-item text-center">No tienes notificaciones sin leer.</li>
    @endforelse
</ul>

<style>
    .clickable-notification {
        cursor: pointer;
    }
</style>

<script>
function handleClick(notificationId, profileUrl) {
    console.log('Formulario a enviar:', 'form-' + notificationId);
    document.getElementById('form-' + notificationId).submit();
    console.log('Redirigiendo a:', profileUrl);
    setTimeout(function() {
        window.location = profileUrl;
    }, 300);
}
</script>
