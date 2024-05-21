<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Exception;
use App\Settings\GeneralSettings;
use App\Settings\AzureADSettings;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('const.APP_FORCE_HTTPS')) {
            URL::forceScheme('https');
        }

        try {
            // Ensure there is a DB connection and class exist before retrieving settings
            if (DB::connection()->getPDO() ) {

                // Overriding Azure .env default configs for System azure settings
                if (class_exists(AzureADSettings::class)) {
                    $azureSettings = resolve(AzureADSettings::class);
                    $servicesAzure = 'services.azure.';
                    if ($data = $azureSettings->toArray()) {
                        $config = [];
                        foreach ($data as $key => $val) {
                            if (config()->has($servicesAzure . $key) && !empty($val)) {
                                $config[$servicesAzure . $key] = $val;
                            }
                        }
                        if ($config) {
                            config($config);
                        }
                    }
                }

                // Overriding SMTP .env default configs with settings
                if (class_exists(GeneralSettings::class)) {
                    $generalSettings = resolve(GeneralSettings::class);
                    $mailers = 'mail.mailers.';
                    if ($data = $generalSettings->toArray()) {
                        $config = [];
                        foreach ($data as $key => $val) {
                            $key = Str::replace('_', '.', $key);
                            if (config()->has($mailers . $key) && !empty($val)) {
                                $config[$mailers . $key] = $val;
                            }
                        }
                        if ($config) {
                            config($config);
                        }
                    }
                }
            }

        } catch (Exception $e) {}

        // Laravel strict for development
        Model::shouldBeStrict(!$this->app->isProduction());
        /**  Macros */
        Builder::macro('search', function ($field, $string) {
            return $string ? $this->where($field, 'like', '%'.$string.'%') : $this;
        });

        Builder::macro('toCsv', function () {
            $results = $this->get();

            if ($results->count() < 1) return;

            $titles = implode(',', array_keys((array) $results->first()->getAttributes()));

            $values = $results->map(function ($result) {
                return implode(',', collect($result->getAttributes())->map(function ($thing) {
                    return '"'.$thing.'"';
                })->toArray());
            });

            $values->prepend($titles);

            return $values->implode("\n");
        });

        Collection::macro('toCsv', function () {
            if ($this->count() < 1) {
                return '';
            }

            // Extract column names from the first item
            $titles = implode(',', array_keys($this->first()));

            // Convert each item to a CSV row
            $values = $this->map(function ($item) {

                return implode(',', array_map(function ($value) {

                    if (is_array($value)) {
                        if (isset($value['name'])) {
                            $value = $value['name'];
                        } else if (isset($value['title'])) {
                            $value = $value['title'];
                        } else {
                            $value = '-';
                        }
                    }
                    // Double quotes for CSV and escape inner quotes by doubling them
                    return '"' . str_replace('"', '""', $value) . '"';
                }, $item));
            });

            // Add the titles as the first row
            $values->prepend($titles);

            // Concatenate all rows with newlines
            return $values->implode("\n");
        });
    }
}
