<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreVehicleTypeRequest;
use App\Http\Requests\UpdateVehicleTypeRequest;
use App\Http\Resources\VehicleTypeResource;
use App\HttpResponses;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use App\Models\VehicleType;


class VehicleTypeController extends Controller
{
    use AuthorizesRequests, HttpResponses;

    public function __construct()
    {
        $this->authorizeResource(VehicleType::class, 'vehicle');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicles = VehicleType::all();
        return $this->success([
            'vehichle_types' => VehicleTypeResource::collection($vehicles)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleTypeRequest $request)
    {
        $request->validated($request->all());
        $vehicle = VehicleType::create($request->all());
        return $this->success([
            'vehicle_type' => new VehicleTypeResource($vehicle)
        ], 'Vehicle type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleType $vehicle)
    {
        return $this->success([
            'vehicle_type' => new VehicleTypeResource($vehicle)
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleTypeRequest $request, VehicleType $vehicle)
    {
        $request->validated($request->all());

        $vehicle->update($request->all());

        return $this->success([
            'vehicle_type' => new VehicleTypeResource($vehicle)
        ], 'Vehicle type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleType $vehicle)
    {
        $vehicle->delete();
        return $this->success([], 'Vehicle type deleted successfully.');
    }
}
