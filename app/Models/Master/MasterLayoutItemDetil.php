<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class MasterLayoutItemDetil extends Model
{
    protected $table = 'master_layout_item_detil';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'layout_id',
        'layout_main_id',
        'layout_item_id',
        'key',
        'label',
        'source_table',
        'source_field',
        'amount',
    ];

    public function Layout()
    {
        return $this->belongsTo(MasterLayOut::class, 'layout_id', 'id');
    }

    public function Main()
    {
        return $this->belongsTo(MasterLayoutMain::class, 'layout_main_id', 'id');
    }
}
