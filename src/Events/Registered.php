<?php

namespace WalkerChiu\SiteCMS\Events;

use Illuminate\Auth\Events\Registered as BaseClass;
use WalkerChiu\SiteCMS\Events\EventTrait;

class Registered extends BaseClass
{
    use EventTrait;

    /**
     * Site.
     *
     * @var Site
     */
    public $site;



    /**
     * Create a new event instance.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;

        $this->site = $this->getSite();
    }
}
