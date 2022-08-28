<?php

/** @var \Illuminate\Database\Eloquent\Factory  $factory */

use Faker\Generator as Faker;
use WalkerChiu\SiteCMS\Models\Entities\Email;
use WalkerChiu\SiteCMS\Models\Entities\EmailLang;

$factory->define(Email::class, function (Faker $faker) {
    return [
        'site_id' => 1,
        'type'    => $faker->randomElement(config('wk-core.class.site-cms.emailType')::getCodes()),
        'serial'  => $faker->isbn10
    ];
});

$factory->define(EmailLang::class, function (Faker $faker) {
    return [
        'code'  => $faker->locale,
        'key'   => $faker->randomElement(['name', 'description', 'subject', 'content']),
        'value' => $faker->sentence
    ];
});
