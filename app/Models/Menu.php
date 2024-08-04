<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'price',
        'description',
        'image_url',
        'category', 
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
