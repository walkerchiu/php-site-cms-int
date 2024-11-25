<?php

namespace WalkerChiu\SiteCMS;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use WalkerChiu\Core\Models\Constants\CountryZone;
use WalkerChiu\Core\Models\Constants\Language;
use WalkerChiu\SiteCMS\Models\Entities\Site;
use WalkerChiu\SiteCMS\Models\Entities\SiteLang;

class SiteTest extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ .'/../migrations');
        $this->withFactories(__DIR__ .'/../../src/database/factories');
    }

    /**
     * To load your package service provider, override the getPackageProviders.
     *
     * @param \Illuminate\Foundation\Application  $app
     * @return Array
     */
    protected function getPackageProviders($app)
    {
        return [\WalkerChiu\Core\CoreServiceProvider::class,
                \WalkerChiu\SiteCMS\SiteCMSServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
    }

    /**
     * A basic functional test on Site.
     *
     * For WalkerChiu\SiteCMS\Models\Entities\Site
     * 
     * @return void
     */
    public function testSite()
    {
        // Config
        Config::set('wk-core.onoff.core-lang_core', 0);
        Config::set('wk-site-cms.onoff.core-lang_core', 0);
        Config::set('wk-core.lang_log', 1);
        Config::set('wk-site-cms.lang_log', 1);
        Config::set('wk-core.soft_delete', 1);
        Config::set('wk-site-cms.soft_delete', 1);

        // Give
        $db_morph_1 = factory(Site::class)->create();
        $db_morph_2 = factory(Site::class)->create();
        $db_morph_3 = factory(Site::class)->create(['is_enabled' => 1]);

        // Get records after creation
            // When
            $records = Site::all();
            // Then
            $this->assertCount(3, $records);

        // Delete someone
            // When
            $db_morph_2->delete();
            $records = Site::all();
            // Then
            $this->assertCount(2, $records);

        // Resotre someone
            // When
            Site::withTrashed()
                 ->find($db_morph_2->id)
                 ->restore();
            $record_2 = Site::find($db_morph_2->id);
            $records = Site::all();
            // Then
            $this->assertNotNull($record_2);
            $this->assertCount(3, $records);

        // Return Lang class
            // When
            $class = $record_2->lang();
            // Then
            $this->assertEquals($class, SiteLang::class);

        // Scope query on enabled records
            // When
            $records = Site::ofEnabled()
                            ->get();
            // Then
            $this->assertCount(1, $records);

        // Scope query on disabled records
            // When
            $records = Site::ofDisabled()
                            ->get();
            // Then
            $this->assertCount(2, $records);

        // Give
        $record_4 = factory(Site::class)->create(['language_supported' => Language::getCodes()]);
        // When
        $language_supported = $record_4->language_supported;
        // Then
        $this->assertEquals(true, is_array($language_supported));
    }
}
