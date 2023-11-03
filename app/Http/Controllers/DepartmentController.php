<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $department = Department::get();
        return (new DepartmentResource(true, 'Success get all departments', $department))
            ->response()->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        try {
            $department = Department::create($request->validated());
            return (new DepartmentResource(true, 'Success create a department', $department))
                ->response()->setStatusCode(201);
        } catch (\Throwable $th) {
            return (new DepartmentResource(false, $th->getMessage(), null))
                ->response()->setStatusCode(400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department): JsonResponse
    {
        try {
            $department = Department::findOrFail($department->id);
            return (new DepartmentResource(true, 'Success get department', $department))
                ->response()->setStatusCode(200);
        } catch (\Throwable $th) {
            return (new DepartmentResource(false, $th->getMessage(), null))
                ->response()->setStatusCode(404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        try {
            $data = $request->validated();

            $department = Department::findOrFail($department->id);
            $department->fill($data);
            $department->save();

            return (new DepartmentResource(true, 'Success update a department', $department))
                ->response()->setStatusCode(200);
        } catch (\Throwable $th) {
            return (new DepartmentResource(false, $th->getMessage(), null))
                ->response()->setStatusCode(400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        try {
            $department = Department::findOrFail($department->id);
            $department->delete();

            return (new DepartmentResource(true, 'Success delete a department', null))
                ->response()->setStatusCode(200);
        } catch (\Throwable $th) {
            return (new DepartmentResource(false, $th->getMessage(), null))
                ->response()->setStatusCode(400);
        }
    }
}
