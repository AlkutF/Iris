@extends('layouts.app')
@include('components.navbar')

@section('content')
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <!-- Imagen de la historia -->
                        <div class="story-image-container">
                            <img src="{{ asset('storage/' . $story->media) }}" alt="Story Image" class="img-fluid story-image">
                        </div>
                        
                        <p class="mt-3"><strong>Creado por:</strong> {{ $story->user->name }}</p>
                        <p><strong>Fecha de creación:</strong> {{ $story->created_at->format('d M Y, H:i') }}</p>
                        <p><strong>Última actualización:</strong> {{ $story->updated_at->format('d M Y, H:i') }}</p>

                        <!-- Reacciones -->
                        <div class="reactions mb-3">
                            <button type="button" class="btn btn-sm btn-outline-primary react-btn" 
                                    data-reaction-type="like" data-story-id="{{ $story->id }}">
                                👍 Like <span class="badge badge-light" id="like-count-{{ $story->id }}">{{ $story->reactions()->where('reaction_type', 'like')->count() }}</span>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger react-btn" 
                                    data-reaction-type="love" data-story-id="{{ $story->id }}">
                                ❤️ Love <span class="badge badge-light" id="love-count-{{ $story->id }}">{{ $story->reactions()->where('reaction_type', 'love')->count() }}</span>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-warning react-btn" 
                                    data-reaction-type="surprise" data-story-id="{{ $story->id }}">
                                😲 Surprise <span class="badge badge-light" id="surprise-count-{{ $story->id }}">{{ $story->reactions()->where('reaction_type', 'surprise')->count() }}</span>
                            </button>
                        </div>

                        <!-- Botones de navegación -->
                        <div class="story-navigation text-center">
                            @if($previousStory)
                                <a href="{{ route('stories.show', ['id' => $previousStory->id]) }}" class="btn btn-secondary" style="border-radius: 50%;">&laquo; Anterior</a>
                            @endif
                            
                            @if($nextStory)
                                <a href="{{ route('stories.show', ['id' => $nextStory->id]) }}" class="btn btn-secondary" style="border-radius: 50%;">Siguiente &raquo;</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        console.log('Document ready');
        $('.react-btn').on('click', function () {
            console.log('Reaction button clicked');
            var reactionType = $(this).data('reaction-type');
            var storyId = $(this).data('story-id');
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: '/stories/' + storyId + '/reactions',
                method: 'POST',
                data: {
                    _token: _token,
                    reaction_type: reactionType,
                },
                success: function (response) {
                    console.log('Success:', response);
                    $('#love-count-' + storyId).text(response.love_count);
                    $('#surprise-count-' + storyId).text(response.surprise_count);
                    $('#like-count-' + storyId).text(response.like_count);
                },
                error: function (error) {
                    console.log('Error:', error);
                }
            });
        });
    });
</script>
@endsection

<!-- Estilos adicionales -->
@section('styles')
<style>
    /* Estilo de la tarjeta de la historia */
    .card {
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .story-image-container {
        overflow: hidden;
        border-radius: 10px;
        margin-bottom: 15px;
    }

    .story-image {
        width: 100%;
        height: auto;
        border-radius: 10px;
    }

    .reactions {
        display: flex;
        justify-content: space-around;
        margin-top: 20px;
    }

    .reactions button {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .story-navigation {
        margin-top: 20px;
    }

    .story-navigation .btn {
        margin: 0 10px;
        padding: 10px 20px;
        font-size: 14px;
    }

    .story-navigation .btn:hover {
        background-color: #007bff;
        color: white;
    }
</style>
@endsection

