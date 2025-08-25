<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Master\MasterKapal;

class VVoyage extends Model
{
    protected $table = 'vessel_voyage';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'master_id',
        'eta',
        'etd',
        'arrival_date',
        'departure_date',
        'start_work_date',
        'clossing_date',
        'cargo_clossing_date',
        'voy_no',
        'user_id',
        'create_at',
    ];

    public function Master()
    {
        return $this->belongsTo(MasterKapal::class, 'master_id', 'id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
