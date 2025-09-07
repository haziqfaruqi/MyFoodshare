<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodMatch extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'food_listing_id',
        'recipient_id',
        'status',
        'distance',
        'matched_at',
        'approved_at',
        'pickup_scheduled_at',
        'completed_at',
        'qr_code',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'matched_at' => 'datetime',
            'approved_at' => 'datetime',
            'pickup_scheduled_at' => 'datetime',
            'completed_at' => 'datetime',
            'distance' => 'decimal:2',
        ];
    }

    public function foodListing()
    {
        return $this->belongsTo(FoodListing::class);
    }

    public function listing()
    {
        return $this->belongsTo(FoodListing::class, 'food_listing_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function tracking()
    {
        return $this->hasMany(Tracking::class);
    }

    public function generateQrCode()
    {
        $this->qr_code = 'QR-' . strtoupper(substr(md5($this->id . time()), 0, 8));
        $this->save();
        return $this->qr_code;
    }
}