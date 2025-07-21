<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MasterLayoutMain extends Model
{
    protected $table = 'master_layout_main';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'layout_id',
        'name',
        'currency_flag',
        'uid',
        'order',
        'vat',
        'admin_nota',
        'admin_it',
        'amount'
    ];

    public function Layout()
    {
         return $this->belongsTo(MasterLayOut::class, 'layout_id', 'id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'uid', 'id');
    }
}
