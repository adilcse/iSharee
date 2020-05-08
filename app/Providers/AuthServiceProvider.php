<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        Gate::define('update-post', function ($user, $post) {
            if($user->is_admin){
                return true;
            }else if(0 === $user->id){
                return false;   
            }
            return $user->id === $post->user_id;
        });
        Gate::define('update-comment', function ($user, $commentUser) {
            if($user->is_admin){
                return true;
            }else if(0 === $user->id){
                return false;   
            }
            return $user->id === $commentUser->id;
        });
    }
}
