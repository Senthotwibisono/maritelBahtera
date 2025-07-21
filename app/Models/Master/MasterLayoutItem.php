<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class MasterLayoutItem extends Model
{
    protected $table = 'master_layout_item';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'layout_id',
        'layout_main_id',
        'item_id',
        'name',
        'unit',
        'remark',
        'formula',
        'amount',
        'order',
    ];

    public function Layout()
    {
        return $this->belongsTo(MasterLayOut::class, 'layout_id', 'id');
    }

    public function Main()
    {
        return $this->belongsTo(MasterLayoutMain::class, 'layout_main_id', 'id');
    }

    public function Mitem()
    {
        return $this->belongsTo(MasterItem::class, 'item_id', 'id');
    }
}
