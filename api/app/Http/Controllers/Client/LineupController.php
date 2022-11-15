<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Client\Lineup;
use App\Http\Controllers\Controller;
use App\Http\Resources\Client\Applicant\LineupResource;
use App\Models\Client\Employer\JobOrder;
use App\Models\Client\Employer\JobOrderPosition;

class LineupController extends Controller
{
    public function index(Request $request)
    {
        $applicant_id = $request['applicant_id'];
        $lineups      = Lineup::with(['employer','lineup_status','user'])
            ->where('applicant_id', $applicant_id)->get();

        return LineupResource::collection($lineups);
    }

    public function store(Request $request)
    {
        $status_id      = $request['status_id'];
        $applicant_ids  = $request['applicants'];
        $position_id    = $request['position_id'];
        $user_id        = $request['user_id'];

        $position       = JobOrderPosition::find($position_id);
        $job_order      = JobOrder::find($position->job_order_id);

        if(isset($applicant_ids)) {
            foreach (explode(',', $applicant_ids) as $applicant_id) {
                $lineup = new Lineup;
                $lineup->lineup_status_id   = $status_id;
                $lineup->job_order_id       = $position->job_order_id;
                $lineup->position_id        = $position_id;
                $lineup->principal_id       = $job_order->principal_id;
                $lineup->applicant_id       = $applicant_id;
                $lineup->user_id            = $user_id;
                $lineup->save();
            }

            $data['message'] = 'Applicant lineup has been updated.';
            return response()->json($data);
        }
    }

    public function show($status_id, Request $request)
    {
        $position_id  = $request['position_id'];
        $lineups      = Lineup::with(['user','job_order','position','applicant.educations'])
            ->where('lineup_status_id', $status_id)
            ->where('position_id', $position_id)
            ->get();

        return LineupResource::collection($lineups);
    }

    public function lieupUpdate(Request $request)
    {
        $applicant_ids  = $request['applicants'];
        $position_id    = $request['position_id'];
        $status_id      = $request['status_id'];

        foreach (explode(',', $applicant_ids) as $applicant_id) {
            Lineup::updateOrCreate(
                ['applicant_id' => $applicant_id, 'position_id' => $position_id],
                ['lineup_status_id' => $status_id, 'remarks' => $request['remarks']]
            );
        }

        $data['message'] = 'Applicant lineup has been updated.';
        return response()->json($data);
    }

    public function destroy($id)
    {
        $lineup = Lineup::find($id);
        $lineup->delete();

        $data['message'] = 'Applicant has been deleted.';
        return response()->json($data);
    }
}
