<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model {
    use HasFactory;
    protected $fillable = ['name', 'booking_limit', 'price'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_subscriptions')->withPivot('user_id', 'subscription_id');
    }
}
