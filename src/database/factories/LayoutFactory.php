<?php

/** @var \Illuminate\Database\Eloquent\Factory  $factory */

use Faker\Generator as Faker;
use WalkerChiu\SiteCMS\Models\Entities\Layout;
use WalkerChiu\SiteCMS\Models\Entities\LayoutLang;

$factory->define(Layout::class, function (Faker $faker) {
    return [
        'site_id'        => 1,
        'type'           => $faker->randomElement(config('wk-core.class.site-cms.layoutType')::getCodes()),
        'serial'         => $faker->isbn10,
        'identifier'     => $faker->slug,
        'is_highlighted' => $faker->boolean
    ];
});

$factory->define(LayoutLang::class, function (Faker $faker) {
    return [
        'code'  => $faker->locale,
        'key'   => $faker->randomElement(['name', 'description', 'content']),
        'value' => $faker->sentence
    ];
});
