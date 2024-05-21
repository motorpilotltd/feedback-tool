<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.title', 'MP FeedBack App');
        $this->migrator->add('general.welcome_title', 'MP Feedback');
        $this->migrator->add('general.welcome_description', 'Welcome! This is your place to suggest ideas or vote for ideas for improving our applications. Please select the product you would like to provide feedback for below to view and submit your ideas.');
        $this->migrator->add('general.language', 'english');
        $this->migrator->add('general.app_email', 'noreply@example.com');
        $this->migrator->add('general.pagination', 25);
        $this->migrator->add('general.smtp_host', '');
        $this->migrator->add('general.smtp_port', '');
        $this->migrator->add('general.smtp_user', '');
        $this->migrator->add('general.smtp_password', '');
        $this->migrator->add('general.forcelogin', false);
        $this->migrator->add('general.ga_measurement_id', '');
    }
}
