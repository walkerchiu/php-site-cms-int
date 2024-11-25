<?php

namespace WalkerChiu\SiteCMS;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use WalkerChiu\SiteCMS\Models\Entities\Site;
use WalkerChiu\SiteCMS\Models\Entities\SiteLang;

class SiteLangTest extends \Orchestra\Testbench\TestCase
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
     * A basic functional test on SiteLang.
     *
     * For WalkerChiu\Core\Models\Entities\Lang
     *     WalkerChiu\SiteCMS\Models\Entities\SiteLang
     *
     * @return void
     */
    public function testSiteLang()
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
        $db_lang_1 = factory(SiteLang::class)->create(['morph_id' => $db_morph_1->id, 'morph_type' => Site::class, 'code' => 'en_us', 'key' => 'name', 'value' => 'Hello']);
        $db_lang_2 = factory(SiteLang::class)->create(['morph_id' => $db_morph_1->id, 'morph_type' => Site::class, 'code' => 'en_us', 'key' => 'description']);
        $db_lang_3 = factory(SiteLang::class)->create(['morph_id' => $db_morph_1->id, 'morph_type' => Site::class, 'code' => 'zh_tw', 'key' => 'description']);
        $db_lang_4 = factory(SiteLang::class)->create(['morph_id' => $db_morph_1->id, 'morph_type' => Site::class, 'code' => 'en_us', 'key' => 'name']);
        $db_lang_5 = factory(SiteLang::class)->create(['morph_id' => $db_morph_2->id, 'morph_type' => Site::class, 'code' => 'en_us', 'key' => 'name']);
        $db_lang_6 = factory(SiteLang::class)->create(['morph_id' => $db_morph_2->id, 'morph_type' => Site::class, 'code' => 'zh_tw', 'key' => 'description']);

        // Get records after creation
            // When
            $records = SiteLang::all();
            // Then
            $this->assertCount(6, $records);

        // Get record's morph
            // When
            $record = SiteLang::find($db_lang_1->id);
            // Then
            $this->assertNotNull($record);
            $this->assertInstanceOf(Site::class, $record->morph);

        // Scope query on whereCode
            // When
            $records = SiteLang::ofCode('en_us')
                                ->get();
            // Then
            $this->assertCount(4, $records);

        // Scope query on whereKey
            // When
            $records = SiteLang::ofKey('name')
                                ->get();
            // Then
            $this->assertCount(3, $records);

        // Scope query on whereCodeAndKey
            // When
            $records = SiteLang::ofCodeAndKey('en_us', 'name')
                                ->get();
            // Then
            $this->assertCount(3, $records);

        // Scope query on whereMatch
            // When
            $records = SiteLang::ofMatch('en_us', 'name', 'Hello')
                                ->get();
            // Then
            $this->assertCount(1, $records);
            $this->assertTrue($records->contains('id', $db_lang_1->id));
    }

    /**
     * A basic functional test on SiteLang.
     *
     * For WalkerChiu\Core\Models\Entities\LangTrait
     *     WalkerChiu\SiteCMS\Models\Entities\SiteLang
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
        $db_lang_1 = factory(SiteLang::class)->create(['morph_id' => $db_morph_1->id, 'morph_type' => Site::class, 'code' => 'en_us', 'key' => 'name', 'value' => 'Hello']);
        $db_lang_2 = factory(SiteLang::class)->create(['morph_id' => $db_morph_1->id, 'morph_type' => Site::class, 'code' => 'en_us', 'key' => 'description']);
        $db_lang_3 = factory(SiteLang::class)->create(['morph_id' => $db_morph_1->id, 'morph_type' => Site::class, 'code' => 'zh_tw', 'key' => 'description']);
        $db_lang_4 = factory(SiteLang::class)->create(['morph_id' => $db_morph_1->id, 'morph_type' => Site::class, 'code' => 'en_us', 'key' => 'name']);
        $db_lang_5 = factory(SiteLang::class)->create(['morph_id' => $db_morph_2->id, 'morph_type' => Site::class, 'code' => 'en_us', 'key' => 'name']);
        $db_lang_6 = factory(SiteLang::class)->create(['morph_id' => $db_morph_2->id, 'morph_type' => Site::class, 'code' => 'zh_tw', 'key' => 'description']);

        // Get lang of record
            // When
            $record_1 = Site::find($db_morph_1->id);
            $lang_1   = SiteLang::find($db_lang_1->id);
            $lang_4   = SiteLang::find($db_lang_4->id);
            // Then
            $this->assertNotNull($record_1);
            $this->assertTrue(!$lang_1->is_current);
            $this->assertTrue($lang_4->is_current);
            $this->assertCount(4, $record_1->langs);
            $this->assertInstanceOf(SiteLang::class, $record_1->findLang('en_us', 'name', 'entire'));
            $this->assertEquals($db_lang_4->id, $record_1->findLang('en_us', 'name', 'entire')->id);
            $this->assertEquals($db_lang_4->id, $record_1->findLangByKey('name', 'entire')->id);
            $this->assertEquals($db_lang_2->id, $record_1->findLangByKey('description', 'entire')->id);

        // Get lang's histories of record
            // When
            $histories_1 = $record_1->getHistories('en_us', 'name');
            $record_2 = Site::find($db_morph_2->id);
            $histories_2 = $record_2->getHistories('en_us', 'name');
            // Then
            $this->assertCount(1, $histories_1);
            $this->assertCount(0, $histories_2);
    }
}
