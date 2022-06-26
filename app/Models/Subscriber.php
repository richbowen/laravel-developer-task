<?php

namespace App\Models;

use App\Enums\SubscriberState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 'name', 'state'
    ];

    protected $state = [
        'state' => SubscriberState::class
    ];

    public function fields()
    {
        return $this->belongsToMany(Field::class, 'subscriber_field');
    }
}
