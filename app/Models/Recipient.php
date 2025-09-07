<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'capacity',
        'dietary_requirements',
        'rating',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'dietary_requirements' => 'array',
            'rating' => 'decimal:1',
        ];
    }

    public function matches()
    {
        return $this->hasMany(\App\Models\FoodMatch::class);
    }

    public function isActive()
    {
        return $this->status === 'active';
    }
}