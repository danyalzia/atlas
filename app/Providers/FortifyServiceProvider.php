<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Inertia\Inertia;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    #[\Override]
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
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', fn(Request $request) => Limit::perMinute(5)->by($request->email.$request->ip()));

        RateLimiter::for('two-factor', fn(Request $request) => Limit::perMinute(5)->by($request->session()->get('login.id')));

        Fortify::loginView(fn() => Inertia::render('auth/Login'));
        Fortify::registerView(fn() => Inertia::render('auth/Register'));
        Fortify::requestPasswordResetLinkView(fn() => Inertia::render('auth/ForgotPassword'));
        Fortify::resetPasswordView(fn() => Inertia::render('auth/ResetPassword'));
        Fortify::verifyEmailView(fn() => Inertia::render('auth/VerifyEmail'));
        Fortify::confirmPasswordView(fn() => Inertia::render('auth/ConfirmPassword'));
    }
}
