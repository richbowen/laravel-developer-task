<?php

namespace App\Models;

use App\Enums\FieldType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'type'
    ];

    protected $casts = [
        'type' => FieldType::class
    ];

    protected $hidden = ['pivot'];

    public function subscribers()
    {
        return $this->belongsToMany(Subscriber::class, 'subscriber_field');
    }
}
