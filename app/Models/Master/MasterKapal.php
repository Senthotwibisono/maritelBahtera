<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class MasterKapal extends Model
{
    protected $table = 'master_kapal';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
        'dwt',
        'grt',
        'nrt',
        'loa',
        'breadth',
        'owner',
        'country_id',
    ];

    public function Negara()
    {
        return $this->belongsTo(MasterCountry::class, 'country_id', 'id');
    }
}
