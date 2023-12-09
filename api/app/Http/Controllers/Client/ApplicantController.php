<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Client\Skill;
use Illuminate\Http\Request;
use App\Models\Client\Lineup;
use App\Models\Client\Applicant;
use App\Models\Client\Education;
use App\Models\Client\Reference;
use App\Models\Client\Employment;
use App\Models\Client\ActivityLog;
use Illuminate\Support\Facades\DB;
use App\Models\Client\ResumeParser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Client\ApplicantPosition;
use App\Http\Resources\Applicant\ApplicantResource;
use App\Http\Requests\Client\Applicant\ApplicantRequest;
use App\Http\Requests\Client\Applicant\ApplicantUpdateRequest;
use App\Http\Requests\Client\Applicant\EncodeApplicantRequest;

class ApplicantController extends Controller
{
    public function index()
    {
        return ApplicantResource::collection(Applicant::orderBy('fname')->get());
    }

    public function store(ApplicantRequest $request)
    {
        $applicant = new Applicant;
        $applicant->date_applied            = isset($request['date_applied']) ? Carbon::parse($request['date_applied'])->addDay()->format('Y-m-d') : '';
        $applicant->applicant_number        = $this->generateApplicantNumber();
        $applicant->fname                   = $request['fname'];
        $applicant->mname                   = $request['mname'];
        $applicant->lname                   = $request['lname'];
        $applicant->email                   = $request['email'];
        $applicant->landline                = $request['landline'];
        $applicant->mobile_number           = $request['mobile_number'];
        $applicant->alt_mobile_number       = $request['alt_mobile_number'];
        $applicant->birthdate               = isset($request['birthdate']) ? Carbon::parse($request['birthdate'])->addDay()->format('Y-m-d') : '';
        $applicant->gender                  = $request['gender'];
        $applicant->birthplace              = $request['birthplace'];
        $applicant->address                 = $request['address'];
        $applicant->city                    = $request['city'];
        $applicant->province                = $request['province'];
        $applicant->postal_code             = $request['postal_code'];
        $applicant->language_spoken         = $request['language_spoken'];
        $applicant->keywords                = $request['keywords'];
        $applicant->expected_salary         = $request['expected_salary'];
        $applicant->availability            = $request['availability'];
        $applicant->user_id                 = $request['user_id'];

        if($request['resume'] != '' && $request->has('resume')) {
            $file   = $request->file('resume');
            $resume = time().'.'.$file->getClientOriginalExtension();

            Storage::disk('public')->putFileAs(
                'uploads/applicant/resume',
                $file,
                $resume
            );

            $applicant->resume = 'uploads/applicant/resume/'.$resume;
        }

        $applicant->save();

        // insert activity log
        $this->storeActivityLog('personal', $applicant, 'create');

        $data['message']            = 'New applicant has been saved.';
        $data['applicant']          = $applicant;
        return response()->json($data);
    }

    public function show($applicant_number)
    {
        return new ApplicantResource(Applicant::where('applicant_number', $applicant_number)->withTrashed()->first());
    }

    public function update(ApplicantUpdateRequest $request, $id)
    {
        $applicant = Applicant::find($id);
        $applicant->date_applied            = isset($request['date_applied']) ? Carbon::parse($request['date_applied'])->addDay()->format('Y-m-d') : '';
        $applicant->fname                   = $request['fname'];
        $applicant->mname                   = $request['mname'];
        $applicant->lname                   = $request['lname'];
        $applicant->email                   = $request['email'];
        $applicant->landline                = $request['landline'];
        $applicant->mobile_number           = $request['mobile_number'];
        $applicant->alt_mobile_number       = $request['alt_mobile_number'];
        $applicant->birthdate               = isset($request['birthdate']) ? Carbon::parse($request['birthdate'])->addDay()->format('Y-m-d') : '';
        $applicant->gender                  = $request['gender'];
        $applicant->birthplace              = $request['birthplace'];
        $applicant->address                 = $request['address'];
        $applicant->city                    = $request['city'];
        $applicant->province                = $request['province'];
        $applicant->postal_code             = $request['postal_code'];
        $applicant->language_spoken         = $request['language_spoken'];
        $applicant->keywords                = $request['keywords'];
        $applicant->resume                  = $request['resume'];
        $applicant->expected_salary         = $request['expected_salary'];
        $applicant->availability            = $request['availability'];

        $applicant->source_id               = $request['source_id'];
        $applicant->suffix                  = $request['suffix'];
        $applicant->height                  = $request['height'];
        $applicant->weight                  = $request['weight'];
        $applicant->marital_status          = $request['marital_status'];
        $applicant->nationality_id          = $request['nationality_id'];
        $applicant->religion                = $request['religion'];
        $applicant->passport_number         = $request['passport_number'];
        $applicant->passport_place_issued   = $request['passport_place_issued'];
        $applicant->passport_date_issued    = isset($request['passport_date_issued']) ? Carbon::parse($request['passport_date_issued'])->addDay()->format('Y-m-d') : '';
        $applicant->passport_date_submitted = isset($request['passport_date_submitted']) ? Carbon::parse($request['passport_date_submitted'])->addDay()->format('Y-m-d') : '';
        $applicant->passport_date_expiry    = isset($request['passport_date_expiry']) ? Carbon::parse($request['passport_date_expiry'])->addDay()->format('Y-m-d') : '';
        $applicant->sss_number              = $request['sss_number'];
        $applicant->tin_number              = $request['tin_number'];
        $applicant->philhealth              = $request['philhealth'];
        $applicant->pagibig                 = $request['pagibig'];
        $applicant->remarks                 = $request['remarks'];

        if($request['resume'] != '' && $request->has('resume')) {
            $file   = $request->file('resume');
            $resume = time().'.'.$file->getClientOriginalExtension();

            Storage::disk('public')->putFileAs(
                'uploads/applicant/resume',
                $file,
                $resume
            );

            $applicant->resume = 'uploads/applicant/resume/'.$resume;
        }

        if($request['passport_file'] != '' && $request->has('passport_file')) {
            $file   = $request->file('passport_file');
            $passport_file = time().'.'.$file->getClientOriginalExtension();

            Storage::disk('public')->putFileAs(
                'uploads/applicant/passport',
                $file,
                $passport_file
            );

            $applicant->passport_file = 'uploads/applicant/passport/'.$passport_file;
        }

        $applicant->save();

        // insert activity log
        $this->storeActivityLog('personal', $applicant, 'update');

        // add position applied
        ApplicantPosition::updateOrCreate(
            ['applicant_id' => $applicant->applicant_number],
            [
                'position_applied'      => $request['position_applied'],
                'years_of_experience'   => $request['yrs_of_exp'],
                'user_id'               => $request['user_id']
            ]
        );

        $data['message']    = 'Applicant information has been saved.';
        $data['applicant']  = $applicant;
        return response()->json($data);
    }

    public function options()
    {
        $deployed_applicant_ids = Lineup::distinct()->pluck('applicant_id');
        $data['data'] = Applicant::select(DB::raw("applicant_number as id, concat(fname,' ',lname) as name"))
            ->whereNotIn('applicant_number', $deployed_applicant_ids)
            ->get();

        return response()->json($data);
    }

    public function encode(EncodeApplicantRequest $request)
    {
        $applicant = new Applicant;
        $applicant->position_applied    = $request['position_applied'];
        $applicant->fname               = $request['fname'];
        $applicant->mname               = $request['mname'];
        $applicant->lname               = $request['lname'];
        $applicant->applicant_number    = $this->generateApplicantNumber();
        $applicant->mobile_number       = $request['mobile_number'];
        $applicant->birthdate           = isset($request['birthdate']) ? Carbon::parse($request['birthdate'])->addDay()->format('Y-m-d') : '';
        $applicant->keywords            = $request['keywords'];
        $applicant->user_id             = $request['user_id'];

        if($request['resume'] != '' && $request->has('resume')) {
            $file   = $request->file('resume');
            $resume = time().'.'.$file->getClientOriginalExtension();

            Storage::disk('public')->putFileAs(
                'uploads/applicant/resume',
                $file,
                $resume
            );

            $applicant->resume = 'uploads/applicant/resume/'.$resume;
        }

        $applicant->save();

        // insert activity log
        $this->storeActivityLog('personal', $applicant, 'create');

        // add applicant positions data
        $position = new ApplicantPosition;
        $position->applicant_id     = $applicant->id;
        $position->position_applied = $request['position_applied'];
        $position->user_id          = $request['user_id'];
        $position->save();

        $data['message']    = 'New applicant has been saved.';
        $data['applicant']  = $applicant;
        return response()->json($data);
    }

    public function datatable(Request $request)
    {
        $columns = [
            0   => '',
            1   => 'lname',
            2   => 'mobile_number',
            3   => 'position_applied',
            4   => 'date_applied',
            5   => 'status',
            6   => 'latest_remarks'
        ];

        $search     = $request['search'];

        $result     = Applicant::with('lineup.lineup_status')
            ->when($search, function ($query, $search) {
            $query->where(function ($queryString) use ($search) {
                $queryString->orWhere('fname', 'like', '%'.$search.'%')
                    ->orWhere('mname', 'like', '%'.$search.'%')
                    ->orWhere('lname', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('mobile_number', 'like', '%'.$search.'%')
                    ->orWhere('applicant_number', 'like', '%'.$search.'%');
            });
        });

        $totalData = $result->count();

        $totalFiltered = $totalData;

        $limit  = $request['length'];
        $start  = $request['start'];

        $order  = $columns[$request['order.0.column']];
        $dir    = $request['order.0.dir'];

        $applicants = $result->offset($start)
            ->limit($limit)
            ->when($order, function ($query, $order) use ($dir) {
                if($order != '') {
                    $query->orderBy($order, $dir);
                }
            })
            ->get();

        $data = [];
        if(!empty($applicants)) {
            $i = 1;
            foreach ($applicants as $key =>  $applicant) {
                $applicant->setAttribute('number', $i);
                $nestedData['counter']              = $i;
                $nestedData['applicant_name']       = ['applicant_name' => $applicant->applicant_name, 'applicant_id' => $applicant->applicant_number];
                $nestedData['mobile_number']        = $applicant->mobile_number;
                $nestedData['position_applied']     = isset($applicant->position_applied) ? $applicant->position_applied : '--';
                $nestedData['date_applied_display'] = (isset($applicant->date_applied) && $applicant->position_applied) ? Carbon::parse($applicant->date_applied)->format('d M, Y') : '';
                $nestedData['status']               = isset($applicant->lineup) ? $applicant->lineup->lineup_status->name : '';
                $nestedData['latest_remarks']       = $applicant->remarks;
                $nestedData['action']               = ['id' => $applicant->id, 'applicant_id' => $applicant->applicant_number];
                $i++;
                $data[]                             = $nestedData;
            }
        }

        $json_data = [
            "draw"            => intval($request['draw']),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        ];

        return response()->json($json_data);
    }

    public function resumeParser(Request $request)
    {
        $resume     = new ResumeParser;
        $resume->user_id = $request['user_id'];

        if($request['resume'] != '' && $request->has('resume')) {
            $file   = $request->file('resume');
            $resumefile = time().'.'.$file->getClientOriginalExtension();

            Storage::disk('public')->putFileAs(
                'uploads/resume-parser',
                $file,
                $resumefile
            );

            $resume->resumefile = 'storage/uploads/resume-parser/'.$resumefile;
        }

        $resume->save();
        $data['resumelink'] = config('app.api_url').'/'.$resume->resumefile;
        $data['resume_id']  = $resume->id;

        return response()->json($data);
    }

    public function getResumeData(Request $request)
    {
        $user_id = $request['user_id'];
        $resume = ResumeParser::where('user_id', $user_id)->first();
        $data['data'] = json_decode($resume->content, true);
        $data['resume_id']  = $resume->id;

        return response()->json($data);
    }

    public function deleteResume(Request $request)
    {
        $user_id = $request['user_id'];
        $resume = ResumeParser::where('user_id', $user_id)->first();
        $resume->delete();

        return response()->json(200);
    }

    public function getTrashed(Request $request)
    {
        $columns = [
            0   => '',
            1   => 'lname',
            2   => 'mobile_number',
            3   => 'position_applied',
            4   => 'date_applied',
            5   => 'status',
            6   => 'latest_remarks'
        ];

        $search     = $request['search'];

        $result     = Applicant::onlyTrashed()
            ->with('lineup.lineup_status')
            ->when($search, function ($query, $search) {
            $query->where(function ($queryString) use ($search) {
                $queryString->orWhere('fname', 'like', '%'.$search.'%')
                    ->orWhere('mname', 'like', '%'.$search.'%')
                    ->orWhere('lname', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('mobile_number', 'like', '%'.$search.'%')
                    ->orWhere('applicant_number', 'like', '%'.$search.'%');
            });
        });

        $totalData = $result->count();

        $totalFiltered = $totalData;

        $limit  = $request['length'];
        $start  = $request['start'];

        $order  = $columns[$request['order.0.column']];
        $dir    = $request['order.0.dir'];

        $applicants = $result->offset($start)
            ->limit($limit)
            ->when($order, function ($query, $order) use ($dir) {
                if($order != '') {
                    $query->orderBy($order, $dir);
                }
            })
            ->get();

        $data = [];
        if(!empty($applicants)) {
            $i = 1;
            foreach ($applicants as $key =>  $applicant) {
                $applicant->setAttribute('number', $i);
                $nestedData['counter']              = $i;
                $nestedData['applicant_name']       = ['applicant_name' => $applicant->applicant_name, 'applicant_id' => $applicant->applicant_number];
                $nestedData['mobile_number']        = $applicant->mobile_number;
                $nestedData['position_applied']     = isset($applicant->position_applied) ? $applicant->position_applied : '--';
                $nestedData['date_applied_display'] = (isset($applicant->date_applied) && $applicant->position_applied) ? Carbon::parse($applicant->date_applied)->format('d M, Y') : '';
                $nestedData['status']               = isset($applicant->lineup) ? $applicant->lineup->lineup_status->name : '';
                $nestedData['latest_remarks']       = $applicant->remarks;
                $nestedData['action']               = ['id' => $applicant->id, 'applicant_id' => $applicant->applicant_number];
                $i++;
                $data[]                             = $nestedData;
            }
        }

        $json_data = [
            "draw"            => intval($request['draw']),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        ];

        return response()->json($json_data);
    }

    public function destroy($id)
    {
        $applicant = Applicant::find($id);
        $applicant->delete();

        $data['message'] = 'Applicant has been deleted.';
        return response()->json($data);
    }

    public function permanentDelete($id)
    {
        $applicant = Applicant::withTrashed()->find($id);
        $applicant->forcedelete();

        $data['message'] = 'Applicant has been permanently deleted.';
        return response()->json($data);
    }

    public function returnApplicant($id)
    {
        $applicant = Applicant::withTrashed()->find($id);
        $applicant->deleted_at = null;
        $applicant->save();

        $data['message'] = 'Applicant data has been retrieved.';
        return response()->json($data);
    }

    public function storeResume(Request $request)
    {
        $applicant = new Applicant;
        $applicant->date_applied        = Carbon::now()->addDay()->format('Y-m-d');
        $applicant->applicant_number    = $this->generateApplicantNumber();
        $applicant->fname               = $request['fname'];
        $applicant->mname               = $request['mname'];
        $applicant->lname               = $request['lname'];
        $applicant->email               = $request['email'];
        $applicant->birthdate           = isset($request['birthdate']) ? Carbon::parse($request['birthdate'])->addDay()->format('Y-m-d') : '';
        $applicant->mobile_number       = $request['mobile_number'];
        $applicant->user_id             = $request['user_id'];
        $applicant->save();

        // saves educations
        $educations = json_decode($request['educations'], true);
        $education_levels = json_decode($request['education_levels'], true);
        foreach ($educations as $key => $item) {
            $education = new Education;
            $education->course          = $item['course'];
            $education->school          = $item['school'];
            $education->education_level = $education_levels[$key];
            $education->applicant_id    = $applicant->applicant_number;
            $education->user_id         = $request['user_id'];
            $education->save();
        }

        // saves experiences
        $employments = json_decode($request['employments'], true);
        foreach ($employments as $item) {
            $employment = new Employment;
            $employment->position        = $item['position'];
            $employment->company_name    = $item['company_name'];
            $employment->company_address = $item['company_address'];
            $employment->applicant_id    = $applicant->applicant_number;
            $employment->user_id         = $request['user_id'];
            $employment->save();
        }

        // saves skills
        $skills = json_decode($request['skills'], true);
        $skill_levels   = json_decode($request['skill_levels'], true);
        $skill_remarks  = json_decode($request['skill_remarks'], true);
        foreach ($skills as $key => $item) {
            $skill = new Skill;
            $skill->name            = $item['name'];
            $skill->skill_level     = $skill_levels[$key];
            $skill->applicant_id    = $applicant->applicant_number;
            $skill->user_id         = $request['user_id'];
            $skill->save();
        }

        // saves references
        $references = json_decode($request['references'], true);
        foreach ($references as $key => $item) {
            $reference = new Reference;
            $reference->name            = $item['name'];
            $reference->position        = $item['position'];
            $reference->company         = $item['company'];
            $reference->contact_number  = $item['contact_number'];
            $reference->email           = $item['email'];
            $reference->relationship    = $item['relationship'];
            $reference->applicant_id    = $applicant->applicant_number;
            $reference->user_id         = $request['user_id'];
            $reference->save();
        }

        // remove resume content once inserted
        if($applicant->id) {
            $resume_id  = $request['resume_id'];
            $resume     = ResumeParser::find($resume_id);
            $resume->delete();
        }

        // insert activity log
        $this->storeActivityLog('personal', $applicant, 'create');

        $data['message'] = 'Applicant information from resume has been saved.';
        $data['applicant_number'] = $applicant->applicant_number;
        return response()->json($data);
    }

    public function uploadPhoto(Request $request)
    {
        $upload_type    = $request['upload_type'];
        $applicant_id   = $request['applicant_id'];
        
        $applicant = Applicant::where('applicant_number', $applicant_id)->first();
        if($upload_type == 'camera') {
            $base64image        = $request['canvas'];
            $date               = time().'.png';
            $image              = $this->base64_to_jpeg($base64image, storage_path().'/app/public/uploads/applicant/'.$date);
            $applicant->photo   = 'storage/uploads/applicant/'.$image;
            $applicant->save();
        }

        if($upload_type == 'photo') {
            if($request['image'] != '' && $request->has('image')) {
                $file   = $request->file('image');
                $image  = time().'.'.$file->getClientOriginalExtension();
    
                Storage::disk('public')->putFileAs(
                    'uploads/applicant',
                    $file,
                    $image
                );
    
                $applicant->photo = 'storage/uploads/applicant/'.$image;
                $applicant->save();
            }
        }

        $data['applicant']  = $applicant;
        $data['message']    = 'Applicant profile photo has been updated.';
        return response()->json($data);
    }

    private function generateApplicantNumber()
    {
        $year = date('Y');
        $applicant = Applicant::max('applicant_number');
        $current = isset($applicant) ? substr($applicant, 0, 4) : $year;

        if($current !== $year) {
            return $year.'000001';
        } elseif(!isset($applicant)) {
            return $year.'000001';
        } else {
            return $applicant+1;
        }
    }

    private function storeActivityLog($type, $applicant, $action)
    {
        $user = User::find($applicant->user_id);
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

    private function base64_to_jpeg($base64_string, $output_file) {

        $parts        = explode(";base64,", $base64_string);
        $imagebase64  = base64_decode($parts[1]);
        // open the output file for writing
        $ifp = fopen( $output_file, 'w' ); 
    
        // we could add validation here with ensuring count( $data ) > 1
        fwrite( $ifp, $imagebase64 );
    
        // clean up the file resource
        fclose( $ifp ); 
    
        return basename($output_file);
    }
}
