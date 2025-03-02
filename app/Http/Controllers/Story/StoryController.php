<?php

namespace App\Http\Controllers\Story;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\Reaction;
use App\Models\StoryReaction;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Support\Facades\Log;

class StoryController extends Controller
{
    public function create()
    {
        return view('stories.create');  // Asegúrate de tener la vista 'create.blade.php'
    }
    public function loadMoreStories(Request $request)
{
    $stories = Story::orderBy('created_at', 'desc')
                    ->paginate(4, ['*'], 'page', $request->page ?? 1); // Paginación de 4 historias

    $hasMoreStories = $stories->hasMorePages();

    return response()->json([
        'stories' => view('components.story-list', compact('stories'))->render(),
        'has_more' => $hasMoreStories
    ]);
}

    public function store(Request $request)
    {
        // Validación de los datos del request
        $request->validate([
            'media' => 'required|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:10240', // Validación
            'text' => 'nullable|string|max:255',
        ]);
        
        // Verificar si el archivo fue correctamente subido
        if (!$request->hasFile('media') || !$request->file('media')->isValid()) {
            Log::error('No se ha subido un archivo válido o el archivo no es válido.');
            return back()->withErrors(['media' => 'El archivo no es válido o no fue subido correctamente.']);
        }
        
        $file = $request->file('media');
        $originalExtension = $file->getClientOriginalExtension();
        Log::info("Extensión del archivo: {$originalExtension}");
        
        // Procesar las imágenes
        if (in_array($originalExtension, ['jpg', 'jpeg', 'png'])) {
            $image = Image::make($file)->encode('webp', 80);
            
            // Generar un nombre único para la imagen
            $newFilename = uniqid() . '.webp';
            $mediaPath = 'stories/' . $newFilename;  // Guardar en posts_media
            
            // Mover la imagen al directorio deseado
            try {
                // Guardar la imagen en public/storage/posts_media
                $image->save(public_path('storage/' . $mediaPath));
                Log::info("Imagen guardada exitosamente en: {$mediaPath}");
            } catch (\Exception $e) {
                Log::error("Error al guardar la imagen: " . $e->getMessage());
                return back()->withErrors(['media' => 'Error al guardar la imagen.']);
            }
        } 
        // Procesar los videos
        elseif (in_array($originalExtension, ['mp4', 'mov', 'avi'])) {
            Log::info("Procesando video: {$file->getClientOriginalName()}");
            
            // Generar un nombre único para el video
            $newFilename = uniqid() . '.' . $originalExtension;
            $mediaPath = 'stories/' . $newFilename;  // Guardar en posts_media
            
            // Mover el video al directorio deseado
            try {
                // Mover el archivo al directorio public/storage/posts_media
                $file->move(public_path('storage/stories'), $newFilename);
                Log::info("Video guardado exitosamente en: {$mediaPath}");
            } catch (\Exception $e) {
                Log::error("Error al procesar el video: " . $e->getMessage());
                return back()->withErrors(['media' => 'Error al procesar el video.']);
            }
        } 
        else {
            Log::error("Formato de archivo no soportado: {$originalExtension}");
            return back()->withErrors(['media' => 'Formato no soportado.']);
        }
        
        // Crear la historia
        try {
            Story::create([
                'user_id' => auth()->id(),
                'media' => $mediaPath, // Guardar la ruta del archivo
                'text' => $request->input('text'),
            ]);
            Log::info('Historia creada exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al crear la historia: ' . $e->getMessage());
            return back()->withErrors(['media' => 'Error al crear la historia.']);
        }
    
        // Redireccionar con éxito
        return redirect()->route('home')->with('success', 'Historia publicada.');
    }
    
    public function show($id)
    {
        $story = Story::findOrFail($id);
        
        // Historia anterior
        $previousStory = Story::where('id', '<', $story->id)->latest()->first();
        
        // Historia siguiente
        $nextStory = Story::where('id', '>', $story->id)->oldest()->first();
        
        return view('stories.show', compact('story', 'previousStory', 'nextStory'));
    }

    public function reactToStory(Request $request, $id)
    {
        $story = Story::findOrFail($id);
        $reactionType = $request->input('reaction_type');
        $reaction = StoryReaction::updateOrCreate(
            ['user_id' => auth()->id(), 'story_id' => $story->id],
            ['reaction_type' => $reactionType]
        );
        $likeCount = $story->reactions()->where('reaction_type', 'like')->count();
        $loveCount = $story->reactions()->where('reaction_type', 'love')->count();
        $surpriseCount = $story->reactions()->where('reaction_type', 'surprise')->count();
        return response()->json([
            'like_count' => $likeCount,
            'love_count' => $loveCount,
            'surprise_count' => $surpriseCount,
        ]);
    }

}