<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\HttpResponses;
use App\Http\Requests\StoreWorkerRequest;
use App\Models\Worker;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Http\Resources\WorkerResource;

class WorkerController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workers = Worker::with(['user', 'vehicleType'])->get();
        return $this->success(WorkerResource::collection($workers));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkerRequest $request)
    {
        $request->validated($request->all());

        $role = Role::where('name', 'worker')->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role->id,
        ]);

        $user->markEmailAsVerified();

        $worker = Worker::create([
            'user_id' => $user->id,
            'license_number' => $request->license_number,
            'vehicle_type_id' => $request->vehicle_type_id,
            'completed_deliveries' => $request->completed_deliveries
        ]);
        Password::sendResetLink([
            'email' => $user->email,
        ]);


        return $this->success([
            'worker' => new WorkerResource($worker)
        ], 'Worker created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Worker $worker)
    {
        return $this->success(['worker' => new WorkerResource($worker)]);
    }

    public function destroy(Worker $worker)
    {
        $worker->delete();
        $worker->user->delete();

        return $this->success(null, 'Worker deleted successfully');
    }
}
