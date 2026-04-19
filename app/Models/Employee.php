<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'role', 'shift',
        'salary', 'hired_at', 'is_active'
    ];

    protected $casts = [
        'salary'    => 'decimal:2',
        'hired_at'  => 'date',
        'is_active' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class, 'assigned_to');
    }

    public function getRoleBadgeAttribute(): string
    {
        return match($this->role) {
            'admin'   => 'bg-red-100 text-red-700',
            'manager' => 'bg-purple-100 text-purple-700',
            'barista' => 'bg-amber-100 text-amber-700',
            'cashier' => 'bg-blue-100 text-blue-700',
            default   => 'bg-gray-100 text-gray-700',
        };
    }
}
