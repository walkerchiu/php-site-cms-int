<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SiteCMS: Email
    |--------------------------------------------------------------------------
    |
    */

    'site_id'     => '站臺 ID',
    'site_name'   => '站臺名稱',
    'type'        => '種類',
    'serial'      => '編號',
    'is_enabled'  => '是否啟用',

    'name'        => '名稱',
    'description' => '描述',
    'subject'     => '主題',
    'content'     => '內容',

    'list'   => 'Email 樣板清單',
    'create' => '建立 Email 樣板',
    'edit'   => 'Email 樣板修改',

    'form' => [
        'email' => [
            'information' => 'Email',
                'basicInfo' => '基本資訊',
                'body'      => '寄出內容'
        ],
        'shared' => [
            'header' => 'Email 共用設定'
        ]
    ],

    'delete' => [
        'header' => '刪除樣板',
        'body'   => '確定要刪除這項樣板嗎？'
    ],

    'emailType' => [
        'general'        => '一般通知',
        'verifyEmail'    => '驗證電子信箱',
        'emailVerified'  => '信箱驗證成功通知',
        'registered'     => '會員註冊通知',
        'login'          => '登入成功通知',
        'loginFailed'    => '登入失敗通知',
        'passwordForgot' => '重置密碼確認',
        'passwordReset'  => '密碼重置成功通知'
    ]
];
