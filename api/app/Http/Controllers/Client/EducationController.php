<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Client\Applicant;
use App\Models\Client\Education;
use App\Models\Client\FieldStudy;
use App\Models\Client\ActivityLog;
use App\Http\Controllers\Controller;
use App\Http\Resources\Applicant\StudyResource;
use App\Http\Resources\Applicant\EducationResource;
use App\Http\Requests\Client\Applicant\EducationRequest;

class EducationController extends Controller
{
    public function index(Request $request)
    {
        $applicant_id = $request['applicant_id'];
        $educations   = Education::with(['education_field'])
            ->where('applicant_id', $applicant_id)
            ->get();

        return EducationResource::collection($educations);
    }

    public function store(EducationRequest $request)
    {
        $from       = $request['from_date'];
        $to         = $request['to_date'];

        $education  = new Education;
        $education->education_level = $request['education_level'];
        $education->field_study     = $request['field_study'];
        $education->course          = $request['course'];
        $education->school          = $request['school'];
        $education->location        = $request['location'];
        $education->duration        = $from. '-' .$to;
        $education->remarks         = $request['remarks'];
        $education->applicant_id    = $request['applicant_id'];
        $education->user_id         = $request['user_id'];
        $education->save();

        // insert activity log
        $this->storeActivityLog('education', $education, 'create');

        $data['message']            = 'New education has been saved.';
        return response()->json($data);
    }

    public function show($id)
    {
        return new EducationResource(Education::find($id));
    }

    public function update(EducationRequest $request, $id)
    {
        $from       = $request['from_date'];
        $to         = $request['to_date'];

        $education  = Education::find($id);
        $education->education_level = $request['education_level'];
        $education->field_study     = $request['field_study'];
        $education->course          = $request['course'];
        $education->school          = $request['school'];
        $education->location        = $request['location'];
        $education->duration        = $from. '-' .$to;
        $education->remarks         = $request['remarks'];
        $education->save();

        // insert activity log
        $this->storeActivityLog('education', $education, 'update');

        $data['message']            = 'Education has been updated.';
        return response()->json($data);
    }

    public function destroy($id)
    {
        $education  = Education::find($id);
        $education->delete();
        $data['message'] = 'Education has been deleted.';
        return response()->json($data);
    }

    public function levels()
    {
        $data['data'] = config('iris.educational_levels');
        return response()->json($data);
    }

    public function studies()
    {
        return StudyResource::collection(FieldStudy::select(['id','name'])->get());
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
