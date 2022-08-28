<?php

namespace WalkerChiu\SiteCMS\Events;

use Illuminate\Auth\Events\Failed as BaseClass;
use WalkerChiu\SiteCMS\Events\EventTrait;

class Failed extends BaseClass
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
     * @param String                                      $guard
     * @param \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param Array                                       $credentials
     * @return void
     */
    public function __construct($guard, $user, $credentials)
    {
        $this->user        = $user;
        $this->guard       = $guard;
        $this->credentials = $credentials;

        $this->site = $this->getSite();
    }
}
