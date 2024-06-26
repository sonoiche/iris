<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Client\Applicant;
use App\Models\Client\Employment;
use App\Models\Client\ActivityLog;
use App\Http\Controllers\Controller;
use App\Http\Resources\Applicant\EmploymentResource;
use App\Http\Requests\Client\Applicant\EmploymentRequest;

class EmploymentController extends Controller
{
    public function index(Request $request)
    {
        $applicant_id = $request['applicant_id'];
        $employments  = Employment::where('applicant_id', $applicant_id)->get();

        return EmploymentResource::collection($employments);
    }

    public function store(EmploymentRequest $request)
    {
        $from       = $request['from_date'];
        $to         = $request['to_date'];

        $employment = new Employment;
        $employment->position           = $request['position'];
        $employment->company_name       = $request['company_name'];
        $employment->company_address    = $request['company_address'];
        $employment->department         = $request['department'];
        $employment->country_id         = $request['country_id'];
        $employment->duration           = $from. '-' .$to;
        $employment->duties             = $request['duties'];
        $employment->applicant_id       = $request['applicant_id'];
        $employment->user_id            = $request['user_id'];
        $employment->save();

        // insert activity log
        $this->storeActivityLog('employment', $employment, 'create');

        $data['message']                = 'New employment has been saved.';
        return response()->json($data);
    }

    public function show($id)
    {
        return new EmploymentResource(Employment::find($id));
    }

    public function update(EmploymentRequest $request, $id)
    {
        $from       = $request['from_date'];
        $to         = $request['to_date'];

        $employment = Employment::find($id);
        $employment->position           = $request['position'];
        $employment->company_name       = $request['company_name'];
        $employment->company_address    = $request['company_address'];
        $employment->department         = $request['department'];
        $employment->country_id         = $request['country_id'];
        $employment->duration           = $from. '-' .$to;
        $employment->duties             = $request['duties'];
        $employment->save();

        // insert activity log
        $this->storeActivityLog('employment', $employment, 'update');

        $data['message']                = 'Employment has been updated.';
        return response()->json($data);
    }

    public function destroy($id)
    {
        $employment  = Employment::find($id);
        $employment->delete();
        $data['message'] = 'Employment has been deleted.';
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
