<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SMPTModel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Check if the table exists
        if (Schema::hasTable('smpt_setting_table')) {
            // Attempt to retrieve SMTP settings
            $smtpSetting = SMPTModel::where('status', '1')->first();

            if ($smtpSetting) {
                Config::set('mail.mailers.smtp.host', $smtpSetting->mail_host);
                Config::set('mail.mailers.smtp.port', $smtpSetting->mail_port);
                Config::set('mail.mailers.smtp.username', $smtpSetting->mail_username);
                Config::set('mail.mailers.smtp.password', $smtpSetting->mail_password);
                Config::set('mail.mailers.smtp.encryption', $smtpSetting->mail_encryption);
                Config::set('mail.from.address', $smtpSetting->mail_from_address);
                Config::set('mail.from.name', $smtpSetting->mail_from_name);
            }
        }
    }
}
