<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Post\CommentController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RelacionUsuarios\FriendshipController;
use App\Http\Controllers\RelacionUsuarios\BlockingController;
use App\Http\Controllers\RelacionUsuarios\FriendRequestController;
use App\Http\Controllers\user\ProfileController;
use App\Http\Controllers\Story\StoryController;
use App\Http\Controllers\Groups\GroupController;
use App\Http\Controllers\Groups\GroupPostController;
use App\Http\Controllers\Groups\CommentGroupController;
use App\Http\Controllers\Groups\ReactionPostGroupController;
use App\Http\Controllers\Chats\ChatUserController;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\RegaloSanValentinController;
//Para recuperar contraseña
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::get('/banned', function () {
    return view('banned'); // Vista que muestra un mensaje de baneo
})->name('banned');

Route::post('groups/{group}/requestPost', [GroupPostController::class, 'requestPost'])->name('groups.requestPost');

Route::middleware(['auth', 'isAdmin'])->group(function() {
    // Ruta para el panel de administración
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // Ruta para ver usuarios
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    //Ruta para ver post 
    Route::get('/admin/users/{user}/posts', [AdminController::class, 'viewUserPosts'])->name('admin.users.posts');
    // Ruta para banear/desbanear un usuario
    Route::post('/admin/users/{user}/ban', [AdminController::class, 'banUser'])->name('admin.users.ban');
    //Ruta para eliminar post
    Route::delete('/admin/posts/{post}', [AdminController::class, 'deletePost'])->name('admin.posts.delete');
    //Ruta para crear grupos
    Route::get('groups/create', [GroupController::class, 'create'])->name('groups.create'); 
    // Ruta para ver los datos (con el nombre actualizado)
    Route::get('admin/groups', [GroupController::class, 'indexAdminGrupos'])->name('groups.index_admin_grupos');
    
// Ver solicitudes de posteos pendientes
Route::get('admin/solicitudes', [GroupController::class, 'verSolicitudes'])->name('admin.verSolicitudes');

// Permitir un post
Route::post('admin/solicitudes/permitir/{id}', [GroupController::class, 'permitirSolicitud'])->name('groups.allowPost');

// Denegar un post
Route::delete('admin/solicitudes/denegar/{id}', [GroupController::class, 'denegarSolicitud'])->name('groups.denyPost');
//Ver regalo
Route::get('/admin/regalos', [RegaloSanValentinController::class, 'index'])->name('admin.regalos');
Route::delete('/regalos/{id}', [RegaloSanValentinController::class, 'destroy'])->name('regalos.destroy');

});

    // Rutas para manejar los regalos de San Valentín
    Route::get('regalos', [RegaloSanValentinController::class, 'index'])->name('regalos.index');
    Route::get('regalos/create', [RegaloSanValentinController::class, 'create'])->name('regalos.create');
    Route::post('regalos', [RegaloSanValentinController::class, 'store'])->name('regalos.store');

Route::delete('notificaciones/{notificationId}', [NotificationController::class, 'destroy'])->name('notificaciones.destroy');
// routes/web.php

Route::get('/friend_requests', [FriendRequestController::class, 'index'])->name('friend_requests.index');
Route::post('friend_requests/accept/{id}', [FriendRequestController::class, 'accept'])->name('friend_requests.accept');
Route::post('friend_requests/reject/{id}', [FriendRequestController::class, 'reject'])->name('friend_requests.reject');
Route::delete('friendships/{id}', [FriendshipController::class, 'destroy'])->name('friendships.destroy');
//Rutas evluadas y utiles
// Rutas raiz , la misma es utilizada en muchas partes , tenlo en cuenta antes de modificarla


Route::get('/', function () {
    // Verifica si el usuario está autenticado
    if (auth()->check()) {
        // Si está autenticado, redirige a la página de inicio
        return redirect()->route('home');
    }

    // Si no está autenticado, muestra la vista welcome
    return view('welcome');
})->name('welcome');

//Ruta para crear el perfil por primera vez 
Route::get('/profile/create', [ProfileController::class, 'create'])->name('profile.create');
// Ruta para generar un nuevo post dentro de
Route::post('groups/{group}/posts', [GroupPostController::class, 'store'])->name('group.posts.store');
// Rutas para editar y eliminar publicaciones de grupo , son completamente funcionales
Route::put('groups/{group}/posts/{post}', [GroupPostController::class, 'update'])->name('groups.posts.update');
Route::get('groups/{group}/posts/{post}/edit', [GroupPostController::class, 'edit'])->name('groups.posts.edit');
Route::delete('groups/{group}/posts/{post}', [GroupPostController::class, 'destroy'])->name('groups.posts.destroy');
//Rutas para comentarios en grupos 
Route::delete('groups/{group}/comments/{commentGroup}', [CommentGroupController::class, 'destroy'])->name('groups.comments.destroy');
Route::get('groups/{group}/comments/{commentGroup}/edit', [CommentGroupController::class, 'edit'])->name('groups.comments.edit');
Route::put('groups/{group}/comments/{commentGroup}', [CommentGroupController::class, 'update'])->name('groups.comments.update');
//Rutas en creacion
//Rutas sin evaluar

Route::post('/chats/sendMessage', [ChatUserController::class, 'sendMessage'])->name('chats.sendMessage');
Route::get('/chats/getMessages/{chatId}', [ChatUserController::class, 'getMessages'])->name('chats.getMessages');

Route::get('/cerrar-sesion', [ProfileController::class, 'cerrarSesion'])->name('closesesion');
Route::get('/home', [PostController::class, 'index'])->name('home');
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/amistades', [ProfileController::class, 'indexAmistades'])->name('user.amistades');
// Rutas de autenticación
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.submit');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register'])->name('register.submit');

// Rutas de publicaciones
Route::resource('posts', PostController::class);
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/posts/{post}/react', [PostController::class, 'react'])->name('posts.react');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/posts/{post}/comments/load-more', [CommentController::class, 'loadMore']);

// Rutas de reacciones
Route::post('/posts/{post}/reactions', [ReactionController::class, 'store'])->name('reactions.store');
Route::get('/posts/{post}/reactions', [ReactionController::class, 'index'])->name('reactions.index');

// Rutas de notificaciones
Route::get('/notificaciones/user', [NotificationController::class, 'index'])->name('notificaciones.user');
Route::patch('/notificaciones/{id}/read', [NotificationController::class, 'markAsRead'])->name('notificaciones.markAsRead');
Route::get('/notificaciones/redirigir/{postId}/{notificationId}', [NotificationController::class, 'redirectToPost'])->name('notificaciones.redirect');
Route::get('/notifications/unread', [NotificationController::class, 'getUnreadNotifications'])->name('notifications.unread');
Route::delete('/notificaciones/{id}', [NotificationController::class, 'destroy'])->name('notificaciones.destroy');

// Rutas de relaciones entre usuarios
Route::resource('friendships', FriendshipController::class);
Route::resource('blockings', BlockingController::class);
Route::post('/block/{userId}', [BlockingController::class, 'blockUser'])->name('user.block');
Route::post('/unblock/{userId}', [BlockingController::class, 'unblockUser'])->name('user.unblock');
Route::post('/friendship/{user_id}/send', [FriendRequestController::class, 'sendRequest'])->name('friendship.sendRequest');
Route::post('friendship/{receiverId}/send', [FriendRequestController::class, 'sendRequest'])->name('friendship.send');  // duplicado, evalúa si es necesario
Route::post('friendship/{senderId}/accept', [FriendRequestController::class, 'acceptRequest'])->name('friendship.accept');
Route::post('friendship/{senderId}/reject', [FriendRequestController::class, 'rejectRequest'])->name('friendship.reject');
Route::delete('friendship/{receiverId}/cancel', [FriendRequestController::class, 'cancelRequest'])->name('friendship.cancel');
Route::delete('/friend-requests/{id}', [FriendRequestController::class, 'destroy'])->name('friend_requests.destroy');
Route::get('/friends', [FriendController::class, 'index'])->name('friends.index');

// Rutas para comentarios
Route::get('comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
//Rutas para visualizar perfil
Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
//Ruta para visualizar amigos
Route::get('/amistades', [ProfileController::class, 'indexAmistades'])->name('amistades.index');
// Rutas protegidas por autenticación para crear historias 
Route::middleware(['auth'])->group(function () {
    
    Route::get('/historias', [StoryController::class, 'index'])->name('stories.index');
    Route::get('/historias/crear', [StoryController::class, 'create'])->name('stories.create');
    Route::post('/historias', [StoryController::class, 'store'])->name('stories.store');
});;
//Ruta para ver historia individual
Route::get('/stories/{id}', [StoryController::class, 'show'])->name('stories.show');
Route::post('/stories/{story}/reactions', [StoryController::class, 'reactToStory'])->name('stories.reactions');



// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Perfil de usuario
  
    Route::post('/profile/store', [ProfileController::class, 'store'])->name('profile.store');
    Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/{id}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{id}', [ProfileController::class, 'update'])->name('profile.update');

    // Rutas de relaciones (amigos, bloqueos)
    Route::post('/user/{userId}/block', [BlockingController::class, 'blockUser'])->name('block.block');
    Route::post('/user/{userId}/unblock', [BlockingController::class, 'unblockUser'])->name('block.unblock');
    Route::resource('friend_requests', FriendRequestController::class)->only(['store', 'update', 'destroy']);
});

//En teoria esto sera lo de grupos, digo sera porque no se si se va a usar
Route::post('/groups/{group}/join', [GroupController::class, 'join'])->name('groups.join');
Route::post('groups/{group}/accept/{user}', [GroupController::class, 'accept'])->name('groups.accept');
Route::post('groups/{group}/reject/{user}', [GroupController::class, 'reject'])->name('groups.reject');
//Para entrar al area de grupos , medida temporal
Route::get('groups', [GroupController::class, 'index'])->name('groups.index');
Route::get('groups/{group}', [GroupController::class, 'show'])->name('groups.show');
Route::delete('groups/{group}/request', [GroupController::class, 'destroy_request'])->name('groups.destroy.request');
Route::delete('groups/{group}/leave', [GroupController::class, 'destroy'])->name('groups.leave');
//Rutas protegidas por autenticación para crear grupos
Route::middleware(['auth'])->group(function () {
    Route::post('groups', [GroupController::class, 'store'])->name('groups.store');
        Route::get('groups/{group}/edit', [GroupController::class, 'edit'])->name('groups.edit');
        Route::put('groups/{group}', [GroupController::class, 'update'])->name('groups.update');
        Route::delete('groups/{group}/destroy', [GroupController::class, 'destroyGroup'])->name('groups.destroyGroup');

});
//Ruta para promover a admin
Route::post('groups/{group}/promote-to-admin', [GroupController::class, 'promoteToAdmin'])->name('groups.promote.to.admin');
Route::delete('groups/{group}/remove-member', [GroupController::class, 'removeMember'])->name('groups.remove.member');
// Rebajar a Miembro
Route::post('groups/{group}/demote-to-member', [GroupController::class, 'demoteToMember'])->name('groups.demote.to.member');
//Ruta para aceptar solicitud de grupo
Route::post('groups/{group}/accept-request', [GroupController::class, 'acceptRequest'])->name('groups.accept.request');
//Ruta para rechazar solicitud de grupo
Route::post('groups/{group}/reject-request', [GroupController::class, 'rejectRequest'])->name('groups.reject.request');
Route::get('groups/{group}/edit', [GroupController::class, 'edit'])->name('groups.edit');
Route::put('groups/{group}', [GroupController::class, 'update'])->name('groups.update');
Route::delete('groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');

// Ruta para mostrar los posts del grupo y la vista de creación de post
Route::get('groups/{group}/posts', [GroupPostController::class, 'index'])->name('group.posts.index');

// Ruta para mostrar el formulario de creación
Route::get('groups/{group}/posts/create', [GroupPostController::class, 'create'])->name('group.posts.create');



Route::post('groups/{group}/posts/{post}/comment', [CommentGroupController::class, 'store'])->name('groups.posts.addComment');

Route::post('groups/posts/{post}/react', [ReactionPostGroupController::class, 'store'])->name('groups.posts.react');
// Ruta para generar reacciones a los posts de grupo
Route::post('groups/{group}/posts/{post}/reactions', [ReactionPostGroupController::class, 'store'])->name('groups.reactions.store');
Route::post('groups/{group}/posts/{post}/addReaction', [GroupPostController::class, 'addReaction'])->name('groups.posts.addReaction');


// Ruta para redirigir al usuario a Google
Route::get('/login-google', [LoginController::class, 'redirectToGoogle'])->name('login.google');

// Ruta para manejar el callback de Google
Route::get('/google-callback', [LoginController::class, 'handleGoogleCallback']);
//Microsoft
Route::get('login/microsoft', [LoginController::class, 'redirectToProvider'])->name('login.microsoft');
Route::get('login/microsoft/callback', [LoginController::class, 'handleProviderCallback']);

//para el recuperar contraseña
// Rutas para la recuperación de contraseñas
Route::get('forgot-password', [ForgotPasswordController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [ForgotPasswordController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [ResetPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [ResetPasswordController::class, 'store'])
                ->name('password.store');

    Route::get('/ruta-para-cargar-mas-historias', [StoryController::class, 'loadMoreStories']);
// Enviar solicitud de amistad
Route::post('friend_requests', [FriendRequestController::class, 'store'])->name('friend_requests.store');

// Aceptar solicitud de amistad
Route::post('friend_requests/accept/{userId}', [FriendRequestController::class, 'update'])->name('friend_requests.accept');

// Rechazar solicitud de amistad
Route::post('friend_requests/reject/{senderId}', [FriendRequestController::class, 'rejectRequest'])->name('friend_requests.reject');

// Cancelar solicitud de amistad
Route::delete('friend_requests/{id}', [FriendRequestController::class, 'destroy'])->name('friend_requests.destroy');

Route::get('/chats', [ChatUserController::class, 'index'])->name('chats.index');
Route::get('/chats/{chat}', [ChatUserController::class, 'show'])->name('chats.show'); // Mostrar el chat con AJAX
Route::post('/chats/{chat}/message', [ChatUserController::class, 'storeMessage'])->name('chats.storeMessage');
Route::post('chats/{chatId}/message', [ChatController::class, 'sendMessage'])->name('chats.sendMessage');
Route::post('/chats/{friendId}', [ChatUserController::class, 'createChat'])->name('chats.create');
Route::get('/chats/{chat}', [ChatUserController::class, 'show'])->name('chats.show');
Route::post('/chats/{friendId}', [ChatUserController::class, 'create'])->name('chats.create');
