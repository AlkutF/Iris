<?php

namespace App\Models;
use App\Models\Profile;
use App\Models\Post;
use App\Models\Reaction;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\Friendship;
use App\Models\Blocking;
use App\Models\Message;
use App\Models\Role;
use App\Models\Chat;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function isAdmin()
    {
        return $this->roles()->where('name', 'admin')->exists();
    }

    public function chats()
    {
        return $this->belongsToMany(Chat::class, 'chat_user')->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class); // Relación uno a uno con el perfil
    }
    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable');
    }
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function friendships()
    {
        return $this->hasMany(Friendship::class);
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id');
    }
    public function friendRequestsReceived()
    {
        return $this->hasMany(FriendRequest::class, 'receiver_id');
    }

    // Relación de bloqueo
    public function blockings()
    {
        return $this->hasMany(Blocking::class);
    }

    public function blockedUsers()
    {
        return $this->belongsToMany(User::class, 'blockings', 'user_id', 'blocked_user_id');
    }

    public function blockedBy(User $user)
    {
        return $this->belongsToMany(User::class, 'blockings', 'blocked_user_id', 'user_id')
                    ->wherePivot('user_id', $user->id);
    }

    public function friendRequestsSent()
{
    return $this->hasMany(FriendRequest::class, 'sender_id');
}

public function groups()
{
    return $this->belongsToMany(Group::class, 'group_members', 'user_id', 'group_id')
                ->withPivot('role', 'status') // Acceso a los campos adicionales
                ->withTimestamps();
}
public function unreadNotifications()
{
    return $this->notifications()->whereNull('read_at');
}

// Verificar si ya son amigos
public function isFriendWith(User $user)
{
    return $this->friends()->where('friend_id', $user->id)->exists();
}

// Verificar si hay una solicitud pendiente de un usuario específico
public function hasPendingRequestFrom(User $user)
{
    return $this->receivedRequests()->where('sender_id', $user->id)
                                     ->where('status', 'pending')
                                     ->exists();
}

// Relaciones en el modelo User
public function sentRequests()
{
    return $this->hasMany(Friendship::class, 'user_id');
}

public function receivedRequests()
{
    return $this->hasMany(Friendship::class, 'friend_id');
}



    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'external_id',
        'external_auth',
        'banned_at', 
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'banned_at' => 'datetime', 
    ];
}
