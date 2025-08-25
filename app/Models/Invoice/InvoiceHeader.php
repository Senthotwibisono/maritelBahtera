<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;
use App\Models\Master\MasterCountry;
use App\Models\Master\MasterPort;
use App\Models\User;
Use App\Models\VVoyage;
class InvoiceHeader extends Model
{
    protected $table = 'invoice_header';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'status',
        'layout_id',
        'ves_id',
        'ves_name',
        'ves_code',
        'dwt',
        'grt',
        'nrt',
        'loa',
        'breadth',
        'owner',
        'country_id',
        'voy',
        'exchange_rate',
        'reference_no',
        'invoice_date',
        'port_of_call',
        'purpose_of_call',
        'activity',
        'cargo',
        'volume',
        'est_port_stay',
        'idr_amount',
        'idr_fund_amount',
        'idr_balance_due',
        'usd_amount',
        'usd_fund_amount',
        'usd_balance_due',
        'created_at',
        'user_id',
        'updated_at',
        'last_user_updated',
        'voy_id'
    ];

    public function Negara()
    {
        return $this->belongsTo(MasterCountry::class, 'country_id', 'id');
    }

    public function Port()
    {
        return $this->belongsTo(MasterPort::class, 'port_of_call', 'id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function UserUpdate()
    {
        return $this->belongsTo(User::class, 'last_user_updated', 'id');
    }

    public function Voy()
    {
        return $this->belongsTo(VVoyage::class, 'voy_id', 'id');
    }
}
