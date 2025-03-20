<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    /** @use HasFactory<\Database\Factories\DeliveryFactory> */
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'provider_id',
        'receiver_name',
        'receiver_address',
        'delivery_date',
        'code'
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
