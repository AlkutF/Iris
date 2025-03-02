@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Comentario</h1>
    <form action="{{ route('groups.comments.update', ['group' => $group->id, 'commentGroup' => $commentGroup->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="content" class="form-label">Contenido</label>
            <textarea name="content" id="content" class="form-control" rows="4">{{ old('content', $commentGroup->content) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>
@endsection
