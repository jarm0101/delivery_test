<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public function scopeUserRole($query)
    {
        $user = Auth::user();
        if ($user->hasRole('worker')) {
            return $query->whereWorkerId($user->worker->id);
        }
        if ($user->hasRole('provider')) {
            return $query->whereProviderId($user->provider->id);
        }
        return $query;
    }

    public function scopeJoinProvider($query)
    {
        return $query->join('providers as p', 'deliveries.provider_id', '=', 'p.id')
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->select('deliveries.*');
    }
    public function scopeProviderName($query, $filter)
    {
        if (!is_null($filter)) {
            return $query->where('u.name', 'like', '%' . $filter . '%');
        }
        return $query;
    }

    public function scopeRecieverName($query, $filter)
    {
        if (!is_null($filter)) {
            return $query->where('reciever_name', 'like', '%' . $filter . '%');
        }
        return $query;
    }

    public function scopeCode($query, $filter)
    {
        if (!is_null($filter)) {
            return $query->where('code', 'like', '%' . $filter . '%');
        }
        return $query;
    }

    public function scopeStartDate($query, $filter)
    {
        if (!is_null($filter)) {
            return $query->where('delivery_date', '>=', $filter);
        }
        return $query;
    }

    public function scopeEndDate($query, $filter)
    {
        if (!is_null($filter)) {
            return $query->where('delivery_date', '<=', $filter);
        }
        return $query;
    }
}
