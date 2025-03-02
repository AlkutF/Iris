@extends('layouts.app')
@include('components.navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h1 class="h1-container">Chats</h1>
        </div>
        <div class="card-body">
            <div id="chat-container" class="d-flex flex-column flex-md-row" style="height: 70vh;">
                <!-- Lista de Chats -->
                <div id="chat-list" class="col-12 col-md-3 overflow-auto border-end p-3" style="max-height: 100%; overflow-y: auto;">
                    <input type="text" id="search-chat" class="form-control mb-3" placeholder="Buscar por nombre">   

                    @if ($chats->isEmpty() && $friendsWithoutChat->isEmpty())
                        <div class="text-center text-muted mt-3">
                            <p>No tienes chats ni amigos disponibles.</p>
                        </div>
                    @else
                        @php
                            $loadedFriends = [];  // Array para almacenar los IDs de los usuarios cargados
                        @endphp

                        @foreach ($chats as $chat)
    @php
        $chatUser = $chat->users->where('id', '!=', auth()->id())->first();  // Obtener el usuario con el que se tiene el chat
    @endphp
    <div class="chat-preview mb-3" data-chat-id="{{ $chat->id }}" data-chat-name="{{ $chatUser->profile->nombre_perfil }}">
        <button class="chat-link d-block w-100 text-start btn btn-light p-3">
            <div class="d-flex align-items-center">
                <!-- Imagen de perfil del usuario -->
                <img src="{{ asset('storage/' . $chatUser->profile->avatar) }}" alt="User image" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px;">
                
                <!-- Nombre del usuario del chat -->
                <span>{{ $chatUser->profile->nombre_perfil }}</span>
            </div>
            
            <!-- Último mensaje -->
            @if ($chat->messages->isNotEmpty())
                <div class="d-flex justify-content-between">
                    <small class="text-muted">
                        Último mensaje de {{ $chat->messages->last()->user->profile->nombre_perfil }}: 
                        {{ Str::limit($chat->messages->last()->content, 30) }} <!-- Mostrar los primeros 30 caracteres -->
                    </small>
                </div>
            @else
                <div class="d-flex justify-content-between">
                    <small class="text-muted">No hay mensajes aún</small>
                </div>
            @endif
        </button>
    </div>
@endforeach


                        @foreach ($friendsWithoutChat as $friend)
                            @if (!in_array($friend->id, $loadedFriends)) <!-- Verificar si el amigo ya ha sido cargado -->
                                <div class="chat-preview mb-3" data-friend-id="{{ $friend->id }}" data-friend-name="{{ $friend->profile->nombre_perfil  }}">
                                    <button class="chat-link d-block w-100 text-start btn btn-light p-3">
                                        <div class="d-flex align-items-center">
                                            <!-- Imagen de perfil del amigo -->
                                            <img src="{{ asset($friend->profile_picture) }}" alt="Friend image" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px;">
                                            <!-- Nombre del amigo -->
                                            <span>{{ $friend->profile->nombre_perfil }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Iniciar chat</small>
                                        </div>
                                    </button>
                                </div>
                                @php
                                    $loadedFriends[] = $friend->id;  // Agregar el ID del amigo al array
                                @endphp
                            @endif
                        @endforeach
                    @endif
                </div>

                <!-- Contenedor del Chat -->
                <div id="chat-content" class="col-12 col-md-9 d-flex flex-column p-3" style="max-height: 100%; display: none;">
    <!-- Encabezado del chat con la imagen y el nombre del usuario -->
    <div id="chat-header" class="d-flex align-items-center mb-3">
        <img id="chat-user-avatar" src="{{ asset('storage/assets/default.webp')}}" alt="User image" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px;">
        <h5 id="chat-user-name" class="mb-0"></h5>
    </div>

    <!-- Contenedor de mensajes -->
    <div id="messages-container" class="flex-grow-1 overflow-auto" style="max-height: 80vh; overflow-y: auto;">
        <!-- Mensaje inicial si no hay chats abiertos -->
        <div id="no-chat-message" class="text-center text-muted mt-3">
            <p>Haz clic en un usuario para cargar los chats.</p>
        </div>
    </div>

    <!-- Formulario de Mensaje -->
    <div id="message-form-container">
        <form id="message-form" class="mt-3">
            <div class="input-group">
                <textarea name="content" class="form-control" required placeholder="Escribe tu mensaje" rows="3"></textarea>
                <button type="submit" class="btn btn-send">
                    <i class="bi bi-send"></i> Enviar
                </button>
            </div>
        </form>
    </div>
</div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var chatId = null; // Variable para almacenar el ID del chat activo
    var messageUpdateInterval = 3000; // Intervalo de actualización en milisegundos (3 segundos)

    // Al hacer clic en un chat existente, cargar el chat dinámicamente
    $('.chat-preview').on('click', function() {
        chatId = $(this).data('chat-id');
        var friendId = $(this).data('friend-id'); // Obtener el ID del amigo para crear el chat si es necesario

        console.log('Chat ID seleccionado: ', chatId); // Depuración

        // Si es un amigo sin chat, crearlo
        if (friendId) {
            $.ajax({
                url: '{{ route("chats.create", ":friendId") }}'.replace(':friendId', friendId),
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    console.log('Respuesta al crear chat: ', response); // Depuración
                    // Redirigir al chat recién creado
                    window.location.href = '{{ route("chats.index") }}';
                }
            });
        } else {
            // Obtener la información del usuario con el que se está chateando
            var chatUserAvatar = $(this).find('img').attr('src');
            var chatUserName = $(this).data('chat-name');

            // Actualizar el encabezado del chat con la imagen y el nombre del usuario
            $('#chat-user-avatar').attr('src', chatUserAvatar);
            $('#chat-user-name').text(chatUserName);

            // Ocultar el mensaje inicial si no hay chats abiertos
            $('#no-chat-message').hide();

            // Hacer la solicitud AJAX para cargar los mensajes del chat
            $.ajax({
                url: '{{ route("chats.getMessages", ":chatId") }}'.replace(':chatId', chatId),
                method: 'GET',
                success: function(response) {
                    console.log('Respuestas de los mensajes: ', response); // Depuración
                    var messagesContainer = $('#messages-container');
                    messagesContainer.html(''); // Limpiar los mensajes anteriores

                    // Procesar los mensajes de la respuesta
                    if (response.messages && Array.isArray(response.messages)) {
                        $.each(response.messages, function(index, message) {
                            messagesContainer.append('<p><strong>' + message.user.profile.nombre_perfil +  ':</strong> ' + message.content + '</p>');
                        });

                        // Asegurarse de que los mensajes más recientes siempre estén visibles
                        messagesContainer.scrollTop(messagesContainer[0].scrollHeight);
                    }

                    // Mostrar el contenedor del chat
                    $('#chat-content').show().data('chat-id', chatId);

                    // Iniciar la actualización automática de los mensajes
                    startMessageAutoUpdate();
                }
            });
        }
    });

    // Función para actualizar los mensajes del chat en tiempo real
    function startMessageAutoUpdate() {
        // Si ya hay un intervalo activo, lo limpiamos primero
        if (window.messageUpdateIntervalId) {
            clearInterval(window.messageUpdateIntervalId);
        }

        // Iniciar un intervalo para actualizar los mensajes cada 3 segundos
        window.messageUpdateIntervalId = setInterval(function() {
            if (chatId) {
                $.ajax({
                    url: '{{ route("chats.getMessages", ":chatId") }}'.replace(':chatId', chatId),
                    method: 'GET',
                    success: function(response) {
                        console.log('Actualización de mensajes: ', response); // Depuración
                        var messagesContainer = $('#messages-container');
                        var lastMessage = messagesContainer.find('p').last();

                        // Verificar si hay nuevos mensajes
                        if (response.messages && Array.isArray(response.messages)) {
                            var latestMessage = response.messages[response.messages.length - 1];

                            // Si el último mensaje recibido es diferente al último mensaje mostrado
                            if (lastMessage.length === 0 || lastMessage.text() !== latestMessage.user.profile.nombre_perfil + ': ' + latestMessage.content) {
                                // Agregar el nuevo mensaje
                                messagesContainer.append('<p><strong>' + latestMessage.user.profile.nombre_perfil + ':</strong> ' + latestMessage.content + '</p>');
                                messagesContainer.scrollTop(messagesContainer[0].scrollHeight);
                            }
                        }
                    }
                });
            }
        }, messageUpdateInterval); // 3000ms = 3 segundos
    }

    // Enviar el mensaje del chat mediante AJAX
    $('#message-form').on('submit', function(e) {
        e.preventDefault();

        var form = $(this);
        var messageContent = form.find('textarea[name="content"]').val();
        var chatId = $('#chat-content').data('chat-id');

        if (!chatId) {
            console.error('Chat ID no está definido');
            return;
        }

        console.log('Enviando mensaje a chat ID: ', chatId); // Depuración

        $.ajax({
            url: '{{ url('chats') }}/' + chatId + '/message',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                content: messageContent
            },
            success: function(response) {
                console.log('Respuesta al enviar mensaje: ', response); // Depuración
                if (response.success) {
                    var newMessage = '<p><strong>' + response.user.nombre_perfil + ':</strong> ' + response.message + '</p>';
                    $('#messages-container').append(newMessage);
                    $('#messages-container').scrollTop($('#messages-container')[0].scrollHeight);
                    form.find('textarea').val('');
                }
            }
        });
    });

    // Filtrar chats por nombre de usuario
    $('#search-chat').on('input', function() {
        var searchTerm = $(this).val().toLowerCase();

        // Filtrar la lista de chats y amigos
        $('.chat-preview').each(function() {
            var chatName = $(this).data('chat-name') ? $(this).data('chat-name').toLowerCase() : $(this).data('friend-name').toLowerCase();

            if (chatName.indexOf(searchTerm) !== -1) {
                $(this).show(); // Mostrar si coincide con el término de búsqueda
            } else {
                $(this).hide(); // Ocultar si no coincide
            }
        });
    });
});
</script>
<style>
    @media  (max-width: 768px) {
        .chat-preview {
            padding: 10px;
        }
        
        .card-body{
            min-height: 140vh;
        }
        }
</style>