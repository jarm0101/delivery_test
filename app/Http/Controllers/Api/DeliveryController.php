<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeliveryRequest;
use App\Http\Requests\UpdateDeliveryRequest;
use App\Http\Resources\DeliveryResource;
use App\HttpResponses;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class DeliveryController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $query = Delivery::UserRole()
            ->JoinProvider()
            ->ProviderName($request->provider_name)
            ->RecieverName($request->reciver_name)
            ->Code($request->code)
            ->StartDate($request->start_date)
            ->EndDate($request->end_date)
            ->with('provider.user', 'worker.user');

        if ($request->order_by) {
            $query->orderBy($request->order_by, $request->direction ?? 'asc');
        }

        return $this->success(
            [
                'deliveries' => DeliveryResource::collection($query->paginate($perPage))
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeliveryRequest $request)
    {
        $request->validated($request->all());

        $code = 'DLV-' . str_pad((Delivery::max('id') ?? 0) + 1, 6, '0', STR_PAD_LEFT);

        $delivery = Delivery::create([
            'code' => $code,
            'provider_id' => $request->provider_id,
            'worker_id' => $request->worker_id,
            'receiver_name' => $request->receiver_name,
            'receiver_address' => $request->receiver_address,
            'delivery_date' => $request->delivery_date,
        ]);

        return $this->success([
            'delivery' => new DeliveryResource($delivery)
        ], 'Delivery created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Delivery $delivery)
    {
        $delivery->load('provider', 'worker');

        return $this->success([
            'delivery' => new DeliveryResource($delivery)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeliveryRequest $request, Delivery $delivery)
    {
        $request->validated($request->all());

        $delivery->update($request->all());

        return $this->success([
            'delivery' => new DeliveryResource($delivery)
        ], 'Delivery updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Delivery $delivery)
    {
        $delivery->delete();

        return $this->success([], 'Delivery deleted successfully');
    }

    public function exportPdf(Request $request)
    {
        $query = Delivery::UserRole()
            ->JoinProvider()
            ->ProviderName($request->provider_name)
            ->RecieverName($request->reciver_name)
            ->Code($request->code)
            ->StartDate($request->start_date)
            ->EndDate($request->end_date)
            ->with('provider.user', 'worker.user');

        $deliveriesGrouped = $query->get()->groupBy(function ($delivery) {
            return $delivery->provider->user->name;
        });

        $pdf = Pdf::loadView('report.deliveries', [
            'deliveriesGrouped' => $deliveriesGrouped,
            'startDate' => $request->start_date,
            'endDate' => $request->end_date,
        ]);
        $path = storage_path('app/public/reports/deliveries_' . now()->format('Ymd_His') . '.pdf');
        $pdf->save($path);

        return $this->success([
            'url' => asset('storage/reports/' . basename($path))
        ], 'report generated successfully');
    }
}
