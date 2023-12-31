<?php

namespace WalkerChiu\SiteCMS\Models\Observers;

class SiteObserver
{
    /**
     * Handle the entity "retrieved" event.
     *
     * @param Entity  $entity
     * @return void
     */
    public function retrieved($entity)
    {
        //
    }

    /**
     * Handle the entity "creating" event.
     *
     * @param Entity  $entity
     * @return void
     */
    public function creating($entity)
    {
        //
    }

    /**
     * Handle the entity "created" event.
     *
     * @param Entity  $entity
     * @return void
     */
    public function created($entity)
    {
        //
    }

    /**
     * Handle the entity "updating" event.
     *
     * @param Entity  $entity
     * @return void
     */
    public function updating($entity)
    {
        //
    }

    /**
     * Handle the entity "updated" event.
     *
     * @param Entity  $entity
     * @return void
     */
    public function updated($entity)
    {
        //
    }

    /**
     * Handle the entity "saving" event.
     *
     * @param Entity  $entity
     * @return void
     */
    public function saving($entity)
    {
        if (!is_null($entity->language)) {
            if (!in_array($entity->language, config('wk-core.class.core.language')::getCodes()))
                return false;
        }
        if (!is_null($entity->timezone)) {
            if (!in_array($entity->timezone, config('wk-core.class.core.timeZone')::getValues()))
                return false;
        }
        if ($entity->is_main) {
            config('wk-core.class.site-cms.site')
                ::withTrashed()
                ->where('id', '<>', $entity->id)
                ->update(['is_main' => 0]);
        }
        if (
            config('wk-core.class.site-cms.site')
                ::where('id', '<>', $entity->id)
                ->where('identifier', $entity->identifier)
                ->exists()
        )
            return false;
    }

    /**
     * Handle the entity "saved" event.
     *
     * @param Entity  $entity
     * @return void
     */
    public function saved($entity)
    {
        //
    }

    /**
     * Handle the entity "deleting" event.
     *
     * @param Entity  $entity
     * @return void
     */
    public function deleting($entity)
    {
        if ($entity->is_main)
            return false;
    }

    /**
     * Handle the entity "deleted" event.
     *
     * Its Lang will be automatically removed by database.
     *
     * @param Entity  $entity
     * @return void
     */
    public function deleted($entity)
    {
        if ($entity->isForceDeleting()) {
            $entity->langs()->withTrashed()
                            ->forceDelete();
            foreach ($entity->layouts as $layout) {
                if (
                    config('wk-site-cms.onoff.morph-category')
                    && !empty(config('wk-core.class.morph-category.category'))
                ) {
                    $layout->categories()->detach();
                }
                if (
                    config('wk-site-cms.onoff.morph-comment')
                    && !empty(config('wk-core.class.morph-comment.comment'))
                ) {
                    $records = $layout->comments()->withTrashed()->get();
                    foreach ($records as $recoed) {
                        $recoed->forceDelete();
                    }
                }
                if (
                    config('wk-site-cms.onoff.morph-image')
                    && !empty(config('wk-core.class.morph-image.image'))
                ) {
                    $records = $layout->images()->withTrashed()->get();
                    foreach ($records as $recoed) {
                        $recoed->forceDelete();
                    }
                }
                if (
                    config('wk-site-cms.onoff.morph-nav')
                    && !empty(config('wk-core.class.morph-nav.nav'))
                ) {
                    $layout->navs()->detach();
                }
                if (
                    config('wk-site-cms.onoff.morph-tag')
                    && !empty(config('wk-core.class.morph-tag.tag'))
                    && is_iterable($layout->tags())
                ) {
                    $layout->tags()->detach();
                }
            }

            if (
                config('wk-site-cms.onoff.coupons')
                && !empty(config('wk-core.class.coupon.coupon'))
            ) {
                $records = $entity->coupons()->withTrashed()->get();
                foreach ($records as $recoed) {
                    $recoed->forceDelete();
                }
            }
            if (
                config('wk-site-cms.onoff.firewall')
                && !empty(config('wk-core.class.firewall.setting'))
            ) {
                $records = $entity->firewalls()->withTrashed()->get();
                foreach ($records as $recoed) {
                    $recoed->forceDelete();
                }
            }
            if (
                config('wk-site-cms.onoff.mall-stock')
                && !empty(config('wk-core.class.mall-stock.stock'))
            ) {
                $records = $entity->stocks()->withTrashed()->get();
                foreach ($records as $recoed) {
                    $recoed->forceDelete();
                }
            }
            if (
                config('wk-site-cms.onoff.morph-address')
                && !empty(config('wk-core.class.morph-address.address'))
            ) {
                $records = $entity->addresses()->withTrashed()->get();
                foreach ($records as $recoed) {
                    $recoed->forceDelete();
                }
            }
            if (
                config('wk-site-cms.onoff.morph-board')
                && !empty(config('wk-core.class.morph-board.board'))
            ) {
                $records = $entity->boards()->withTrashed()->get();
                foreach ($records as $recoed) {
                    $recoed->forceDelete();
                }
            }
            if (
                config('wk-site-cms.onoff.morph-category')
                && !empty(config('wk-core.class.morph-category.category'))
            ) {
                $entity->categories()->detach();
            }
            if (
                config('wk-site-cms.onoff.morph-comment')
                && !empty(config('wk-core.class.morph-comment.comment'))
            ) {
                $records = $entity->comments()->withTrashed()->get();
                foreach ($records as $recoed) {
                    $recoed->forceDelete();
                }
            }
            if (
                config('wk-site-cms.onoff.morph-image')
                && !empty(config('wk-core.class.morph-image.image'))
            ) {
                $records = $entity->images()->withTrashed()->get();
                foreach ($records as $recoed) {
                    $recoed->forceDelete();
                }
            }
            if (
                config('wk-site-cms.onoff.morph-nav')
                && !empty(config('wk-core.class.morph-nav.nav'))
            ) {
                $entity->navs()->detach();
            }
            if (
                config('wk-site-cms.onoff.morph-registration')
                && !empty(config('wk-core.class.morph-registration.registration'))
            ) {
                $records = $entity->registrations()->withTrashed()->get();
                foreach ($records as $recoed) {
                    $recoed->forceDelete();
                }
            }
            if (
                config('wk-site-cms.onoff.morph-tag')
                && !empty(config('wk-core.class.morph-tag.tag'))
                && is_iterable($entity->tags())
            ) {
                $entity->tags()->detach();
            }
            if (
                config('wk-site-cms.onoff.morph-link')
                && !empty(config('wk-core.class.morph-link.link'))
            ) {
                $records = $entity->links()->withTrashed()->get();
                foreach ($records as $recoed) {
                    $recoed->forceDelete();
                }
            }
            if (
                config('wk-site-cms.onoff.newsletter')
                && !empty(config('wk-core.class.newsletter.article'))
            ) {
                $records = $entity->newsletters()->withTrashed()->get();
                foreach ($records as $recoed) {
                    $recoed->forceDelete();
                }
            }
            if (
                config('wk-site-cms.onoff.payment')
                && !empty(config('wk-core.class.payment.payment'))
            ) {
                $records = $entity->payments()->withTrashed()->get();
                foreach ($records as $recoed) {
                    $recoed->forceDelete();
                }
            }
            if (
                config('wk-site-cms.onoff.point')
                && !empty(config('wk-core.class.point.setting'))
            ) {
                $records = $entity->points()->withTrashed()->get();
                foreach ($records as $recoed) {
                    $recoed->forceDelete();
                }
            }
            if (
                config('wk-site-cms.onoff.role')
                && !empty(config('wk-core.class.role.role'))
            ) {
                $records = $entity->roles()->withTrashed()->get();
                foreach ($records as $recoed) {
                    $recoed->forceDelete();
                }
            }
            if (
                config('wk-site-cms.onoff.shipment')
                && !empty(config('wk-core.class.shipment.shipment'))
            ) {
                $records = $entity->shipments()->withTrashed()->get();
                foreach ($records as $recoed) {
                    $recoed->forceDelete();
                }
            }
        }

        if (!config('wk-site-cms.soft_delete')) {
            $entity->forceDelete();
        }
    }

    /**
     * Handle the entity "restoring" event.
     *
     * @param Entity  $entity
     * @return void
     */
    public function restoring($entity)
    {
        if (
            config('wk-core.class.site-cms.site')
                ::where('id', '<>', $entity->id)
                ->where('identifier', $entity->identifier)
                ->exists()
        )
            return false;
    }

    /**
     * Handle the entity "restored" event.
     *
     * @param Entity  $entity
     * @return void
     */
    public function restored($entity)
    {
        //
    }
}
