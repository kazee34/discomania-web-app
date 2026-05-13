<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Src\admin\product\domain\repositories\ProductRepositoryInterface;
use Src\admin\product\infrastructure\repositories\EloquentProductRepository;
use Src\admin\user\domain\repositories\AdminRepositoryInterface;
use Src\admin\user\infrastructure\events\LaravelEventPublisher;
use Src\admin\user\infrastructure\repositories\EloquentAdminRepository;
use Src\admin\user\infrastructure\repositories\EloquentUserRepository;
use Src\shared\domain\repositories\EventPublisher;
use Src\shared\domain\repositories\UserRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            EloquentUserRepository::class
        );

        $this->app->bind(
            EventPublisher::class,
            LaravelEventPublisher::class
        );

        $this->app->bind(
            AdminRepositoryInterface::class,
            EloquentAdminRepository::class
        );

        $this->app->bind(
            ProductRepositoryInterface::class,
            EloquentProductRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
