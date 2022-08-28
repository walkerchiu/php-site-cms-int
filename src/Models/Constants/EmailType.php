<?php

namespace WalkerChiu\SiteCMS\Models\Constants;

/**
 * @license MIT
 * @package WalkerChiu\SiteCMS
 *
 *
 */

class EmailType
{
    /**
     * @return Array
     */
    public static function getCodes(): array
    {
        $items = [];
        $types = self::all();
        foreach ($types as $code => $type) {
            array_push($items, $code);
        }

        return $items;
    }

    /**
     * @param Bool  $onlyVaild
     * @return Array
     */
    public static function options($onlyVaild = false): array
    {
        $items = $onlyVaild ? [] : ['' => trans('php-core::system.null')];
        $types = self::all();
        foreach ($types as $key => $value) {
            $lang = trans('php-site-cms::email.emailType.'.$key);
            $items = array_merge($items, [$key => $lang]);
        }

        return $items;
    }

    /**
     * @return Array
     */
    public static function all(): array
    {
        return [
            'general'        => 'General notice',
            'verifyEmail'    => 'Verify email address',
            'emailVerified'  => 'Notify when email address is verified',
            'registered'     => 'Verify email when sign up',
            'login'          => 'Notify when sign in suceesfully',
            'loginFailed'    => 'Notify when sign in failed',
            'passwordForgot' => 'Verify email when forgot password',
            'passwordReset'  => 'Notify when password is reset'
        ];
    }
}
