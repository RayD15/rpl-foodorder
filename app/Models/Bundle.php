<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Bundle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'status',
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    public const STATUS_READY = 'ready';

    public const STATUS_SOLD_OUT = 'sold_out';

    protected static function booted(): void
    {
        $flush = fn () => Cache::forget('bundles_ready');
        static::created($flush);
        static::updated($flush);
        static::deleted($flush);
    }

    public function isReady(): bool
    {
        return $this->status === self::STATUS_READY;
    }

    public function items(): HasMany
    {
        return $this->hasMany(BundleItem::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'bundle_items')
            ->withPivot('qty');
    }

    /**
     * Total harga normal (tanpa diskon) dari semua item dalam bundle.
     */
    public function getRegularTotalAttribute(): int
    {
        return $this->items->sum(fn ($item) => $item->product?->price * $item->qty) ?? 0;
    }

    /**
     * Besar penghematan (harga normal - harga paket).
     */
    public function getSavingsAttribute(): int
    {
        return max(0, $this->regular_total - $this->price);
    }

    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return asset('images/placeholder.svg');
        }

        // Check if image exists in public
        if (file_exists(public_path('images/'.$this->image))) {
            return asset('images/'.$this->image);
        }

        // Check if image exists in storage (for backward compatibility)
        if (str_starts_with($this->image, 'products/')) {
            $storagePath = storage_path('app/public/'.$this->image);
            if (file_exists($storagePath)) {
                return asset('storage/'.$this->image);
            }
        }

        return asset('images/placeholder.svg');
    }
}
