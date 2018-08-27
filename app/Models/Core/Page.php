<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
          /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'pages';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = ['handle', 'content','created_at', 'updated_at'];
}
