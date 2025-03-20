<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\HttpResponses;
use App\Models\Role;
use App\Http\Resources\RoleResource;

class RoleController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return $this->success(RoleResource::collection($roles));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
    }
}
