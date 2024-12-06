<?php

namespace Tec\Installer\Providers;

use Tec\Base\Events\FinishedSeederEvent;
use Tec\Base\Events\UpdatedEvent;
use Tec\Base\Facades\BaseHelper;
use Tec\Base\Supports\ServiceProvider;
use Tec\Base\Traits\LoadAndPublishDataTrait;
use Tec\Installer\Http\Middleware\CheckIfInstalledMiddleware;
use Tec\Installer\Http\Middleware\CheckIfInstallingMiddleware;
use Tec\Installer\Http\Middleware\RedirectIfNotInstalledMiddleware;
use Carbon\Carbon;
use Illuminate\Routing\Events\RouteMatched;

class InstallerServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('packages/installer')
            ->loadHelpers()
            ->loadAndPublishConfigurations('installer')
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes()
            ->publishAssets();

        $this->app['events']->listen(RouteMatched::class, function () {
            if (defined('INSTALLED_SESSION_NAME')) {
                $router = $this->app->make('router');

                $router->middlewareGroup('install', [CheckIfInstalledMiddleware::class]);
                $router->middlewareGroup('installing', [CheckIfInstallingMiddleware::class]);

                $router->pushMiddlewareToGroup('web', RedirectIfNotInstalledMiddleware::class);
            }
        });

        $this->app['events']->listen([UpdatedEvent::class, FinishedSeederEvent::class], function () {
            BaseHelper::saveFileData(storage_path(INSTALLED_SESSION_NAME), Carbon::now()->toDateTimeString());
        });
    }
}
