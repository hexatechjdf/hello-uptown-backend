<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscription extends Model
{
    protected $fillable = [
        'email',
        'subscribed_at',
        'unsubscribed_at',
        'status',
    ];

    protected $casts = [
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
   public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSubscribed($query)
    {
        return $query->whereNull('unsubscribed_at');
    }

    public function scopeByEmail($query, $email)
    {
        return $query->where('email', $email);
    }

    // Methods
    public function unsubscribe(): void
    {
        $this->update([
            'status' => 'unsubscribed',
            'unsubscribed_at' => now(),
        ]);
    }

    public function resubscribe(): void
    {
        $this->update([
            'status' => 'active',
            'unsubscribed_at' => null,
        ]);
    }

    public function markAsBounced(): void
    {
        $this->update([
            'status' => 'bounced',
        ]);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->unsubscribed_at === null;
    }

    public function isUnsubscribed(): bool
    {
        return $this->status === 'unsubscribed' || $this->unsubscribed_at !== null;
    }
}
