<?php 
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'password'];

    // Define relationship to Role
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    // Check if user has a specific role
    public function hasRole($roleName): bool
    {
        return $this->roles->contains('name', $roleName);
    }
    public function tenants()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

}
