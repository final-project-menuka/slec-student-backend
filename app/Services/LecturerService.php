<?php
namespace App\Services; 

use App\User;
use Illuminate\Support\Str;
use App\Exceptions\ExceptionModels;
use App\OnGoingLec;
use App\Lecturer_attendance;
use App\Lecturer;

/**
 * All Students Services Manage By This Method
 */
class LecturerService
{
    private $user;
    private $on_going_lec;
    private $lecturer_attendence;
    private $lecturer_model;

    public function __construct(User $user,OnGoingLec $on_going_lec,Lecturer_attendance $lecturer_attendence,Lecturer $lecturer_model) {
        $this->user = $user;
        $this->on_going_lec = $on_going_lec;
        $this->lecturer_attendence = $lecturer_attendence;  
        $this->lecturer_model = $lecturer_model;                  
    }

    /**
     * New lecturer's Services Done By This Method
     */
    public function new_lecturer($request){
        if(!empty($request->input('id')) && !empty($request->input('imei'))){
            $new_lecturer = $this->lecturer_model::where('nsbm_id',$request->input('id'))->first();
            if(!empty($new_lecturer)){
                $email = $this->user::where('email',$request->input('email'))->first();
                if($email === null || empty($email)){
                    $this->user->id = Str::uuid();
                    $this->user->name = $new_lecturer['name'];
                    $this->user->email = $request->input('email');
                    $this->user->password = hash('sha256',$request->input('password'));
                    $this->user->imei_number = $request->input('imei');
                    $this->user->nsbm_id = $new_lecturer['nsbm_id'];
                    $this->user->batch = 0.0;
                    $this->user->course = $new_lecturer['course'];
                    $this->user->role = 1;
                    $this->user->save();
                    return response()->json($this->user,200);
                }else{
                    throw new \Exception(ExceptionModels::USER_EXISTS);
                }
            }else{
                throw new \Exception(ExceptionModels::YOU_ARE_NOT_A_VALIED_STUDENT);    
            }
        }else{
            throw new \Exception(ExceptionModels::ID_OR_IMEI_NOT_VALIED);
        }
    }

    /**
     * Lecturer Attendance Mark Done By This Method
     * 
     */
    public function mark_attendance($request)
    {
        if(!empty($request->input('id')) && !empty($request->input('macAddress'))){
            $on_going_lecture = $this->on_going_lec::where('start_time','<=',date('Y-m-d H:i:s'))
            ->where('end_time','>=',date('Y-m-d H:i:s'))->where('course',$request->input('course'))->where('batch',$request->input('batch'))
            ->where('mac_address',$request->input('macAddress'))->first();
            if(!empty($on_going_lecture)){
                $attendence = $this->student_attendence::where('module_code',$on_going_lecture['module_code'])
                ->where('student_id','=',$request->input('id'))->where('attended_time','>=',$on_going_lecture['start_time'])->where('attended_time','<=',$on_going_lecture['end_time'])->first();
                if(empty($attendence)){
                    $this->student_attendence->attended_time = date('Y-m-d H:i:s');
                    $this->student_attendence->student_id = $request->input('id');
                    $this->student_attendence->module_code = $on_going_lecture['module_code'];
                    $this->student_attendence->position = $on_going_lecture['position'];
                    $this->student_attendence->lec_hall_id = $on_going_lecture['lec_hall_number'];
                    $this->student_attendence->lecturer_name = $request->input('name');
                    $this->student_attendence->mac_address = $on_going_lecture['mac_address'];
                    $this->student_attendence->date = $on_going_lecture['date'];
                    $this->student_attendence->save();
                    return response()->json($this->student_attendence,201);
                }else{
                    throw new \Exception(ExceptionModels::PRESENTED_LECTURE);
                }
            }else{
                throw new \Exception(ExceptionModels::LEC_HALL_NOT_FOUND);
            }
        }else{
            throw new \Exception(ExceptionModels::MAC_ADDRESS_NOT_EXISTS);
        }
    }
    

    public function get_time_table()
    {
        //response()->json('ok',200);
        $today_lecs = $this->on_going_lec::where('date',date('Y-m-d'))->get();
        if(sizeof($today_lecs) > 0){
            return response()->json($today_lecs,200);
        }
        else{
            throw new \Exception(ExceptionModels::TODAY_NO_LECTURES);
        }
    }
}
