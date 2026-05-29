<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;
use Src\admin\customer\application\listeners\LogoutUserOnCustomerDeactivatedListener;
use Src\admin\user\application\listeners\CreateAdminOnUserCreatedListener;
use Src\admin\user\application\listeners\DeleteUserOnAdminDeletedListener;
use Src\admin\user\application\listeners\LogoutUserOnAdminDeactivatedListener;
use Src\admin\user\domain\events\AdminDeactivatedEvent;
use Src\admin\user\domain\events\AdminDeletedEvent;
use Src\customer\order\application\listeners\SendOrderConfirmationEmailOnOrderCreatedListener;
use Src\customer\order\application\listeners\SendOrderStatusUpdateEmailListener;
use Src\customer\order\domain\events\OrderCancelledEvent;
use Src\customer\order\domain\events\OrderCreatedEvent;
use Src\customer\order\domain\events\OrderStatusUpdatedEvent;
use Src\customer\payment\application\listeners\OrderPaymentRefundOnCancelledOrderListener;
use Src\customer\product\application\listeners\RestoreProductStockOnOrderCancelledListener;
use Src\customer\user\application\listeners\SendWelcomeEmailOnCustomerCreatedListener;
use Src\customer\user\domain\events\CustomerCreatedEvent;
use Src\customer\user\domain\events\CustomerDeactivatedEvent;
use Src\shared\domain\events\UserCreatedEvent;
use Src\admin\customer\domain\repositories\AdminCustomerRepositoryInterface;
use Src\admin\customer\infrastructure\repositories\EloquentAdminCustomerRepository;
use Src\admin\dashboard\domain\repositories\DashboardStatsRepositoryInterface;
use Src\admin\dashboard\infrastructure\repositories\EloquentDashboardStatsRepository;
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
use Src\customer\order\domain\notifications\OrderEmailNotifierInterface;
use Src\customer\order\infrastructure\notifications\LaravelOrderEmailNotifier;
use Src\customer\user\domain\notifications\CustomerEmailNotifierInterface;
use Src\customer\user\domain\repositories\CustomerRepositoryInterface;
use Src\customer\user\infrastructure\notifications\LaravelCustomerEmailNotifier;
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

        $this->app->bind(
            DashboardStatsRepositoryInterface::class,
            EloquentDashboardStatsRepository::class
        );

        $this->app->bind(
            CustomerEmailNotifierInterface::class,
            LaravelCustomerEmailNotifier::class
        );

        $this->app->bind(
            OrderEmailNotifierInterface::class,
            LaravelOrderEmailNotifier::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->registerMailTransports();
        $this->registerEventListeners();
    }

    private function registerMailTransports(): void
    {
        Mail::extend('brevo', function () {
            return (new BrevoTransportFactory)->create(
                new Dsn('brevo+api', 'default', config('services.brevo.key'))
            );
        });
    }

    private function registerEventListeners(): void
    {
        Event::listen(AdminDeletedEvent::class, DeleteUserOnAdminDeletedListener::class);
        Event::listen(AdminDeactivatedEvent::class, LogoutUserOnAdminDeactivatedListener::class);
        Event::listen(CustomerDeactivatedEvent::class, LogoutUserOnCustomerDeactivatedListener::class);
        Event::listen(UserCreatedEvent::class, CreateAdminOnUserCreatedListener::class);
        Event::listen(OrderCancelledEvent::class, OrderPaymentRefundOnCancelledOrderListener::class);
        Event::listen(OrderCancelledEvent::class, RestoreProductStockOnOrderCancelledListener::class);
        Event::listen(CustomerCreatedEvent::class, SendWelcomeEmailOnCustomerCreatedListener::class);
        Event::listen(OrderCreatedEvent::class, SendOrderConfirmationEmailOnOrderCreatedListener::class);
        Event::listen(OrderStatusUpdatedEvent::class, SendOrderStatusUpdateEmailListener::class);
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
