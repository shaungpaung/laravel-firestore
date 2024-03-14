<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Google\Cloud\Firestore\FirestoreClient;

class FirestoreServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton('firebase.firestore', function ($app) {
            $config = [
                'keyFilePath' => env('FIREBASE_CREDENTIALS'),
            ];
            return new FirestoreClient($config);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}