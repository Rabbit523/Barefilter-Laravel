<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public function getConfigurationAttribute($value)
    {
        return json_decode($value);
    }

    public function setConfigurationAttribute($value)
    {
        $this->attributes['configuration'] = json_encode($value);
    }
}