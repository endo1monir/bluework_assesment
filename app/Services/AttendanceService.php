<?php

namespace App\Services;

use App\Http\Requests\Attendance\ClockInQueryRequest;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceService
{

    const LATITUDE = 30.04951367581298;
    const LONGITUDE = 31.240338786508516;
    const DISTANCE_KM = 2.0;

    /**
     * get the data make the checks for clock in and  store it
     * @param array $data the clock in details
     * @return string[] the result of the checks
     */
    public function ClockIn(array $data): array
    {
        // Convert Unix timestamp to MySQL datetime format
        $dateTime = Carbon::createFromTimestamp($data['timestamp'])->toDateTimeString();
        $distance = $this->checkDistance($data['latitude'], $data['longitude']);
        if ($distance > self::DISTANCE_KM) {
            return ['status' => 'error'
                , 'message' => 'rejected to check in'];
        }
        $data['type'] = 'clock-in';
        $data['timestamp'] = $dateTime;
        $attendance = Attendance::create($data);
        return ['status' => 'success'
            , 'message' => 'checked in',
            'data' => $attendance];


    }

    /** Checks the worker clockins by the given worker id
     * @param $data
     * @return array
     */
    public function getWorkerClockIns($data):array
    {
        $WorkerClockIns = Attendance::where('worker_id', $data['worker_id'])->pluck('timestamp')->toArray();
        return $WorkerClockIns;
    }

    /**
     * Check the distance in 2 kmeters of the given latitude and long
     * @param $latitude
     * @param $longitude
     * @return float
     */
    public function checkDistance($latitude, $longitude): float
    {
        $theta = $longitude - self::LONGITUDE;
        $dist = sin(deg2rad($latitude)) * sin(deg2rad(self::LATITUDE)) + cos(deg2rad($latitude)) * cos(deg2rad(self::LATITUDE)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        //convert from miles to kilometers
        $km = $dist * 60 * 1.853159616;

        return $km;
    }
}
