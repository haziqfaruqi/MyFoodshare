<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'latitude',
        'longitude',
        'description',
        
        // Donor (Restaurant) specific fields
        'restaurant_name',
        'business_license',
        'cuisine_type',
        'restaurant_capacity',
        
        // Recipient (NGO) specific fields
        'organization_name',
        'contact_person',
        'ngo_registration',
        'dietary_requirements',
        'recipient_capacity',
        'needs_preferences',
        
        'status',
        'admin_notes',
        'approved_at',
        'approved_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dietary_requirements' => 'array',
            'approved_at' => 'datetime',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    public function foodListings()
    {
        return $this->hasMany(FoodListing::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDonor()
    {
        return $this->role === 'donor';
    }

    public function isRecipient()
    {
        return $this->role === 'recipient';
    }

    public function isRestaurantOwner()
    {
        return $this->role === 'donor'; // For backward compatibility
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return in_array($this->status, ['active']);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}