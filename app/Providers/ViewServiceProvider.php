<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Componer datos para la vista
        View::composer('your-view-name', function ($view) {
            $user = auth()->user();
            $profile = request()->route('profile');

            $view->with([
                'friendship' => \App\Models\Friendship::existingFriendship($user->id, $profile->user_id),
                'friendRequest' => \App\Models\FriendRequest::existingRequest($user->id, $profile->user_id),
            ]);
        });
    }
}
