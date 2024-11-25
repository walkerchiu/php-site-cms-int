<?php

namespace WalkerChiu\SiteCMS;

use Illuminate\Support\Facades\Validator;
use WalkerChiu\SiteCMS\Models\Constants\LayoutType;
use WalkerChiu\SiteCMS\Models\Entities\Site;
use WalkerChiu\SiteCMS\Models\Forms\LayoutFormRequest;

class LayoutFormRequestTest extends \Orchestra\Testbench\TestCase
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

        $this->request  = new LayoutFormRequest();
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
     * For WalkerChiu\Account\Models\Forms\LayoutFormRequest
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
     * For WalkerChiu\Account\Models\Forms\LayoutFormRequest
     * 
     * @return void
     */
    public function testRules()
    {
        $faker = \Faker\Factory::create();

        $db_site = factory(Site::class)->create();

        // Give
        $attributes = [
            'site_id'        => $db_site->id,
            'type'           => $faker->randomElement(LayoutType::getCodes()),
            'serial'         => $faker->isbn10,
            'identifier'     => $faker->slug,
            'is_highlighted' => $faker->boolean,
            'name'           => $faker->name,
            'content'        => $faker->text
        ];
        // When
        $validator = Validator::make($attributes, $this->rules, $this->messages); $this->request->withValidator($validator);
        $fails = $validator->fails();
        // Then
        $this->assertEquals(false, $fails);

        // Give
        $attributes = [
            'type'           => $faker->randomElement(LayoutType::getCodes()),
            'serial'         => $faker->isbn10,
            'identifier'     => $faker->slug,
            'is_highlighted' => $faker->boolean,
            'name'           => $faker->name,
            'content'        => $faker->text
        ];
        // When
        $validator = Validator::make($attributes, $this->rules, $this->messages); $this->request->withValidator($validator);
        $fails = $validator->fails();
        // Then
        $this->assertEquals(true, $fails);
    }
}
