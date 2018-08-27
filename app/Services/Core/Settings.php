<?php

namespace App\Services\Core;

use App\Models\Core\Setting;

class Settings
{
    public static function getSiteConfiguration()
    {
        $record = Setting::find(1);
        return $record->configuration;
    }

    public static function get()
    {
        return Setting::find(1);
    }

    public static function update($data)
    {
        $configuration = json_decode($data["configuration"]);
        $settings = Setting::find(1);
        $settings->configuration = $configuration;
        $settings->save();
        return $settings;
    }
}
