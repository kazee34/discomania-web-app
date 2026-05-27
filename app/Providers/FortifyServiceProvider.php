<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Models\AdminModel;
use App\Models\CustomerModel;
use App\Models\UserModel;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\PasswordConfirmedResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\TwoFactorDisabledResponse;
use Laravel\Fortify\Contracts\TwoFactorEnabledResponse;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
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
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
        $this->configureResponses();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::authenticateUsing(function (Request $request) {
            $user = UserModel::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return null;
            }

            $isDeactivatedAdmin = AdminModel::where('user_id', $user->id)
                ->where('is_active', false)
                ->exists();

            $isDeactivatedCustomer = CustomerModel::where('user_id', $user->id)
                ->where('is_active', false)
                ->exists();

            if ($isDeactivatedAdmin || $isDeactivatedCustomer) {
                return null;
            }

            return $user;
        });
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(fn (Request $request) => Inertia::render('auth/Login', [
            'canResetPassword' => Features::enabled(Features::resetPasswords()),
            'canRegister' => Features::enabled(Features::registration()),
            'status' => $request->session()->get('status'),
        ]));

        Fortify::resetPasswordView(fn (Request $request) => Inertia::render('auth/ResetPassword', [
            'email' => $request->email,
            'token' => $request->route('token'),
        ]));

        Fortify::requestPasswordResetLinkView(fn (Request $request) => Inertia::render('auth/ForgotPassword', [
            'status' => $request->session()->get('status'),
        ]));

        Fortify::verifyEmailView(fn (Request $request) => Inertia::render('auth/VerifyEmail', [
            'status' => $request->session()->get('status'),
        ]));

        Fortify::registerView(function (Request $request) {
            $path = null;

            $redirectParam = $request->query('redirect', '');
            if ($redirectParam && ! in_array($redirectParam, ['/login', '/register'], true)) {
                $path = $redirectParam;
            } else {
                $referer = $request->headers->get('referer', '');
                $appUrl = config('app.url');
                if ($referer && str_starts_with($referer, $appUrl)) {
                    $refererPath = parse_url($referer, PHP_URL_PATH) ?? '/';
                    if (! in_array($refererPath, ['/login', '/register'], true)) {
                        $path = $refererPath;
                    }
                }
            }

            if ($path) {
                $request->session()->put('register.intended', $path);
            }

            return Inertia::render('auth/Register');
        });

        Fortify::twoFactorChallengeView(fn () => Inertia::render('auth/TwoFactorChallenge'));

        Fortify::confirmPasswordView(fn () => Inertia::render('auth/ConfirmPassword'));
    }

    /**
     * Override Fortify response contracts for role-based redirects.
     */
    private function configureResponses(): void
    {
        $this->app->singleton(LoginResponse::class, function () {
            return new class implements LoginResponse
            {
                public function toResponse($request)
                {
                    $isAdmin = AdminModel::query()
                        ->where('user_id', $request->user()->id)
                        ->where('is_active', true)
                        ->exists();

                    return Inertia::location($isAdmin ? '/dashboard' : '/shop');
                }
            };
        });

        $this->app->singleton(TwoFactorLoginResponse::class, function () {
            return new class implements TwoFactorLoginResponse
            {
                public function toResponse($request)
                {
                    $isAdmin = AdminModel::query()
                        ->where('user_id', $request->user()->id)
                        ->where('is_active', true)
                        ->exists();

                    return Inertia::location($isAdmin ? '/dashboard' : '/shop');
                }
            };
        });

        $this->app->singleton(PasswordConfirmedResponse::class, function () {
            return new class implements PasswordConfirmedResponse
            {
                public function toResponse($request)
                {
                    return redirect()->intended('/settings/two-factor');
                }
            };
        });

        $this->app->singleton(TwoFactorEnabledResponse::class, function () {
            return new class implements TwoFactorEnabledResponse
            {
                public function toResponse($request)
                {
                    return redirect('/settings/two-factor');
                }
            };
        });

        $this->app->singleton(TwoFactorDisabledResponse::class, function () {
            return new class implements TwoFactorDisabledResponse
            {
                public function toResponse($request)
                {
                    return redirect('/settings/two-factor');
                }
            };
        });

        $this->app->singleton(RegisterResponse::class, function () {
            return new class implements RegisterResponse
            {
                public function toResponse($request)
                {
                    $intended = $request->session()->pull('register.intended', '/shop');

                    return Inertia::location($intended);
                }
            };
        });
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}
