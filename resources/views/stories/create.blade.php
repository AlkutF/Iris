<form action="{{ route('stories.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="media" class="form-label">Selecciona una historia</label>
        <input type="file" class="form-control" name="media" id="media" required>
    </div>
    <div class="mb-3">
        <label for="text" class="form-label">Texto (opcional)</label>
        <textarea class="form-control" name="text" id="text" rows="3" placeholder="Agrega un mensaje o texto a tu historia"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Subir Historia</button>
</form>
