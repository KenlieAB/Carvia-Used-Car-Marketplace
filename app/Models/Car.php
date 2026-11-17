<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Car extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'price_negotiable' => 'boolean',
            'manufacture_year' => 'integer',
            'registration_year' => 'integer',
            'engine_cc' => 'integer',
            'seats' => 'integer',
            'mileage_km' => 'integer',
            'has_service_book' => 'boolean',
            'has_spare_key' => 'boolean',
            'has_faktur' => 'boolean',
            'has_form_a' => 'boolean',
            'owner_count' => 'integer',
            'tax_expiry_date' => 'date',
            'plate_expiry_year' => 'integer',
            'annual_tax_amount' => 'integer',
            'is_accident_free' => 'boolean',
            'is_flood_free' => 'boolean',
            'view_count' => 'integer',
            'published_at' => 'datetime',
            'expired_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(CarImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(CarImage::class)->where('is_primary', true);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'car_feature');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Check if car is currently active and not expired.
     */
    public function isAvailable(): bool
    {
        return $this->status === 'active' && ($this->expired_at === null || $this->expired_at->isFuture());
    }

    /**
     * Generate pre-filled WhatsApp click-to-chat URL.
     */
    public function getWhatsAppUrlAttribute(): ?string
    {
        if (! $this->user || ! $this->user->phone) {
            return null;
        }

        $phone = preg_replace('/[^0-9]/', '', $this->user->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        $message = urlencode("Halo, saya tertarik dengan mobil {$this->title} (Rp ".number_format($this->price, 0, ',', '.').") di ".config('app.name').". Apakah unit ini masih ada?");

        return "https://wa.me/{$phone}?text={$message}";
    }
}
