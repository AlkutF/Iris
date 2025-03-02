<div class="story">
    <a href="{{ route('stories.show', $story->id)}} font-color">
    <div class="story" style="background-image: url('{{ asset('storage/' . $story->media) }}');">
        <div class="story-overlay">
            <p class="story-user-name">{{ $story->user->name }}</p>
        </div>
    </div>
    </a>
</div>
<style>
    .story {
    background-size: cover; /* Ajusta la imagen al tamaño del div */
    width: 100px;
    height: 150px;
    background-position: center; /* Centra la imagen en el div */
    border-radius: 10px; /* Bordes redondeados */
    position: relative; /* Para posicionar elementos encima */
    margin-right: 10px; /* Espacio entre historias */
    overflow: hidden; /* Asegura que el contenido no se desborde */
}

.font-color{
    color :var(--tertiary-color);
}

.story::before {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.story::after {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: inherit; 
    filter: blur(10px); 
    z-index: 0; 
}

.story-overlay {
    position: absolute;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6); /* Fondo oscuro semitransparente */
    color: white;
    width: 100%;
    text-align: center;
    padding: 5px 0;
    z-index: 2; /* Encima del difuminado */
}

.story-user-name {
    font-size: 12px;
    font-weight: bold;
    margin: 0;
}
</style>