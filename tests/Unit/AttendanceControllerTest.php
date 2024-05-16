<?php

namespace Tests\Unit;

use App\Http\Controllers\AttendanceController;
use App\Models\Attendance;
use App\Models\Worker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\AttendanceService;
use Mockery;

class AttendanceControllerTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function testValidClockIn()
    {
        $worker = Worker::create(['name' => 'John Doe']);
        $attendanceService = app(AttendanceService::class); // Dependency Injection
        $data = [
            "worker_id" =>$worker->id,
            'timestamp' => time(), // Current timestamp
            'latitude' => 30.04951367581298,  // Sample latitude
            'longitude' => 31.240338786508516, // Sample longitude
        ];
        $result = $attendanceService->ClockIn($data);


        $this->assertEquals('success', $result['status']);
        $this->assertEquals('checked in', $result['message']);
        $this->assertArrayHasKey('id', $result['data']); // Check for unique ID

    }
    /** @test */
    public function testExceedingDistance()
    {
        $worker = Worker::create(['name' => 'John Doe']);
        $attendanceService = app(AttendanceService::class);

        $data = [
            "worker_id" =>$worker->id,
            'timestamp' => time(),
            'latitude' => 100,
            'longitude' => 100,
        ];

        $result = $attendanceService->ClockIn($data);

        $this->assertEquals('error', $result['status']);
        $this->assertEquals('rejected to check in', $result['message']);
    }

    /** @test  */
    public function testGetWorkerClockInsValidId()
    {
        $attendanceService = app(AttendanceService::class);
        // Create a sample worker and clock-in records
        $worker = Worker::create(['name' => 'John Doe']);
        $attendanceData = [
            "worker_id" =>$worker->id,
            'timestamp' => time(),
            'latitude' => 30.04951367581298,
            'longitude' => 31.240338786508516,
            'type' => 'clock-in',

        ];
        Attendance::create($attendanceData);

        $data = ['worker_id' => $worker->id];
        $result = $attendanceService->getWorkerClockIns($data);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result); // Check for at least one clock-in
    }
    /** @test  */
    public function testGetWorkerClockInsInvalidId()
    {
        $attendanceService = app(AttendanceService::class);

        $data = ['worker_id' => 1000]; // Non-existent ID

        $result = $attendanceService->getWorkerClockIns($data);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetWorkerClockInsMissingData()
    {
        $attendanceService = app(AttendanceService::class);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Undefined array key "worker_id"');
        $attendanceService->getWorkerClockIns([]); // Empty array
    }
}
