<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Src\admin\customer\domain\repositories\AdminCustomerRepositoryInterface;
use Src\admin\customer\infrastructure\repositories\EloquentAdminCustomerRepository;
use Src\admin\product\domain\repositories\ProductRepositoryInterface as AdminProductRepositoryInterface;
use Src\admin\product\infrastructure\repositories\EloquentProductRepository as AdminEloquentProductRepository;
use Src\admin\user\domain\repositories\AdminRepositoryInterface;
use Src\admin\user\infrastructure\events\LaravelEventPublisher;
use Src\admin\user\infrastructure\repositories\EloquentAdminRepository;
use Src\admin\user\infrastructure\repositories\EloquentUserRepository;
use Src\customer\cart\domain\repositories\CartRepositoryInterface;
use Src\customer\cart\infrastructure\repositories\EloquentCartRepository;
use Src\customer\order\domain\repositories\OrderRepositoryInterface;
use Src\customer\order\infrastructure\repositories\EloquentOrderRepository;
use Src\customer\payment\domain\repositories\OrderPaymentRepositoryInterface;
use Src\customer\payment\domain\repositories\PaymentMethodRepositoryInterface;
use Src\customer\payment\infrastructure\repositories\EloquentOrderPaymentRepository;
use Src\customer\payment\infrastructure\repositories\EloquentPaymentMethodRepository;
use Src\customer\product\domain\repositories\ProductRepositoryInterface as CustomerProductRepositoryInterface;
use Src\customer\product\infrastructure\repositories\EloquentProductRepository as CustomerEloquentProductRepository;
use Src\customer\user\domain\repositories\CustomerRepositoryInterface;
use Src\customer\user\infrastructure\repositories\EloquentCustomerRepository;
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
            AdminProductRepositoryInterface::class,
            AdminEloquentProductRepository::class
        );

        $this->app->bind(
            CustomerProductRepositoryInterface::class,
            CustomerEloquentProductRepository::class
        );

        $this->app->bind(
            CartRepositoryInterface::class,
            EloquentCartRepository::class
        );

        $this->app->bind(
            OrderRepositoryInterface::class,
            EloquentOrderRepository::class
        );

        $this->app->bind(
            CustomerRepositoryInterface::class,
            EloquentCustomerRepository::class
        );

        $this->app->bind(
            AdminCustomerRepositoryInterface::class,
            EloquentAdminCustomerRepository::class
        );

        $this->app->bind(
            PaymentMethodRepositoryInterface::class,
            EloquentPaymentMethodRepository::class
        );

        $this->app->bind(
            OrderPaymentRepositoryInterface::class,
            EloquentOrderPaymentRepository::class
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
