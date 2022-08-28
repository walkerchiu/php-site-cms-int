<?php

namespace WalkerChiu\SiteCMS\Events\Handlers;

use WalkerChiu\SiteCMS\Models\Services\EmailService;

abstract class Notification
{
    /**
     * Service.
     *
     * @var EmailService
     */
    public $service;

    /**
     * Site.
     *
     * @var Site
     */
    public $site;

    /**
     * User.
     *
     * @var User
     */
    public $user;

    /**
     * Notification.
     *
     * @return void
     */
    public function __construct()
    {
        $this->service = new EmailService();
    }
}
