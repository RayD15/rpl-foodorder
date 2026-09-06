<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
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
        $flush = fn () => Cache::forget('catalog_ready');
        static::created($flush);
        static::updated($flush);
        static::deleted($flush);
    }

    public function isReady(): bool
    {
        return $this->status === self::STATUS_READY;
    }

    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return asset('images/placeholder.svg');
        }

        if (str_contains($this->image, '/')) {
            return Storage::url($this->image);
        }

        return asset('images/'.$this->image);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
