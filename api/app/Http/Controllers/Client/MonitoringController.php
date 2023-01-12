<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Client\Applicant;
use App\Http\Controllers\Controller;

class MonitoringController extends Controller
{
    public function applicants(Request $request)
    {
        $columns = [
            0   => '',
            1   => 'lname',
            2   => 'principal_name',
            9   => 'deployed_by',
            10  => 'deployed_date'
        ];

        $principal_id = $request['principal_id'];
        $result     = Applicant::join('processings','processings.applicant_id','=','applicants.applicant_number')
            ->leftJoin('lineups','lineups.applicant_id','=','applicants.applicant_number')
            ->leftJoin('principals','principals.id','=','processings.principal_id')
            ->leftJoin('users','users.id','=','lineups.user_id')
            ->leftJoin('countries','countries.id','=','processings.country_id')
            ->select(
                'applicants.fname',
                'applicants.lname',
                'applicant_number',
                'processings.*',
                'lineups.user_id',
                'lineups.created_at as deployed_date',
                'principals.name as principal_name',
                'users.fname as userfname',
                'users.lname as userlname',
                'countries.name as country_name'
            )
            ->when($principal_id, function ($query, $principal_id) {
                $query->where('processings.principal_id', $principal_id);
            });
        $totalData  = $result->count();

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
            foreach ($applicants as $key =>  $applicant) {
                $nestedData['counter']          = $key+1;
                $nestedData['principal_name']   = $applicant->principal_name;
                $nestedData['applicant_name']   = ['applicant_id' => $applicant->applicant_number, 'applicant_name' => $applicant->fname.' '.$applicant->lname];
                $nestedData['actual_employer']  = $applicant->employer;
                $nestedData['agreed_salary']    = isset($applicant->salary) ? number_format($applicant->salary, 2) : '0.00';
                $nestedData['direct_hire']      = ucfirst($applicant->direct_hire);
                $nestedData['worksite']         = $applicant->worksite;
                $nestedData['country']          = $applicant->country_name;
                $nestedData['job_order_no']     = $applicant->job_order_number;
                $nestedData['endorsement_date'] = isset($applicant->date_endorse) ? Carbon::parse($applicant->date_endorse)->format('d M Y') : '';
                $nestedData['deployed_by']      = $applicant->userfname.' '.$applicant->userlname;
                $nestedData['deployed_date']    = isset($applicant->deployed_date) ? Carbon::parse($applicant->deployed_date)->format('d M Y') : '';
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

    public function documents(Request $request)
    {
        $columns = [
            0   => '',
            1   => 'lname',
            2   => 'principal_name',
            9   => 'deployed_by',
            10  => 'deployed_date'
        ];

        $principal_id   = $request['principal_id'];
        $status_id      = $request['status_id'];
        $job_order_id   = $request['job_order_id'];
        $position_id    = $request['position_id'];

        $result     = Applicant::join('documents','documents.applicant_id','=','applicants.applicant_number')
            ->leftJoin('processings','processings.applicant_id','=','applicants.applicant_number')
            ->leftJoin('lineups','lineups.applicant_id','=','applicants.applicant_number')
            ->leftJoin('principals','principals.id','=','processings.principal_id')
            ->leftJoin('users','users.id','=','lineups.user_id')
            ->leftJoin('job_order_positions','job_order_positions.id','=','lineups.position_id')
            ->leftJoin('applicant_statuses','applicant_statuses.id','=','lineups.lineup_status_id')
            ->leftJoin('document_types','document_types.id','=','documents.document_type_id')
            ->select(
                'applicants.fname',
                'applicants.lname',
                'applicant_number',
                'documents.*',
                'lineups.user_id',
                'lineups.created_at as deployed_date',
                'principals.name as principal_name',
                'users.fname as userfname',
                'users.lname as userlname',
                'processings.job_order_number',
                'job_order_positions.position_title',
                'applicant_statuses.name as status_name',
                'document_types.name as document_type_name'
            )
            ->when($principal_id, function ($query, $principal_id) {
                $query->where('processings.principal_id', $principal_id);
            })
            ->when($status_id, function ($query, $status_id) {
                $query->where('lineups.lineup_status_id', $status_id);
            })
            ->when($job_order_id, function ($query, $job_order_id) {
                $query->where('lineups.job_order_id', $job_order_id);
            })
            ->when($position_id, function ($query, $position_id) {
                $query->where('lineups.position_id', $position_id);
            });

        $totalData  = $result->count();
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
            foreach ($applicants as $key =>  $applicant) {
                $nestedData['counter']          = $key+1;
                $nestedData['principal_name']   = $applicant->principal_name;
                $nestedData['applicant_name']   = ['applicant_id' => $applicant->applicant_number, 'applicant_name' => $applicant->fname.' '.$applicant->lname];
                $nestedData['job_order_no']     = $applicant->job_order_number;
                $nestedData['position_title']   = $applicant->position_title;
                $nestedData['status']           = $applicant->status_name;
                $nestedData['document_name']    = $applicant->name;
                $nestedData['document_type']    = $applicant->document_type_name;
                $nestedData['date_issued']      = isset($applicant->date_issue) ? Carbon::parse($applicant->date_issue)->format('d M Y') : '';
                $nestedData['expiry_date']      = isset($applicant->date_expiry) ? Carbon::parse($applicant->date_expiry)->format('d M Y') : '';
                $nestedData['submitted_date']   = isset($applicant->date_submitted) ? Carbon::parse($applicant->date_submitted)->format('d M Y') : '';
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
}
