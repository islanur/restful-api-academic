<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\AllResource;

class RoleController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $role = Role::get();
    return (new AllResource(true, 'Success get all roles', $role))
      ->response()->setStatusCode(200);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreRoleRequest $request)
  {
    try {
      $role = Role::create($request->validated());
      return (new AllResource(true, 'Success create a department', $role))
        ->response()->setStatusCode(201);
    } catch (\Throwable $th) {
      return (new AllResource(false, $th->getMessage(), null))
        ->response()->setStatusCode(400);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Role $role)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateRoleRequest $request, Role $role)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Role $role)
  {
    //
  }
}
