<?php

namespace App\Providers;

use App\Models\ContentDetails;
use App\Models\Language;
use App\Models\ManageMenu;
use App\Services\SidebarDataService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Bridge\Mailchimp\Transport\MandrillTransportFactory;
use Symfony\Component\Mailer\Bridge\Sendgrid\Transport\SendgridTransportFactory;
use Symfony\Component\Mailer\Bridge\Sendinblue\Transport\SendinblueTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

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
        try {
            DB::connection()->getPdo();
            Paginator::useBootstrap();
            $data['basicControl'] = basicControl();
            $data['theme'] = template();
            $data['themeTrue'] = template(true);
            View::share($data);

            view()->composer([
                $data['theme'] . 'partials.header',
                $data['theme'] . 'partials.footer',
                $data['theme'] . 'page',
            ], function ($view) {
                $selectedTheme = basicControl()->theme;
                $extraInfo = Cache::remember("content_extra_info_{$selectedTheme}", now()->addMinutes(60),
                    function () use ($selectedTheme) {
                        return ContentDetails::whereHas('content', function ($query) use ($selectedTheme) {
                            return $query->whereIn('name', ['contact', 'social'])->where('theme', $selectedTheme);
                        })->get()->groupBy('content.name');
                    });
                $languages = Cache::remember('active_languages', now()->addMinutes(60), function () {
                    return Language::query()->orderBy('default_status', 'desc')->where('status', 1)->get();
                });

                $view->with('extraInfo', $extraInfo);
                $view->with('languages', $languages);
            });

            view()->composer([
                'admin.layouts.sidebar',
            ], function ($view) {
                $sidebarCounts = Cache::remember('sidebar_counts', now()->addMinutes(10), function () {
                    return SidebarDataService::getSidebarCounts();
                });
                $view->with('sidebarCounts', $sidebarCounts);
            });



            if (basicControl()->force_ssl == 1) {
                if ($this->app->environment('production') || $this->app->environment('local')) {
                    \URL::forceScheme('https');
                }
            }

            Mail::extend('sendinblue', function () {
                return (new SendinblueTransportFactory)->create(
                    new Dsn(
                        'sendinblue+api',
                        'default',
                        config('services.sendinblue.key')
                    )
                );
            });

            Mail::extend('sendgrid', function () {
                return (new SendgridTransportFactory)->create(
                    new Dsn(
                        'sendgrid+api',
                        'default',
                        config('services.sendgrid.key')
                    )
                );
            });

            Mail::extend('mandrill', function () {
                return (new MandrillTransportFactory)->create(
                    new Dsn(
                        'mandrill+api',
                        'default',
                        config('services.mandrill.key')
                    )
                );
            });

        } catch (\Exception $e) {
        }

    }
}
