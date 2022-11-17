<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Client\Lineup;
use App\Models\Client\Applicant;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
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
        $applicant->date_applied            = isset($request['date_applied']) ? Carbon::parse($request['date_applied'])->format('Y-m-d') : '';
        $applicant->applicant_number        = $this->generateApplicantNumber();
        $applicant->fname                   = $request['fname'];
        $applicant->mname                   = $request['mname'];
        $applicant->lname                   = $request['lname'];
        $applicant->email                   = $request['email'];
        $applicant->landline                = $request['landline'];
        $applicant->mobile_number           = $request['mobile_number'];
        $applicant->alt_mobile_number       = $request['alt_mobile_number'];
        $applicant->birthdate               = isset($request['birthdate']) ? Carbon::parse($request['birthdate'])->format('Y-m-d') : '';
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

        $data['message']            = 'New applicant has been saved.';
        $data['applicant']          = $applicant;
        return response()->json($data);
    }

    public function show($applicant_number)
    {
        return new ApplicantResource(Applicant::where('applicant_number', $applicant_number)->first());
    }

    public function update(ApplicantUpdateRequest $request, $id)
    {
        $applicant = Applicant::find($id);
        $applicant->date_applied            = isset($request['date_applied']) ? Carbon::parse($request['date_applied'])->format('Y-m-d') : '';
        $applicant->fname                   = $request['fname'];
        $applicant->mname                   = $request['mname'];
        $applicant->lname                   = $request['lname'];
        $applicant->email                   = $request['email'];
        $applicant->landline                = $request['landline'];
        $applicant->mobile_number           = $request['mobile_number'];
        $applicant->alt_mobile_number       = $request['alt_mobile_number'];
        $applicant->birthdate               = isset($request['birthdate']) ? Carbon::parse($request['birthdate'])->format('Y-m-d') : '';
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
        $applicant->passport_date_issued    = isset($request['passport_date_issued']) ? Carbon::parse($request['passport_date_issued'])->format('Y-m-d') : '';
        $applicant->passport_date_submitted = isset($request['passport_date_submitted']) ? Carbon::parse($request['passport_date_submitted'])->format('Y-m-d') : '';
        $applicant->passport_date_expiry    = isset($request['passport_date_expiry']) ? Carbon::parse($request['passport_date_expiry'])->format('Y-m-d') : '';
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

        $data['message']            = 'Applicant information has been saved.';
        $data['applicant']          = $applicant;
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
        $applicant->mobile_number       = $request['mobile_number'];
        $applicant->birthdate           = isset($request['birthdate']) ? Carbon::parse($request['birthdate'])->format('Y-m-d') : '';
        $applicant->keywords            = $request['keywords'];

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
        $data['message']    = 'New applicant has been saved.';
        $data['applicant']  = $applicant;
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
}
