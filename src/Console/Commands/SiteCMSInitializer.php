<?php

namespace WalkerChiu\SiteCMS\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use WalkerChiu\SiteCMS\Models\Services\EmailTemplateService;

class SiteCMSInitializer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var String
     */
    protected $signature = 'command:SiteCMSInitializer
        {--path-lang-nav=php-site-cms::nav}
        {--path-views-email=php-site-cms::emails}
        {--template-email=0}';

    /**
     * The console command description.
     *
     * @var String
     */
    protected $description = 'Initialize';



    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return Mixed
     */
    public function handle()
    {
        $this->call('command:SiteCleaner');

        $this->info('Initializing...');


        // Create Main Site
        $data = [
            'identifier'         => config('wk-site-cms.initializer.site.identifier'),
            'language'           => config('wk-site-cms.initializer.site.language'),
            'language_supported' => config('wk-site-cms.initializer.site.language_supported'),
            'timezone'           => config('wk-site-cms.initializer.site.timezone'),
            'smtp_host'          => config('wk-site-cms.initializer.site.smtp_host'),
            'smtp_port'          => config('wk-site-cms.initializer.site.smtp_port'),
            'smtp_encryption'    => config('wk-site-cms.initializer.site.smtp_encryption'),
            'smtp_username'      => config('wk-site-cms.initializer.site.smtp_username'),
            'smtp_password'      => config('wk-site-cms.initializer.site.smtp_password'),
            'is_main'            => 1
        ];

        $site = App::make(config('wk-core.class.site-cms.site'))::create($data);
        $siteLang = App::make(config('wk-core.class.site-cms.siteLang'))::create([
            'morph_type' => get_class($site),
            'morph_id'   => $site->id,
            'code'       => config('wk-site-cms.initializer.site.language'),
            'key'        => 'name',
            'value'      => config('wk-site-cms.initializer.site.name'),
            'is_current' => 1
        ]);
        $this->info(config('wk-core.table.site-cms.sites') .' have been affected.');
        $this->info(config('wk-core.table.site-cms.sites_lang') .' have been affected.');
        $this->info(config('wk-core.table.morph-image.images') .' have been affected.');
        $this->info(config('wk-core.table.morph-image.images_lang') .' have been affected.');

        if (config('wk-site-cms.initializer.site.default_data.address')) {
            $this->initializeAddress('site', $site->id);
        }
        if (config('wk-site-cms.initializer.site.default_data.email')) {
            $this->initializeEmails($site->id, $this->option('path-views-email'), $this->option('template-email'));
        }
        if (config('wk-site-cms.initializer.site.default_data.navs')) {
            $this->initializeNavs($site->id, $this->option('path-lang-nav'));
        }
        $this->initializeAccount();

        $this->info('Done!');
    }

    /**
     * Initialize Address.
     *
     * @param String  $type
     * @param Int     $id
     * @return Mixed
     */
    public function initializeAddress(string $type, int $id)
    {
        if (
            config('wk-site-cms.onoff.morph-address')
            && !empty(config('wk-core.class.morph-address.address'))
        ) {
            if ($type == 'site') {
                $address = App::make(config('wk-core.class.morph-address.address'))::create([
                    'morph_type' => config('wk-core.class.site-cms.site'),
                    'morph_id'   => $id,
                    'type'       => config('wk-site-cms.initializer.site.address.type'),
                    'phone'      => config('wk-site-cms.initializer.site.address.phone'),
                    'email'      => config('wk-site-cms.initializer.site.address.email'),
                    'area'       => config('wk-site-cms.initializer.site.address.area'),
                    'is_main'    => 1
                ]);
                $items = ['name', 'address_line1', 'address_line2', 'guide'];
                foreach ($items as $item) {
                    if (config('wk-site-cms.initializer.site.address.'.$item)) {
                        $addressLang = App::make(config('wk-core.class.morph-address.addressLang'))::create([
                            'morph_type' => get_class($address),
                            'morph_id'   => $address->id,
                            'code'       => config('wk-site-cms.initializer.site.language'),
                            'key'        => $item,
                            'value'      => config('wk-site-cms.initializer.site.address.'.$item),
                            'is_current' => 1
                        ]);
                    }
                }
            } else {
                $morph_type = config('wk-core.class.user');
                if ($type == 'profile')
                    $morph_type = config('wk-core.class.account.profile');
                $address = App::make(config('wk-core.class.morph-address.address'))::create([
                    'morph_type' => $morph_type,
                    'morph_id'   => $id,
                    'type'       => config('wk-site-cms.initializer.admin.address.type'),
                    'phone'      => config('wk-site-cms.initializer.admin.address.phone'),
                    'email'      => config('wk-site-cms.initializer.admin.address.email'),
                    'area'       => config('wk-site-cms.initializer.admin.address.area'),
                    'is_main'    => 1
                ]);
                $items = ['name', 'address_line1', 'address_line2'];
                foreach ($items as $item) {
                    if (config('wk-site-cms.initializer.admin.address.'.$item)) {
                        $addressLang = App::make(config('wk-core.class.morph-address.addressLang'))::create([
                            'morph_type' => get_class($address),
                            'morph_id'   => $address->id,
                            'code'       => config('wk-site-cms.initializer.site.language'),
                            'key'        => $item,
                            'value'      => config('wk-site-cms.initializer.admin.address.'.$item),
                            'is_current' => 1
                        ]);
                    }
                }
            }

            $this->info(config('wk-core.table.morph-address.addresses') .' have been affected.');
            $this->info(config('wk-core.table.morph-address.addresses_lang') .' have been affected.');
        } else {
            $this->line(config('wk-core.table.morph-address.addresses') .' have not been affected.');
            $this->line(config('wk-core.table.morph-address.addresses_lang') .' have not been affected.');
        }
    }

    /**
     * Initialize Emails.
     *
     * @param Int     $site_id
     * @param String  $path
     * @param Mixed   $email_template
     * @return Mixed
     */
    public function initializeEmails(int $site_id, string $path, $email_template = 0)
    {
        $types  = config('wk-core.class.site-cms.emailType')::getCodes();
        $items = ['name', 'subject', 'style', 'header', 'content', 'footer'];

        foreach ($types as $type) {
            if (config('wk-site-cms.initializer.email.'.$type.'.onoff')) {
                $email = App::make(config('wk-core.class.site-cms.email'))::create([
                    'site_id'    => $site_id,
                    'type'       => $type,
                    'serial'     => config('wk-site-cms.initializer.email.'.$type.'.serial'),
                    'is_enabled' => 1
                ]);
                foreach ($items as $item) {
                    if (in_array($item, ['style', 'header', 'content', 'footer'])) {
                        $service = new EmailTemplateService($type);
                        $value = $service->loadTemplate($item, $path, $email_template);
                        if (empty($value))
                            continue;
                    } else {
                        $value = config('wk-site-cms.initializer.email.'.$type.'.'.$item);
                    }

                    $emailLang = App::make(config('wk-core.class.site-cms.emailLang'))::create([
                        'morph_type' => get_class($email),
                        'morph_id'   => $email->id,
                        'code'       => config('wk-site-cms.initializer.site.language'),
                        'key'        => $item,
                        'value'      => $value,
                        'is_current' => 1
                    ]);
                }
            }
        }

        $this->info(config('wk-core.table.site-cms.emails') .' have been affected.');
        $this->info(config('wk-core.table.site-cms.emails_lang') .' have been affected.');
    }

    /**
     * Initialize Navs.
     *
     * @param String  $site_id
     * @param String  $path
     * @return Mixed
     */
    public function initializeNavs(string $site_id, string $path)
    {
        if (
            config('wk-site-cms.onoff.morph-nav')
            && !empty(config('wk-core.class.morph-nav.nav'))
        ) {
            $items = config('wk-site-cms.initializer.navs');
            $langs = config('wk-site-cms.initializer.site.language_supported');
            foreach ($items as $key1=>$item) {
                $nav = App::make(config('wk-core.class.morph-nav.nav'))::create([
                    'host_type'  => config('wk-core.class.site-cms.site'),
                    'host_id'    => $site_id,
                    'type'       => 'admin',
                    'identifier' => $key1,
                    'icon'       => $item['icon'],
                    'order'      => array_search($key1, array_keys($items)),
                    'is_enabled' => 1
                ]);
                foreach ($langs as $lang) {
                    App::setLocale($lang);
                    App::make(config('wk-core.class.morph-nav.navLang'))::create([
                        'morph_type' => get_class($nav),
                        'morph_id'   => $nav->id,
                        'code'       => $lang,
                        'key'        => 'name',
                        'value'      => trans($path.'.'.$key1),
                        'is_current' => 1
                    ]);
                }

                $i = 0;
                foreach ($item['data'] as $key2 => $value2) {
                    $nav2 = App::make(config('wk-core.class.morph-nav.nav'))::create([
                        'host_type'  => config('wk-core.class.site-cms.site'),
                        'host_id'    => $site_id,
                        'ref_id'     => $nav->id,
                        'type'       => 'admin',
                        'identifier' => $key1 .'-'. $key2,
                        'icon'       => $value2['icon'],
                        'order'      => $i++,
                        'is_enabled' => 1
                    ]);
                    foreach ($langs as $lang) {
                        App::setLocale($lang);
                        App::make(config('wk-core.class.morph-nav.navLang'))::create([
                            'morph_type' => get_class($nav2),
                            'morph_id'   => $nav2->id,
                            'code'       => $lang,
                            'key'        => 'name',
                            'value'      => trans($path.'.'.$key1 .'-'. $key2),
                            'is_current' => 1
                        ]);
                    }

                    $j = 0;
                    foreach ($value2['data'] as $key3 => $value3) {
                        $nav3 = App::make(config('wk-core.class.morph-nav.nav'))::create([
                            'host_type'  => config('wk-core.class.site-cms.site'),
                            'host_id'    => $site_id,
                            'ref_id'     => $nav2->id,
                            'type'       => 'admin',
                            'identifier' => $key1 .'-'. $key2 .'-'. $key3,
                            'icon'       => $value3['icon'],
                            'order'      => $j++,
                            'is_enabled' => 1
                        ]);
                        foreach ($langs as $lang) {
                            App::setLocale($lang);
                            App::make(config('wk-core.class.morph-nav.navLang'))::create([
                                'morph_type' => get_class($nav3),
                                'morph_id'   => $nav3->id,
                                'code'       => $lang,
                                'key'        => 'name',
                                'value'      => trans($path.'.'.$key1 .'-'. $key2 .'-'. $key3),
                                'is_current' => 1
                            ]);
                        }
                    }
                }
            }
            $this->info(config('wk-core.table.morph-nav.navs') .' have been affected.');
            $this->info(config('wk-core.table.morph-nav.navs_lang') .' have been affected.');
        } else {
            $this->line(config('wk-core.table.morph-nav.navs') .' have not been affected.');
            $this->line(config('wk-core.table.morph-nav.navs_lang') .' have not been affected.');
        }
    }

    /**
     * Initialize Account.
     *
     * @return Mixed
     */
    public function initializeAccount()
    {
        if (
            !empty(config('wk-site-cms.initializer.admin.password'))
            && config('wk-site-cms.onoff.user')
            && !empty(config('wk-core.class.user'))
            && config('wk-site-cms.onoff.account')
            && !empty(config('wk-core.class.account.profile'))
            && config('wk-site-cms.onoff.role')
            && !empty(config('wk-core.class.role.role'))
            && !empty(config('wk-core.class.role.permission'))
        ) {
            $user = App::make(config('wk-core.class.user'))::create([
                'name'     => config('wk-site-cms.initializer.admin.name'),
                'email'    => config('wk-site-cms.initializer.admin.email'),
                'password' => \Hash::make(config('wk-site-cms.initializer.admin.password'))
            ]);
            $profile = App::make(config('wk-core.class.account.profile'))::create([
                'user_id'  => $user->id,
                'language' => config('wk-site-cms.initializer.site.language'),
                'timezone' => config('wk-site-cms.initializer.site.timezone')
            ]);
            if (method_exists($user, 'addresses'))
                $this->initializeAddress('user', $user->id);
            else
                $this->initializeAddress('profile', $profile->id);


            // Create Roles
            $items = ['Admin', 'Manager', 'Staff', 'VIP'];
            $roles = [];
            foreach ($items as $item) {
                $role = App::make(config('wk-core.class.role.role'))::create([
                    'identifier' => strtolower($item),
                    'is_enabled' => 1
                ]);
                array_push($roles, $role);
                App::make(config('wk-core.class.role.roleLang'))::create([
                    'morph_type' => get_class($role),
                    'morph_id'   => $role->id,
                    'code'       => config('wk-site-cms.initializer.site.language'),
                    'key'        => 'name',
                    'value'      => $item,
                    'is_current' => 1
                ]);
            }
            $this->info(config('wk-core.table.user') .' have been affected.');
            $this->info(config('wk-core.table.account.profiles') .' have been affected.');
            $this->info(config('wk-core.table.role.roles') .' have been affected.');
            $this->info(config('wk-core.table.role.roles_lang') .' have been affected.');

            if (method_exists($user, 'attachRole')) {
                $user->attachRole($roles[0]);
                $this->info(config('wk-core.table.role.users_roles') .' have been affected.');
            } else {
                $this->line(config('wk-core.table.role.users_roles') .'  have not been affected.');
            }
        } else {
            $this->line(config('wk-core.table.user') .' have not been affected.');
            $this->line(config('wk-core.table.account.profiles') .' have not been affected.');
            $this->line(config('wk-core.table.role.roles') .' have not been affected.');
            $this->line(config('wk-core.table.role.roles_lang') .' have not been affected.');
            $this->line(config('wk-core.table.role.users_roles') .' have not been affected.');
        }
    }
}
