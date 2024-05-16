<?php

namespace App\Http\Controllers;

use App\Http\Requests\Attendance\ClockInQueryRequest;
use App\Http\Requests\Attendance\ClockInRequest;
use App\Http\Requests\StoreattendanceRequest;
use App\Http\Requests\UpdateattendanceRequest;
use App\Models\attendance;
use App\Services\AttendanceService;
use Illuminate\Http\JsonResponse;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreattendanceRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateattendanceRequest $request, attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(attendance $attendance)
    {
        //
    }

    /**
     * Handles the worker clock-in request.
     *
     * @param ClockInRequest $request The validated request.
     * @return JsonResponse
     */
    public function ClockIn(ClockInRequest $request): JsonResponse
    {
        $res = $this->attendanceService->ClockIn($request->validated());
        if ($res['status'] == 'error') {
            return response()->json($res, 400);
        }
        return response()->json($res, 200);
    }

    /**
     * Handles the worker clock-out request.
     *
     * @param ClockInQueryRequest $request The validated request.
     * @return JsonResponse
     * */
    public function getWorkerClockIn(ClockInQueryRequest $request): JsonResponse
    {
        $res = $this->attendanceService->getWorkerClockIns($request->validated());
        return response()->json($res, 200);
    }

}
