<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeliveryQueryFilter
{
    public static function apply($query, Request $request)
    {
        if ($request->has('provider_name')) {
            $query->whereHas('provider.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->get('provider_name') . '%');
            });
        }

        if ($request->has('receiver_name')) {
            $query->where('receiver_name', 'like', '%' . $request->input('receiver_name') . '%');
        }

        if ($request->has('code')) {
            $query->where('code', 'like', '%' . $request->get('code') . '%');
        }

        if ($request->has('start_date')) {
            $query->where('delivery_date', '>=', $request->get('start_date'));
        }

        if ($request->has('end_date')) {
            $query->where('delivery_date', '<=', $request->get('end_date'));
        }

        if ($request->has('order_by')) {
            $direction = $request->get('direction', 'asc');
            $orderBy = $request->get('order_by');

            if ($orderBy === 'provider_name') {
                $query->join('providers', 'deliveries.provider_id', '=', 'providers.id')
                    ->join('users as provider_users', 'providers.user_id', '=', 'provider_users.id')
                    ->orderBy('provider_users.name', $direction)
                    ->select('deliveries.*');
            } elseif ($orderBy === 'code') {
                $query->orderBy('code', $direction);
            } else {
                $query->orderBy($orderBy, $direction);
            }
        }

        return $query;
    }
}
