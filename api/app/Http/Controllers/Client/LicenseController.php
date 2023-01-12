<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Client\License;
use App\Models\Client\Applicant;
use App\Models\Client\ActivityLog;
use App\Http\Controllers\Controller;
use App\Http\Resources\Applicant\LicenseResource;
use App\Http\Requests\Client\Applicant\LicenseRequest;

class LicenseController extends Controller
{
    public function index(Request $request)
    {
        $applicant_id = $request['applicant_id'];
        $licenses  = License::where('applicant_id', $applicant_id)->get();

        return LicenseResource::collection($licenses);
    }

    public function store(LicenseRequest $request)
    {
        $license = new License;
        $license->title          = $request['title'];
        $license->license_number = $request['license_number'];
        $license->date_issue     = $request['date_issue'];
        $license->date_taken     = $request['date_taken'];
        $license->date_expiry    = $request['date_expiry'];
        $license->applicant_id   = $request['applicant_id'];
        $license->user_id        = $request['user_id'];
        $license->save();

        // insert activity log
        $this->storeActivityLog('licenses', $license, 'create');

        $data['message']            = 'New license has been saved.';
        return response()->json($data);
    }

    public function show($id)
    {
        return new LicenseResource(License::find($id));
    }

    public function update(LicenseRequest $request, $id)
    {
        $license = License::find($id);
        $license->title          = $request['title'];
        $license->license_number = $request['license_number'];
        $license->date_issue     = $request['date_issue'];
        $license->date_taken     = $request['date_taken'];
        $license->date_expiry    = $request['date_expiry'];
        $license->save();

        // insert activity log
        $this->storeActivityLog('licenses', $license, 'update');

        $data['message']         = 'License has been updated.';
        return response()->json($data);
    }

    public function destroy($id)
    {
        $license  = License::find($id);
        $license->delete();
        $data['message'] = 'License has been deleted.';
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
