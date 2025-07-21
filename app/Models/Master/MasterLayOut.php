<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MasterLayOut extends Model
{
     protected $table = 'master_layout';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'lock_flag',
        'name',
        'remark',
        'user_created',
        'user_edit',
        'created_at',
    ];

    public function UserCreated()
    {
        return $this->belongsTo(User::class, 'user_created', 'id');
    }

    public function UserEdit()
    {
        return $this->belongsTo(User::class, 'user_edit', 'id');
    }
}
