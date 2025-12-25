<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\BusinessScope;

class Deal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'business_id',
        'title',
        'short_description',
        'long_description',
        'image',
        'discount',
        'original_price',
        'category_id',
        'valid_from',
        'valid_until',
        'terms_conditions',
        'is_featured',
        'status',
    ];
    protected static function booted()
    {
        static::addGlobalScope(new BusinessScope);
    }
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
