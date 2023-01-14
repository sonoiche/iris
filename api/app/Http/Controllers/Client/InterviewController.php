<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Applicant\InterviewRequest;
use App\Models\Client\Employer\JobOrderPosition;
use App\Models\Client\Interview;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    public function index()
    {
        $interviews     = Interview::all();
        $arr_interviews = [];
        foreach ($interviews as $interview) {
            $arr_interviews[] = [
                'title' => $interview->position->position_title,
                'date'  => $interview->interview_date
            ];
        }

        $data['data'] = $arr_interviews;
        return response()->json($data);
    }

    public function store(InterviewRequest $request)
    {
        $position_id    = $request['position_id'];
        $applicant_ids  = explode(',', $request['applicant_ids']);
        $position       = JobOrderPosition::find($position_id);
        $interview_date = Carbon::parse($request['interview_date'])->format('Y-m-d').' '.$request['time'].':00';

        foreach ($applicant_ids as $applicant_id) {
            $interview = new Interview;
            $interview->principal_id    = $request['principal_id'];
            $interview->position_id     = $position_id;
            $interview->job_order_id    = $position->job_order_id;
            $interview->interview_date  = $interview_date;
            $interview->venue           = $request['venue'];
            $interview->remarks         = $request['remarks'];
            $interview->applicant_id    = $applicant_id;
            $interview->user_id         = $request['user_id'];
            $interview->save();
        }

        $data['message'] = 'Interview Schedule has been set.';
        return response()->json($data);
    }
}
