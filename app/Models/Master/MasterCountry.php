<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class MasterCountry extends Model
{
    protected $table = 'master_country';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
    ];
}
