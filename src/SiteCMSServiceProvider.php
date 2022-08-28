<?php

namespace WalkerChiu\SiteCMS;

use Illuminate\Support\ServiceProvider;
use WalkerChiu\SiteCMS\Providers\EventServiceProvider;

class SiteCMSServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();
        $this->app['router']->aliasMiddleware('wkSiteEnable' , config('wk-core.class.site-cms.verifyEnable'));

        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
           __DIR__ .'/config/site-cms.php' => config_path('wk-site-cms.php'),
        ], 'config');

        // Publish migration files
        $from = __DIR__ .'/database/migrations/';
        $to   = database_path('migrations') .'/';
        $this->publishes([
            $from .'create_wk_site_cms_table.php'
                => $to .date('Y_m_d_His', time()) .'_create_wk_site_cms_table.php'
        ], 'migrations');

        $this->loadViewsFrom(__DIR__.'/views', 'php-site-cms');
        $this->publishes([
           __DIR__.'/views' => resource_path('views/vendor/php-site-cms'),
        ]);

        $this->loadTranslationsFrom(__DIR__.'/translations', 'php-site-cms');
        $this->publishes([
            __DIR__.'/translations' => resource_path('lang/vendor/php-site-cms'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                config('wk-site-cms.command.cleaner'),
                config('wk-site-cms.command.initializer')
            ]);
        }

        config('wk-core.class.site-cms.site')::observe(config('wk-core.class.site-cms.siteObserver'));
        config('wk-core.class.site-cms.siteLang')::observe(config('wk-core.class.site-cms.siteLangObserver'));
        config('wk-core.class.site-cms.email')::observe(config('wk-core.class.site-cms.emailObserver'));
        config('wk-core.class.site-cms.emailLang')::observe(config('wk-core.class.site-cms.emailLangObserver'));
        config('wk-core.class.site-cms.layout')::observe(config('wk-core.class.site-cms.layoutObserver'));
        config('wk-core.class.site-cms.layoutLang')::observe(config('wk-core.class.site-cms.layoutLangObserver'));
    }

    /**
     * Register the blade directives
     *
     * @return void
     */
    private function bladeDirectives()
    {
    }

    /**
     * Merges user's and package's configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        if (!config()->has('wk-site-cms')) {
            $this->mergeConfigFrom(
                __DIR__ .'/config/site-cms.php', 'wk-site-cms'
            );
        }

        $this->mergeConfigFrom(
            __DIR__ .'/config/site-cms.php', 'site-cms'
        );
    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param String  $path
     * @param String  $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        if (
            !(
                $this->app instanceof CachesConfiguration
                && $this->app->configurationIsCached()
            )
        ) {
            $config = $this->app->make('config');
            $content = $config->get($key, []);

            $config->set($key, array_merge(
                require $path, $content
            ));
        }
    }
}
