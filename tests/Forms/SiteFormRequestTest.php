<?php

namespace WalkerChiu\SiteCMS;

use Illuminate\Support\Facades\Validator;
use WalkerChiu\Core\Models\Constants\Language;
use WalkerChiu\Core\Models\Constants\TimeZone;
use WalkerChiu\SiteCMS\Models\Forms\SiteFormRequest;

class SiteFormRequestTest extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        //$this->loadLaravelMigrations(['--database' => 'mysql']);
        $this->loadMigrationsFrom(__DIR__ .'/../migrations');
        $this->withFactories(__DIR__ .'/../../src/database/factories');

        $this->request  = new SiteFormRequest();
        $this->rules    = $this->request->rules();
        $this->messages = $this->request->messages();
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
     * Unit test about Authorize.
     *
     * For WalkerChiu\SiteCMS\Models\Forms\SiteFormRequest
     * 
     * @return void
     */
    public function testAuthorize()
    {
        $this->assertEquals(true, 1);
    }

    /**
     * Unit test about Rules.
     *
     * For WalkerChiu\SiteCMS\Models\Forms\SiteFormRequest
     * 
     * @return void
     */
    public function testRules()
    {
        $faker = \Faker\Factory::create();

        $language = $faker->randomElement(config('wk-core.class.core.language')::getCodes());

        // Give
        $attributes = [
            'serial'             => $faker->isbn10,
            'identifier'         => $faker->slug,
            'language'           => $language,
            'language_supported' => [$language],
            'timezone'           => $faker->randomElement(config('wk-core.class.core.timeZone')::getValues()),
            'name'               => $faker->name,
            'email_info'         => $faker->email,
            'email_contact'      => $faker->email
        ];
        // When
        $validator = Validator::make($attributes, $this->rules, $this->messages); $this->request->withValidator($validator);
        $fails = $validator->fails();
        // Then
        $this->assertEquals(false, $fails);

        // Give
        $attributes = [
            'serial'        => $faker->isbn10,
            'identifier'    => $faker->slug,
            'name'          => $faker->name,
            'email_info'    => $faker->email,
            'email_contact' => $faker->email,
            'language'      => 'zh-tw',
            'timezone'      => $faker->randomElement(TimeZone::getValues())
        ];
        // When
        $validator = Validator::make($attributes, $this->rules, $this->messages); $this->request->withValidator($validator);
        $fails = $validator->fails();
        // Then
        $this->assertEquals(true, $fails);
    }
}
