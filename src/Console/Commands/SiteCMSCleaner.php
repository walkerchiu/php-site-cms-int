<?php

namespace WalkerChiu\SiteCMS\Console\Commands;

use WalkerChiu\Core\Console\Commands\Cleaner;

class SiteCMSCleaner extends Cleaner
{
    /**
     * The name and signature of the console command.
     *
     * @var String
     */
    protected $signature = 'command:SiteCMSCleaner';

    /**
     * The console command description.
     *
     * @var String
     */
    protected $description = 'Truncate tables';

    /**
     * Execute the console command.
     *
     * @return Mixed
     */
    public function handle()
    {
        parent::clean('site-cms');
    }
}
