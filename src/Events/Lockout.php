<?php

namespace WalkerChiu\SiteCMS\Events;

use Illuminate\Auth\Events\Lockout as BaseClass;
use Illuminate\Http\Request;
use WalkerChiu\SiteCMS\Events\EventTrait;

class Lockout extends BaseClass
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
     * @param \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->site = $this->getSite();
    }
}
