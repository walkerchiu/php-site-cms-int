<?php

namespace WalkerChiu\SiteCMS\Events;

use WalkerChiu\SiteCMS\Models\Services\SiteService;

trait EventTrait
{
    /**
     * Get Site.
     *
     * @return void
     */
    public function getSite()
    {
        $service = new SiteService();
        $service->rememberSite();

        return $service->getSite();
    }
}
