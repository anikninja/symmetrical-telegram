<?php

namespace App\Providers;

use App\Services\UserService;
use Laravel\Passport\Passport;
use App\Repositories\AuthRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\UserServiceInterface;
use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\FolderRepositoryInterface;
use App\Repositories\FolderRepository;
use App\Interfaces\NoteRepositoryInterface;
use App\Repositories\NoteRepository;
use App\Interfaces\LibraryRepositoryInterface;
use App\Repositories\LibraryRepository;
use App\Interfaces\BookmarkRepositoryInterface;
use App\Repositories\BookmarkRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(FolderRepositoryInterface::class, FolderRepository::class);
        $this->app->bind(NoteRepositoryInterface::class, NoteRepository::class);
        $this->app->bind(LibraryRepositoryInterface::class, LibraryRepository::class);
        $this->app->bind(BookmarkRepositoryInterface::class, BookmarkRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::loadKeysFrom(__DIR__.'/../secrets/oauth');
    }
}
