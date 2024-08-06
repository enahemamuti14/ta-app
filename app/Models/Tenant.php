<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    // Nama tabel jika tidak mengikuti konvensi Laravel
    protected $table = 'tenant';

    // Kolom-kolom yang diizinkan untuk mass assignment
    protected $fillable = [
        'tanggalmulaisewa',
        'tanggalberakhirsewa',
        'namatenant',
        'statussewa',
        'kontak',
    ];

    // Jika menggunakan tipe data khusus, tentukan di sini
    protected $casts = [
        'tanggalmulaisewa' => 'date',
        'tanggalberakhirsewa' => 'date',
    ];

    // Relasi ke model lain (jika diperlukan)
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function menus()
    {
        return $this->hasMany(Menu::class, 'tenant_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'tenant_id');
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'tenant_id');
    }
}
