<?php

/** @var \Illuminate\Database\Eloquent\Factory  $factory */

use Faker\Generator as Faker;
use WalkerChiu\SiteCMS\Models\Entities\Site;
use WalkerChiu\SiteCMS\Models\Entities\SiteLang;

$factory->define(Site::class, function (Faker $faker) {
    $language = $faker->randomElement(config('wk-core.class.core.language')::getCodes());
    $timezone = $faker->randomElement(config('wk-core.class.core.timeZone')::getValues());

    return [
        'serial'             => $faker->isbn10,
        'identifier'         => $faker->slug,
        'language'           => $language,
        'language_supported' => [$language],
        'timezone'           => $timezone
    ];
});

$factory->define(SiteLang::class, function (Faker $faker) {
    return [
        'code'  => $faker->locale,
        'key'   => $faker->randomElement(['name', 'description']),
        'value' => $faker->sentence
    ];
});
