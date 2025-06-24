<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Mencegah pembuatan user baru jika sudah ada admin
        User::creating(function ($user) {
            if (User::count() > 0) {
                return false;
            }
            
            // Set role admin untuk user pertama
            $user->role = 'admin';
            return true;
        });

        // Mencegah penghapusan admin utama
        User::deleting(function ($user) {
            if ($user->role === 'admin') {
                return false;
            }
            return true;
        });
        Paginator::useTailwind();
    }
}