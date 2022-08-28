<?php

namespace WalkerChiu\SiteCMS\Events\Subscribers;

class UserEventSubscriber
{
    /**
     * Register the handlers for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        if (config('wk-site-cms.register_event.VerifyEmail'))
            $events->listen(
                'WalkerChiu\SiteCMS\Events\VerifyEmail',
                'WalkerChiu\SiteCMS\Events\Handlers\EmailVerificationNotification'
            );

        if (config('wk-site-cms.register_event.EmailVerified'))
            $events->listen(
                'WalkerChiu\SiteCMS\Events\EmailVerified',
                'WalkerChiu\SiteCMS\Events\Handlers\EmailVerificationNotification'
            );

        if (config('wk-site-cms.register_event.PasswordForgot'))
            $events->listen(
                'WalkerChiu\SiteCMS\Events\PasswordForgot',
                'WalkerChiu\SiteCMS\Events\Handlers\EmailVerificationNotification'
            );

        if (config('wk-site-cms.register_event.PasswordReset'))
            $events->listen(
                'WalkerChiu\SiteCMS\Events\PasswordReset',
                'WalkerChiu\SiteCMS\Events\Handlers\EmailVerificationNotification'
            );

        if (config('wk-site-cms.register_event.Registered'))
            $events->listen(
                'WalkerChiu\SiteCMS\Events\Registered',
                'WalkerChiu\SiteCMS\Events\Handlers\RegisteredNotification'
            );

        if (config('wk-site-cms.register_event.Authenticated'))
            $events->listen(
                'WalkerChiu\SiteCMS\Events\Authenticated',
                'WalkerChiu\SiteCMS\Events\Handlers\AuthenticatedNotification'
            );
    }
}
