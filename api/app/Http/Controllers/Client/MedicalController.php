<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Client\Medical;
use App\Models\Client\Applicant;
use App\Models\Client\ActivityLog;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Applicant\MedicalRequest;
use App\Http\Resources\Client\Applicant\MedicalResource;

class MedicalController extends Controller
{
    public function index(Request $request)
    {
        $applicant_id = $request['applicant_id'];
        $medicals    = Medical::with('clinic')->where('applicant_id', $applicant_id)->get();

        return MedicalResource::collection($medicals);
    }

    public function store(MedicalRequest $request)
    {
        $clinic = new Medical;
        $clinic->clinic_id      = $request['clinic_id'];
        $clinic->status         = $request['status'];
        $clinic->remarks        = $request['remarks'];
        $clinic->date_referred  = isset($request['date_referred']) ? Carbon::parse($request['date_referred'])->addDay()->format('Y-m-d') : '';
        $clinic->date_taken     = isset($request['date_taken']) ? Carbon::parse($request['date_taken'])->addDay()->format('Y-m-d') : '';
        $clinic->date_result    = isset($request['date_result']) ? Carbon::parse($request['date_result'])->addDay()->format('Y-m-d') : '';
        $clinic->date_expiry    = isset($request['date_expiry']) ? Carbon::parse($request['date_expiry'])->addDay()->format('Y-m-d') : '';
        $clinic->applicant_id   = $request['applicant_id'];
        $clinic->user_id        = $request['user_id'];
        $clinic->save();

        // insert activity log
        $this->storeActivityLog('medical', $clinic, 'create');

        $data['message']        = 'Applicant medical has been saved.';
        return response()->json($data);
    }

    public function show($id)
    {
        return new MedicalResource(Medical::find($id));
    }

    public function update(MedicalRequest $request, $id)
    {
        $clinic = Medical::find($id);
        $clinic->clinic_id      = $request['clinic_id'];
        $clinic->status         = $request['status'];
        $clinic->remarks        = $request['remarks'];
        $clinic->date_referred  = isset($request['date_referred']) ? Carbon::parse($request['date_referred'])->addDay()->format('Y-m-d') : '';
        $clinic->date_taken     = isset($request['date_taken']) ? Carbon::parse($request['date_taken'])->addDay()->format('Y-m-d') : '';
        $clinic->date_result    = isset($request['date_result']) ? Carbon::parse($request['date_result'])->addDay()->format('Y-m-d') : '';
        $clinic->date_expiry    = isset($request['date_expiry']) ? Carbon::parse($request['date_expiry'])->addDay()->format('Y-m-d') : '';
        $clinic->save();

        // insert activity log
        $this->storeActivityLog('medical', $clinic, 'update');

        $data['message']        = 'Applicant medical has been updated.';
        $data['data']           = $clinic;
        return response()->json($data);
    }

    public function destroy($id)
    {
        $clinic  = Medical::find($id);
        $clinic->delete();
        $data['message'] = 'Applicant medical has been deleted.';
        return response()->json($data);
    }

    private function storeActivityLog($type, $applicant, $action)
    {
        $user = User::find($applicant->user_id);
        $applicant = Applicant::where('applicant_number', $applicant->applicant_id)->first();

        $activity = new ActivityLog;
        $activity->user_id          = $user->id;
        $activity->username         = $user->fname.' '.$user->lname;
        $activity->activity_type    = 'crud';
        $activity->applicant_id     = $applicant->applicant_number;
        $activity->applicant_name   = $applicant->fname.' '.$applicant->lname;
        $activity->module           = $type;
        $activity->user_action      = $action;
        $activity->save();
    }
}
