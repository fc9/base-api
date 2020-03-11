<?php

namespace Fc9\Api\Provider;

use RuntimeException;
use Fc9\Api\Auth\Auth;
use Fc9\Api\Dispatcher;
use Fc9\Api\Http\Request;
use Fc9\Api\Http\Response;
use Fc9\Api\Console\Command;
use Fc9\Api\Exception\Handler as ExceptionHandler;
use Fc9\Api\Transformer\Factory as TransformerFactory;

class DingoServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setResponseStaticInstances();

        Request::setAcceptParser($this->app[\Fc9\Api\Http\Parser\Accept::class]);

        $this->app->rebinding('api.routes', function ($app, $routes) {
            $app['api.url']->setRouteCollections($routes);
        });
    }

    protected function setResponseStaticInstances()
    {
        Response::setFormatters($this->config('formats'));
        Response::setFormatsOptions($this->config('formatsOptions'));
        Response::setTransformer($this->app['api.transformer']);
        Response::setEventDispatcher($this->app['events']);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

        $this->registerClassAliases();

        $this->app->register(RoutingServiceProvider::class);

        $this->app->register(HttpServiceProvider::class);

        $this->registerExceptionHandler();

        $this->registerDispatcher();

        $this->registerAuth();

        $this->registerTransformer();

        $this->registerDocsCommand();

        if (class_exists('Illuminate\Foundation\Application', false)) {
            $this->commands([
                \Fc9\Api\Console\Command\Cache::class,
                \Fc9\Api\Console\Command\Routes::class,
            ]);
        }
    }

    /**
     * Register the configuration.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/api.php'), 'api');

        if (! $this->app->runningInConsole() && empty($this->config('prefix')) && empty($this->config('domain'))) {
            throw new RuntimeException('Unable to boot ApiServiceProvider, configure an API domain or prefix.');
        }
    }

    /**
     * Register the class aliases.
     *
     * @return void
     */
    protected function registerClassAliases()
    {
        $serviceAliases = [
            \Fc9\Api\Http\Request::class => \Fc9\Api\Contract\Http\Request::class,
            'api.dispatcher' => \Fc9\Api\Dispatcher::class,
            'api.http.validator' => \Fc9\Api\Http\RequestValidator::class,
            'api.http.response' => \Fc9\Api\Http\Response\Factory::class,
            'api.router' => \Fc9\Api\Routing\Router::class,
            'api.router.adapter' => \Fc9\Api\Contract\Routing\Adapter::class,
            'api.auth' => \Fc9\Api\Auth\Auth::class,
            'api.limiting' => \Fc9\Api\Http\RateLimit\Handler::class,
            'api.transformer' => \Fc9\Api\Transformer\Factory::class,
            'api.url' => \Fc9\Api\Routing\UrlGenerator::class,
            'api.exception' => [\Fc9\Api\Exception\Handler::class, \Fc9\Api\Contract\Debug\ExceptionHandler::class],
        ];

        foreach ($serviceAliases as $key => $aliases) {
            foreach ((array) $aliases as $alias) {
                $this->app->alias($key, $alias);
            }
        }
    }

    /**
     * Register the exception handler.
     *
     * @return void
     */
    protected function registerExceptionHandler()
    {
        $this->app->singleton('api.exception', function ($app) {
            return new ExceptionHandler($app['Illuminate\Contracts\Debug\ExceptionHandler'], $this->config('errorFormat'), $this->config('debug'));
        });
    }

    /**
     * Register the internal dispatcher.
     *
     * @return void
     */
    public function registerDispatcher()
    {
        $this->app->singleton('api.dispatcher', function ($app) {
            $dispatcher = new Dispatcher($app, $app['files'], $app[\Fc9\Api\Routing\Router::class], $app[\Fc9\Api\Auth\Auth::class]);

            $dispatcher->setSubtype($this->config('subtype'));
            $dispatcher->setStandardsTree($this->config('standardsTree'));
            $dispatcher->setPrefix($this->config('prefix'));
            $dispatcher->setDefaultVersion($this->config('version'));
            $dispatcher->setDefaultDomain($this->config('domain'));
            $dispatcher->setDefaultFormat($this->config('defaultFormat'));

            return $dispatcher;
        });
    }

    /**
     * Register the auth.
     *
     * @return void
     */
    protected function registerAuth()
    {
        $this->app->singleton('api.auth', function ($app) {
            return new Auth($app[\Fc9\Api\Routing\Router::class], $app, $this->config('auth'));
        });
    }

    /**
     * Register the transformer factory.
     *
     * @return void
     */
    protected function registerTransformer()
    {
        $this->app->singleton('api.transformer', function ($app) {
            return new TransformerFactory($app, $this->config('transformer'));
        });
    }

    /**
     * Register the documentation command.
     *
     * @return void
     */
    protected function registerDocsCommand()
    {
        $this->app->singleton(\Fc9\Api\Console\Command\Docs::class, function ($app) {
            return new Command\Docs(
                $app[\Fc9\Api\Routing\Router::class],
                $app[\Fc9\Blueprint\Blueprint::class],
                $app[\Fc9\Blueprint\Writer::class],
                $this->config('name'),
                $this->config('version')
            );
        });

        $this->commands([\Fc9\Api\Console\Command\Docs::class]);
    }
}
