<?php

namespace WalkerChiu\SiteCMS;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use WalkerChiu\SiteCMS\Models\Entities\Site;
use WalkerChiu\SiteCMS\Models\Entities\SiteLang;
use WalkerChiu\SiteCMS\Models\Entities\Layout;
use WalkerChiu\SiteCMS\Models\Entities\LayoutLang;

class LayoutTest extends \Orchestra\Testbench\TestCase
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
     * A basic functional test on Layout.
     *
     * For WalkerChiu\SiteCMS\Models\Entities\Layout
     *
     * @return void
     */
    public function testLayout()
    {
        // Config
        Config::set('wk-core.onoff.core-lang_core', 0);
        Config::set('wk-site-cms.onoff.core-lang_core', 0);
        Config::set('wk-core.lang_log', 1);
        Config::set('wk-site-cms.lang_log', 1);
        Config::set('wk-core.soft_delete', 1);
        Config::set('wk-site-cms.soft_delete', 1);

        $db_site = factory(Site::class)->create();

        // Give
        $db_morph_1 = factory(Layout::class)->create(['site_id' => $db_site->id]);
        $db_morph_2 = factory(Layout::class)->create(['site_id' => $db_site->id]);
        $db_morph_3 = factory(Layout::class)->create(['site_id' => $db_site->id, 'is_enabled' => 1]);

        // Get records after creation
            // When
            $records = Layout::all();
            // Then
            $this->assertCount(3, $records);

        // Delete someone
            // When
            $db_morph_2->delete();
            $records = Layout::all();
            // Then
            $this->assertCount(2, $records);

        // Resotre someone
            // When
            Layout::withTrashed()
                  ->find($db_morph_2->id)
                  ->restore();
            $record_2 = Layout::find($db_morph_2->id);
            $records = Layout::all();
            // Then
            $this->assertNotNull($record_2);
            $this->assertCount(3, $records);

        // Return Lang class
            // When
            $class = $record_2->lang();
            // Then
            $this->assertEquals($class, LayoutLang::class);

        // Scope query on enabled records
            // When
            $records = Layout::ofEnabled()
                             ->get();
            // Then
            $this->assertCount(1, $records);

        // Scope query on disabled records
            // When
            $records = Layout::ofDisabled()
                             ->get();
            // Then
            $this->assertCount(2, $records);
    }
}
