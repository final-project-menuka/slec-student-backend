<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LecturerService;


/**
 * Lecturer's Requests Catch By This Controller
 */
class LecturerController extends Controller
{

    private $lecturer_service;

    public function __construct(LecturerService $lecturer_service){
        $this->lecturer_service = $lecturer_service;
    }

    /**
     * New Lecturer Signup By This Method
     * Post Request
     */
    public function new_lecturer(Request $request)
    {
        //return response()->json(['ok'=>'ok'],200);
        return $this->lecturer_service->new_lecturer($request);
    }

    /**
     * Attendance Mark By This Method
     * Post Request
     */
    public function mark_attendence(Request $request){
        return $this->lecturer_service->mark_attendance($request);
    }

    public function update_attendance_status(Request $request)
    {
        return $this->lecturer_service->update_attendance_status($request);
    }
    public function get_time_table(Request $request)
    {
        return $this->lecturer_service->get_time_table();
    }
}

