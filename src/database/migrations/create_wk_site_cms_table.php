<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateWkSiteCMSTable extends Migration
{
    public function up()
    {
        Schema::create(config('wk-core.table.site-cms.sites'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->nullable();
            $table->string('serial')->nullable();
            $table->string('identifier');
            $table->string('language', 5)->default(config('wk-core.language'));
            $table->json('language_supported')->nullable();
            $table->string('timezone')->default(config('wk-core.timezone'));
            $table->string('smtp_host')->nullable();
            $table->string('smtp_port')->nullable();
            $table->string('smtp_encryption')->nullable();
            $table->string('smtp_username')->nullable();
            $table->string('smtp_password')->nullable();
            $table->text('email_theme')->nullable();
            $table->text('layout_theme')->nullable();
            $table->string('view_template')->nullable();
            $table->string('email_template')->nullable();
            $table->string('skin')->nullable();
            $table->text('script_head')->nullable();
            $table->text('script_footer')->nullable();
            $table->json('options')->nullable();
            $table->boolean('can_guestComment')->default(1);
            $table->boolean('is_main')->default(0);
            $table->boolean('is_enabled')->default(0);

            $table->timestampsTz();
            $table->softDeletes();

            $table->index('type');
            $table->index('serial');
            $table->index('identifier');
            $table->index('language');
            $table->index('view_template');
            $table->index('email_template');
            $table->index('is_main');
            $table->index('is_enabled');
        });
        if (!config('wk-site-cms.onoff.core-lang_core')) {
            Schema::create(config('wk-core.table.site-cms.sites_lang'), function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->morphs('morph');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('code');
                $table->string('key');
                $table->longText('value')->nullable();
                $table->boolean('is_current')->default(1);

                $table->timestampsTz();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')
                    ->on(config('wk-core.table.user'))
                    ->onDelete('set null')
                    ->onUpdate('cascade');
            });
        }

        Schema::create(config('wk-core.table.site-cms.layouts'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('site_id');
            $table->string('type', 10);
            $table->string('serial')->nullable();
            $table->string('identifier');
            $table->text('script_head')->nullable();
            $table->text('script_footer')->nullable();
            $table->json('options')->nullable();
            $table->unsignedBigInteger('order')->nullable();
            $table->boolean('is_highlighted')->default(0);
            $table->boolean('is_enabled')->default(0);

            $table->timestampsTz();
            $table->softDeletes();

            $table->foreign('site_id')->references('id')
                  ->on(config('wk-core.table.site-cms.sites'))
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->index('serial');
            $table->index('identifier');
            $table->index('is_highlighted');
            $table->index('is_enabled');
        });
        if (!config('wk-site-cms.onoff.core-lang_core')) {
            Schema::create(config('wk-core.table.site-cms.layouts_lang'), function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->morphs('morph');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('code');
                $table->string('key');
                $table->longText('value')->nullable();
                $table->boolean('is_current')->default(1);

                $table->timestampsTz();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')
                    ->on(config('wk-core.table.user'))
                    ->onDelete('set null')
                    ->onUpdate('cascade');
            });
        }

        Schema::create(config('wk-core.table.site-cms.emails'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('site_id');
            $table->string('type', 20);
            $table->string('serial')->nullable();
            $table->boolean('is_enabled')->default(0);

            $table->timestampsTz();
            $table->softDeletes();

            $table->foreign('site_id')->references('id')
                  ->on(config('wk-core.table.site-cms.sites'))
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->index('serial');
            $table->index('is_enabled');
        });
        if (!config('wk-site-cms.onoff.core-lang_core')) {
            Schema::create(config('wk-core.table.site-cms.emails_lang'), function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->morphs('morph');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('code');
                $table->string('key');
                $table->longText('value')->nullable();
                $table->boolean('is_current')->default(1);

                $table->timestampsTz();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')
                    ->on(config('wk-core.table.user'))
                    ->onDelete('set null')
                    ->onUpdate('cascade');
            });
        }
    }

    public function down() {
        Schema::dropIfExists(config('wk-core.table.site-cms.emails_lang'));
        Schema::dropIfExists(config('wk-core.table.site-cms.emails'));
        Schema::dropIfExists(config('wk-core.table.site-cms.layouts_lang'));
        Schema::dropIfExists(config('wk-core.table.site-cms.layouts'));
        Schema::dropIfExists(config('wk-core.table.site-cms.sites_lang'));
        Schema::dropIfExists(config('wk-core.table.site-cms.sites'));
    }
}
