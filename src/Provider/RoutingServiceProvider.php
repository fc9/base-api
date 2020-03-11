<?php

namespace Fc9\Api\Provider;

use Fc9\Api\Routing\Router;
use Fc9\Api\Routing\UrlGenerator;
use Fc9\Api\Contract\Routing\Adapter;
use Fc9\Api\Routing\ResourceRegistrar;
use Fc9\Api\Contract\Debug\ExceptionHandler;

class RoutingServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerRouter();

        $this->registerUrlGenerator();
    }

    /**
     * Register the router.
     */
    protected function registerRouter()
    {
        $this->app->singleton('api.router', function ($app) {
            $router = new Router(
                $app[Adapter::class],
                $app[ExceptionHandler::class],
                $app,
                $this->config('domain'),
                $this->config('prefix')
            );

            $router->setConditionalRequest($this->config('conditionalRequest'));

            return $router;
        });

        $this->app->singleton(ResourceRegistrar::class, function ($app) {
            return new ResourceRegistrar($app[Router::class]);
        });
    }

    /**
     * Register the URL generator.
     */
    protected function registerUrlGenerator()
    {
        $this->app->singleton('api.url', function ($app) {
            $url = new UrlGenerator($app['request']);

            $url->setRouteCollections($app[Router::class]->getRoutes());

            $url->setKeyResolver(function () {
                return $this->app->make('config')->get('app.key');
            });

            return $url;
        });
    }

    /**
     * Get the URL generator request rebinder.
     *
     * @return \Closure
     */
    private function requestRebinder()
    {
        return function ($app, $request) {
            $app['api.url']->setRequest($request);
        };
    }
}
