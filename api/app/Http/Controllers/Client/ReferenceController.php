<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Client\Applicant;
use App\Models\Client\Reference;
use App\Models\Client\ActivityLog;
use App\Http\Controllers\Controller;
use App\Http\Resources\Applicant\ReferenceResource;
use App\Http\Requests\Client\Applicant\ReferenceRequest;

class ReferenceController extends Controller
{
    public function index(Request $request)
    {
        $applicant_id = $request['applicant_id'];
        $references   = Reference::where('applicant_id', $applicant_id)->get();

        return ReferenceResource::collection($references);
    }

    public function store(ReferenceRequest $request)
    {
        $reference = new Reference;
        $reference->name            = $request['name'];
        $reference->position        = $request['position'];
        $reference->company         = $request['company'];
        $reference->contact_number  = $request['contact_number'];
        $reference->email           = $request['email'];
        $reference->relationship    = $request['relationship'];
        $reference->applicant_id    = $request['applicant_id'];
        $reference->user_id         = $request['user_id'];
        $reference->save();

        // insert activity log
        $this->storeActivityLog('reference', $reference, 'create');

        $data['message']            = 'Reference has been saved.';
        return response()->json($data);
    }

    public function show($id)
    {
        return new ReferenceResource(Reference::find($id));
    }

    public function update(ReferenceRequest $request, $id)
    {
        $reference = Reference::find($id);
        $reference->name            = $request['name'];
        $reference->position        = $request['position'];
        $reference->company         = $request['company'];
        $reference->contact_number  = $request['contact_number'];
        $reference->email           = $request['email'];
        $reference->relationship    = $request['relationship'];
        $reference->save();

        // insert activity log
        $this->storeActivityLog('reference', $reference, 'update');

        $data['message']        = 'Reference has been updated.';
        $data['data']           = $reference;
        return response()->json($data);
    }

    public function destroy($id)
    {
        $training  = Reference::find($id);
        $training->delete();
        $data['message'] = 'Reference has been deleted.';
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
