<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProviderRequest;
use App\Models\Provider;
use App\HttpResponses;
use App\Services\ProviderService;
use App\Http\Resources\ProviderResource;

class ProviderController extends Controller
{
    use HttpResponses;
    public function index()
    {
        $providers = Provider::with(['user'])->get();
        return $this->success(ProviderResource::collection($providers));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProviderRequest $request,  ProviderService $providerService)
    {
        $request->validated($request->all());

        $provider = $providerService->createProvider($request->all());


        return $this->success([
            'provider' => new ProviderResource($provider)
        ], 'provider created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Provider $provider)
    {

        return $this->success(new ProviderResource($provider));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Provider $provider)
    {
        $provider->delete();
        $provider->user->delete();

        return $this->success(null, 'provider deleted successfully');
    }
}
