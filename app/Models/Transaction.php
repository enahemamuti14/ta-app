<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'amount',
        'date',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
