<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StudentService;
use App\Services\LecturerService;

class Time_tableController extends Controller
{

    private $lecturer_service;

    public function __construct(LecturerService $lecturer_service){
        $this->lecturer_service = $lecturer_service;
    }
    /* student time table part */
 public function get_time_table(Request $request)
    {
        return $this->student_service->get_time_table();
        return $this->lecturer_service->get_time_table();
    }

}
