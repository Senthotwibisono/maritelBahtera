<?php

namespace App\Models\Master;

use App\Models\Master\MasterCountry;

use Illuminate\Database\Eloquent\Model;

class MasterPort extends Model
{
    protected $table = 'master_port';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
        'country_id',
        'description',
    ];

    public function Negara()
    {
        return $this->belongsTo(MasterCountry::class, 'country_id', 'id')->orderBy('code', 'asc');
    }
}
