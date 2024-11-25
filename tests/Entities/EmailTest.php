<?php

namespace WalkerChiu\SiteCMS;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use WalkerChiu\SiteCMS\Models\Entities\Site;
use WalkerChiu\SiteCMS\Models\Entities\SiteLang;
use WalkerChiu\SiteCMS\Models\Entities\Email;
use WalkerChiu\SiteCMS\Models\Entities\EmailLang;

class EmailTest extends \Orchestra\Testbench\TestCase
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
     * A basic functional test on Email.
     *
     * For WalkerChiu\SiteCMS\Models\Entities\Email
     *
     * @return void
     */
    public function testEmail()
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
        $db_morph_1 = factory(Email::class)->create(['site_id' => $db_site->id]);
        $db_morph_2 = factory(Email::class)->create(['site_id' => $db_site->id]);
        $db_morph_3 = factory(Email::class)->create(['site_id' => $db_site->id, 'is_enabled' => 1]);

        // Get records after creation
            // When
            $records = Email::all();
            // Then
            $this->assertCount(3, $records);

        // Delete someone
            // When
            $db_morph_2->delete();
            $records = Email::all();
            // Then
            $this->assertCount(2, $records);

        // Resotre someone
            // When
            Email::withTrashed()
                 ->find($db_morph_2->id)
                 ->restore();
            $record_2 = Email::find($db_morph_2->id);
            $records = Email::all();
            // Then
            $this->assertNotNull($record_2);
            $this->assertCount(3, $records);

        // Return Lang class
            // When
            $class = $record_2->lang();
            // Then
            $this->assertEquals($class, EmailLang::class);

        // Scope query on enabled records
            // When
            $records = Email::ofEnabled()
                            ->get();
            // Then
            $this->assertCount(1, $records);

        // Scope query on disabled records
            // When
            $records = Email::ofDisabled()
                            ->get();
            // Then
            $this->assertCount(2, $records);
    }
}
