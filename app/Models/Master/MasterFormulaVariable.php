<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class MasterFormulaVariable extends Model
{
    protected $table = 'master_formula_variable';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'key',
        'label',
        'source_table',
        'source_field',
        'description',
    ];
}
