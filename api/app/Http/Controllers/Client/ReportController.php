<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Client\Applicant;
use App\Models\Client\ActivityLog;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Client\ApplicantSource;
use App\Models\Client\Employer\JobOrder;
use App\Models\Client\Employer\JobOrderPosition;

class ReportController extends Controller
{
    public function generateApplicantSource(Request $request)
    {
        $course = $request['course'];
        $date_applied = (isset($request['date_applied']) && $request['date_applied'] != null) ? Carbon::parse($request['date_applied'])->format('Y-m-d') : '';
        $gender = $request['gender'];
        $keyword = $request['keyword'];
        $location = $request['location'];
        $position_applied = $request['position_applied'];
        $school = $request['school'];
        $skill = $request['skill'];
        $skill_level = $request['skill_level'];
        $source_id = $request['source_id'];
        $yrs_min = $request['yrs_min'];
        $yrs_max = $request['yrs_max'];

        $education = $course ?? $school;
        $skills = $skill ?? $skill_level;
        $experience = $yrs_min ?? $yrs_max;

        $data['data'] = Applicant::when($education, function ($query, $education) use ($course, $school) {
            $query->leftJoin('educations','educations.applicant_id','=','applicants.applicant_number')
                ->when($course, function ($queryString, $course) {
                    $queryString->where('course', 'like', '%'.$course.'%');
                })
                ->when($school, function ($queryString, $school) {
                    $queryString->where('school', 'like', '%'.$school.'%');
                });
            })
            ->when($date_applied, function ($query, $date_applied) {
                $query->where('date_applied', $date_applied);
            })
            ->when($gender, function ($query, $gender) {
                if($gender != 'any') {
                    $query->where('gender', $gender);
                }
            })
            ->when($keyword, function ($query, $keyword) {
                $query->whereRaw('FIND_IN_SET(?, keywords)', [$keyword]);
            })
            ->when($location, function ($query, $location) {
                $query->where(function ($queryString) use ($location) {
                    $queryString->orWhere('address', 'like', '%'.$location.'%')
                        ->orWhere('city', 'like', '%'.$location.'%')
                        ->orWhere('province', 'like', '%'.$location.'%');
                });
            })
            ->when($position_applied, function ($query, $position_applied) {
                $query->where('position_applied', $position_applied);
            })
            ->when($skills, function ($query, $skills) use ($skill, $skill_level) {
                $query->leftJoin('skills','skills.applicant_id','=','applicants.applicant_number')
                    ->when($skill, function ($queryString, $skill) {
                        $queryString->where('name', 'like', '%'.$skill.'%');
                    })
                    ->when($skill_level, function ($queryString, $skill_level) {
                        $queryString->where('skill_level', $skill_level);
                    });
            })
            ->when($source_id, function ($query, $source_id) {
                $query->where('source$source_id', $source_id);
            })
            ->when($experience, function ($query, $experience) use ($yrs_min, $yrs_max) {
                $query->leftJoin('applicant_positions','applicant_positions.applicant_id','=','applicants.applicant_number')
                    ->when($yrs_min, function ($queryString, $yrs_min) {
                        $queryString->where('years_of_experience', '>', $yrs_min);
                    })
                    ->when($yrs_max, function ($queryString, $yrs_max) {
                        $queryString->where('years_of_experience', '<=', $yrs_max);
                    });
            })
            ->get();

        return response()->json($data);
    }

    public function generateDocuments(Request $request)
    {
        $columns = [
            0   => '',
            1   => 'date_submitted'
        ];

        $document_type  = $request['document_type_id'];
        $filter_by      = $request['filter_by'];
        $from           = Carbon::parse($request['from'])->format('Y-m-d');
        $to             = Carbon::parse($request['to'])->format('Y-m-d');
        
        $result     = Applicant::join('documents','documents.applicant_id','=','applicants.applicant_number')
            ->leftJoin('document_types','document_types.id','=','documents.document_type_id')
            ->select(
                'applicants.fname',
                'applicants.lname',
                'applicant_number',
                'documents.*',
                'document_types.name as document_type'
            )
            ->when($document_type, function ($query, $document_type) {
                $query->where('documents.document_type_id', $document_type);
            })
            ->when($filter_by, function ($query, $filter_by) use ($from, $to) {
                if($filter_by == 'submitted') {
                    $query->whereBetween('documents.date_submitted', [$from, $to]);
                } else {
                    $query->whereBetween('documents.date_expiry', [$from, $to]);
                }
            });

        $totalData      = $result->count();
        $totalFiltered  = $totalData;

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
            foreach ($applicants as $key =>  $applicant) {
                $nestedData['counter']          = $key+1;
                $nestedData['date_submitted']   = isset($applicant->date_submitted) ? Carbon::parse($applicant->date_submitted)->format('D M Y') : '';
                $nestedData['applicant_name']   = ['applicant_id' => $applicant->applicant_number, 'applicant_name' => $applicant->fname.' '.$applicant->lname];
                $nestedData['applicant_number'] = ['applicant_id' => $applicant->applicant_number, 'applicant_name' => $applicant->fname.' '.$applicant->lname];
                $nestedData['document_type']    = $applicant->document_type;
                $nestedData['attachment']       = '<a href="'.url('storage/'.$applicant->attachment).'">File</a>';
                $data[]                         = $nestedData;
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

    public function generateEncodedApplicant(Request $request)
    {
        $from   = Carbon::parse($request['from'])->format('Y-m-d');
        $to     = Carbon::parse($request['to'])->format('Y-m-d');
        $user_id = $request['user_id'];

        DB::enabledQueryLog();
        $applicants = Applicant::leftJoin('lineups','lineups.applicant_id','=','applicants.applicant_number')
            ->leftJoin('applicant_statuses','applicant_statuses.id','=','lineups.lineup_status_id')
            ->leftjoin('users','users.id','=','applicants.user_id')
            ->leftJoin('applicant_sources','applicant_sources.id','=','source_id')
            ->leftJoin('applicant_positions','applicant_positions.applicant_id','=','applicants.applicant_number')
            ->select('applicants.*','users.fname as first_name','users.lname as last_name','applicant_sources.name as source_name','applicant_positions.position_applied','applicant_statuses.name as status_name')
            ->when($user_id, function ($query, $user_id) {
                $query->where('applicants.user_id', $user_id);
            })
            ->whereBetween('applicants.created_at', [$from, $to])->get();
            return DB::getQueryLog();
        $data['data'] = [];
        if(!empty($applicants)) {
            foreach ($applicants as $applicant) {
                $nestedData['id']               = $applicant->id;
                $nestedData['fullname']         = $applicant->fullname;
                $nestedData['date_applied']     = isset($applicant->date_applied) ? Carbon::parse($applicant->date_applied)->format('d M Y') : '';
                $nestedData['status']           = $applicant->status_name;
                $nestedData['contact_number']   = $applicant->contact_number;
                $nestedData['position_applied'] = $applicant->position_applied;
                $nestedData['encoder']          = $applicant->first_name.' '.$applicant->last_name;
                $nestedData['source']           = $applicant->source_name;
                $nestedData['contact_number']   = $applicant->contact_number;
                $nestedData['updated_at']       = Carbon::parse($applicant->updated_at)->format('d M Y');
                $data['data'][]                 = $nestedData;
            }
        }

        $data['from'] = Carbon::parse($request['from'])->format('d F Y');
        $data['to']   = Carbon::parse($request['to'])->format('d F Y');

        return response()->json($data);
    }

    public function generateSourceApplicant(Request $request)
    {
        $from           = Carbon::parse($request['from'])->format('Y-m-d');
        $to             = Carbon::parse($request['to'])->format('Y-m-d');
        $source_id      = $request['source_id'];
        
        $data['from']   = Carbon::parse($request['from'])->format('d F Y');
        $data['to']     = Carbon::parse($request['to'])->format('d F Y');

        $array_results = [];
        if(isset($source_id)) {
            $data['source'] = ApplicantSource::find($source_id);
            $applicants     = Applicant::leftJoin('lineups','lineups.applicant_id','=','applicants.applicant_number')
                ->leftjoin('users','users.id','=','applicants.user_id')
                ->leftJoin('applicant_statuses','applicant_statuses.id','=','lineups.lineup_status_id')
                ->leftJoin('applicant_positions','applicant_positions.applicant_id','=','applicants.applicant_number')
                ->leftJoin('applicant_sources','applicant_sources.id','=','source_id')
                ->select('applicants.*','users.fname as first_name','users.lname as last_name','applicant_sources.name as source_name','applicant_positions.position_applied','applicant_statuses.name as status_name')
                ->where('source_id', $source_id)
                ->whereBetween('date_applied', [$from, $to])
                ->orderBy('applicants.fname')
                ->get();

            $data['data'] = [];
            if(!empty($applicants)) {
                foreach ($applicants as $applicant) {
                    $nestedData['id']               = $applicant->id;
                    $nestedData['fullname']         = $applicant->fname.' '.$applicant->lname;
                    $nestedData['mobile_number']    = $applicant->mobile_number;
                    $nestedData['email']            = $applicant->email;
                    $nestedData['date_applied']     = isset($applicant->date_applied) ? Carbon::parse($applicant->date_applied)->format('d M Y') : '';
                    $nestedData['status']           = $applicant->status_name;
                    $nestedData['position_applied'] = $applicant->position_applied;
                    $nestedData['encoder']          = $applicant->first_name.' '.$applicant->last_name;
                    $nestedData['source_name']      = $applicant->source_name;
                    $nestedData['updated_at']       = Carbon::parse($applicant->updated_at)->format('d M Y');
                    $data['data'][]                 = $nestedData;
                }
            }

            return response()->json($data);
        } else {
            $sources = ApplicantSource::orderBy('name')->get();
            foreach ($sources as $source) {
                $array_results[] = [
                    'source_name'       => $source->name,
                    'applicant_count'   => '<a href="'.url(config('app.url').'/client/reports/applicant/source-applicants').'/'.$source->id.'" target="_blank">'.Applicant::where('source_id', $source->id)->whereBetween('date_applied', [$from, $to])->count().'</a>'
                ];
            }

            $data['data'] = $array_results;
            return response()->json($data);
        }
    }

    public function generateAuditTrail(Request $request)
    {
        $from           = Carbon::parse($request['from'])->format('Y-m-d');
        $to             = Carbon::parse($request['to'])->format('Y-m-d');        
        $data['from']   = Carbon::parse($request['from'])->format('d F Y');
        $data['to']     = Carbon::parse($request['to'])->format('d F Y');
        $report_type    = $request['activity_type'];
        $user_id        = $request['user_id'];

        $data['data']   = ActivityLog::with('user')->when($report_type, function ($query, $report_type) {
                $query->where('activity_type', $report_type);
            })
            ->when($user_id, function($query, $user_id) {
                $query->where('user_id', $user_id);
            })
            ->latest()
            ->get();

        return response()->json($data);
    }

    public function generateBirthdateReport(Request $request)
    {
        $birthmonth = json_decode($request['birthmonth']);
        $month      = sprintf("%02d", $birthmonth->month + 1);
        $status_id  = $request['status_id'];
        
        $applicants = Applicant::leftJoin('lineups','lineups.applicant_id','=','applicants.applicant_number')
                ->leftJoin('applicant_statuses','applicant_statuses.id','=','lineups.lineup_status_id')
                ->select('applicants.*','applicant_statuses.name as status_name')
                ->whereRaw("month(birthdate) = ?", [$month])
                ->when($status_id, function ($query, $status_id) {
                    $query->where('lineups.lineup_status_id', $status_id);
                })
                ->orderBy('applicants.fname')
                ->get();

        $data['data'] = [];
        if(!empty($applicants)) {
            foreach ($applicants as $applicant) {
                $nestedData['id']               = $applicant->id;
                $nestedData['fullname']         = $applicant->fname.' '.$applicant->lname;
                $nestedData['mobile_number']    = $applicant->mobile_number;
                $nestedData['email']            = $applicant->email;
                $nestedData['birthdate']        = isset($applicant->birthdate) ? Carbon::parse($applicant->birthdate)->format('d F Y') : '';
                $nestedData['status']           = $applicant->status_name;
                $nestedData['age']              = isset($applicant->birthdate) ? Carbon::parse($applicant->birthdate)->age : '';
                $data['data'][]                 = $nestedData;
            }
        }

        $data['monthname'] = date("F", mktime(0, 0, 0, $month, 10));

        return response()->json($data);
    }

    public function generateDeployment(Request $request)
    {
        $from           = Carbon::parse($request['from'])->format('Y-m-d');
        $to             = Carbon::parse($request['to'])->format('Y-m-d');
        $principal_id   = $request['principal_id'];
        
        $data['from']   = Carbon::parse($request['from'])->format('d F Y');
        $data['to']     = Carbon::parse($request['to'])->format('d F Y');

        $applicants     = Applicant::leftJoin('lineups','lineups.applicant_id','=','applicants.applicant_number')
            ->leftJoin('job_orders','job_orders.id','=','lineups.job_order_id')
            ->leftJoin('job_order_positions','job_order_positions.id','=','lineups.position_id')
            ->leftJoin('processings','processings.applicant_id','=','applicants.applicant_number')
            ->leftJoin('applicant_positions','applicant_positions.applicant_id','=','applicants.applicant_number')
            ->leftJoin('countries','countries.id','=','processings.country_id')
            ->select('applicants.*','applicant_positions.position_applied','job_orders.job_order_number as order_number','job_order_positions.position_title','processings.worksite','countries.name as country_name')
            ->when($principal_id, function ($query, $principal_id) {
                $query->where('principals.id', $principal_id);
            })
            ->whereBetween('date_applied', [$from, $to])
            ->orderBy('applicants.fname')
            ->get();

        $data['data'] = [];
        if(!empty($applicants)) {
            foreach ($applicants as $applicant) {
                $nestedData['id']               = $applicant->id;
                $nestedData['fullname']         = $applicant->fname.' '.$applicant->lname;
                $nestedData['deployed_date']    = '';
                $nestedData['email']            = $applicant->email;
                $nestedData['job_order']        = $applicant->order_number.' '.$applicant->position_title;
                $nestedData['destination']      = $applicant->worksite;
                $nestedData['country']          = $applicant->country_name;
                $nestedData['salary']           = isset($applicant->salary) ? number_format($applicant->salary, 2) : '0.00';
                $data['data'][]                 = $nestedData;
            }
        }

        return response()->json($data);
    }

    public function generateInterview(Request $request)
    {
        $from           = Carbon::parse($request['from'])->format('Y-m-d');
        $to             = Carbon::parse($request['to'])->format('Y-m-d');
        $principal_id   = $request['principal_id'];
        $job_order_id   = $request['job_order_id'];
        
        $data['from']   = Carbon::parse($request['from'])->format('d F Y');
        $data['to']     = Carbon::parse($request['to'])->format('d F Y');

        $applicants     = Applicant::leftJoin('interviews','interviews.applicant_id','=','applicants.applicant_number')
            ->leftJoin('principals','principals.id','=','interviews.principal_id')
            ->leftJoin('job_orders','job_orders.id','=','interviews.job_order_id')
            ->leftJoin('job_order_positions','job_order_positions.id','=','interviews.position_id')
            ->select('applicants.*','principals.name as principal_name','job_orders.job_order_number as order_number','job_order_positions.position_title')
            ->when($principal_id, function ($query, $principal_id) {
                $query->where('principals.id', $principal_id);
            })
            ->when($job_order_id, function ($query, $job_order_id) {
                $query->where('job_orders.id', $job_order_id);
            })
            ->whereBetween('interview_date', [$from, $to])
            ->orderBy('applicants.fname')
            ->get();

        $data['data'] = [];
        if(!empty($applicants)) {
            foreach ($applicants as $applicant) {
                $nestedData['id']               = $applicant->id;
                $nestedData['fullname']         = $applicant->fname.' '.$applicant->lname;
                $nestedData['mobile_number']    = $applicant->mobile_number;
                $nestedData['email']            = $applicant->email;
                $nestedData['job_order']        = $applicant->order_number.' '.$applicant->position_title;
                $nestedData['principal_name']   = $applicant->principal_name;
                $nestedData['interview_date']   = isset($applicant->interview_date) ? Carbon::parse($applicant->interview_date)->format('d F Y') : '';
                $nestedData['venue']            = $applicant->venue;
                $data['data'][]                 = $nestedData;
            }
        }

        return response()->json($data);
    }

    public function generateManpower(Request $request)
    {
        $from           = Carbon::parse($request['from'])->format('Y-m-d');
        $to             = Carbon::parse($request['to'])->format('Y-m-d');
        $principal_id   = $request['principal_id'];
        $job_order_id   = $request['job_order_id'];
        
        $data['from']   = Carbon::parse($request['from'])->format('d F Y');
        $data['to']     = Carbon::parse($request['to'])->format('d F Y');

        $joborders      = JobOrder::leftJoin('principals','principals.id','=','job_orders.principal_id')
            ->leftJoin('users','users.id','=','job_orders.user_id')
            ->select('job_orders.*','principals.name as principal_name','users.fname','users.lname')
            ->when($principal_id, function ($query, $principal_id) {
                $query->where('job_orders.principal_id', $principal_id);
            })
            ->when($job_order_id, function ($query, $job_order_id) {
                $query->where('job_orders.id', $job_order_id);
            })
            ->whereBetween('job_orders.created_at', [$from, $to])
            ->get();

        $data['data'] = [];
        if(!empty($joborders)) {
            foreach ($joborders as $joborder) {
                $nestedData['id']               = $joborder->id;
                $nestedData['principal_name']   = $joborder->principal_name;
                $nestedData['job_order']        = $joborder->order_number;
                $nestedData['fullname']         = $joborder->fname.' '.$joborder->lname;
                $nestedData['status']           = $joborder->status;
                $nestedData['position_count']   = JobOrderPosition::where('job_order_id',$joborder->id)->count();
                $nestedData['created_at']       = isset($joborder->created_at) ? Carbon::parse($joborder->created_at)->format('d F Y') : '';
                $data['data'][]                 = $nestedData;
            }
        }

        return response()->json($data);
    }

}
