<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Client\Lineup;
use App\Http\Controllers\Controller;
use App\Models\Client\ApplicantStatus;
use App\Models\Client\Employer\JobOrder;
use App\Models\Client\Employer\Principal;
use App\Http\Requests\Employer\JobOrderRequest;
use App\Http\Resources\Employer\JobOrderResource;

class JobOrderController extends Controller
{
    public function index(Request $request)
    {
        $principal_id = $request['principal_id'];
        $joborders    = JobOrder::join('job_order_positions','job_order_positions.job_order_id','=','job_orders.id')
            ->select('job_orders.*','job_order_positions.position_title')
            ->when($principal_id, function ($query, $principal_id) {
                $query->where('principal_id', $principal_id);
            })
            ->get();

        return JobOrderResource::collection($joborders);
    }

    public function datatable(Request $request)
    {
        $columns = [
            0   => '',
            1   => 'job_order_number',
            2   => 'name',
            3   => 'date_receive',
            4   => 'date_needed',
            5   => 'date_expiry',
        ];

        $search = $request['search'];
        $principal_ids = $request['principal_ids'];
        $user_ids = $request['user_ids'];
        $status = $request['status'];
        $user_id = $request['user_id'];

        $result  = JobOrder::select(['job_orders.*','principals.id as principal_id','principals.name'])
            ->withCount('positions')
            ->Join('principals','principals.id','=','job_orders.principal_id')
            ->when($search, function ($query, $search) {
                $query->orWhere('job_order_number', 'like', '%'.$search.'%')
                    ->orWhere('name', 'like', '%'.$search.'%');
            })
            ->when($principal_ids, function ($query, $principal_ids) {
                $query->whereIn('job_orders.principal_id', $principal_ids);
            })
            ->when($user_ids, function ($query, $user_ids) {
                $query->where( function ($queryString) use ($user_ids) {
                    foreach ($user_ids as $user_id) {
                        $queryString->orWhereRaw("FIND_IN_SET(?, job_orders.assigned_users)", [$user_id]);
                    }
                });
            })
            ->when($status, function ($query, $status) {
                if(!is_null($status['name'])) {
                    $query->where('job_orders.status', $status['name']);
                }
            })
            ->when($user_id, function ($query, $user_id) {
                $query->where('job_orders.user_id', $user_id);
            });

        $totalData = $result->count();

        $totalFiltered = $totalData;

        $limit  = $request['length'];
        $start  = $request['start'];

        $order  = $columns[$request['order.0.column']];
        $dir    = $request['order.0.dir'];

        $joborders = $result->offset($start)
            ->limit($limit)
            ->when($order, function ($query, $order) use ($dir) {
                if($order != '') {
                    $query->orderBy($order, $dir);
                }
            })
            ->get();

        $data = [];
        if(!empty($joborders)) {
            foreach ($joborders as $key =>  $joborder) {
                $nestedData['counter']               = $key+1;
                $nestedData['job_order_number']      = $joborder->job_order_number;
                $nestedData['principal']             = $joborder->name;
                $nestedData['status']                = $joborder->status;
                $nestedData['date_receive']          = $joborder->date_receive_display;
                $nestedData['date_needed']           = $joborder->date_needed_display;
                $nestedData['date_expiry']           = $joborder->date_expiry_display;
                $nestedData['position']              = $joborder->positions_count;
                $nestedData['action']                = $joborder->id;
                $data[]                              = $nestedData;
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

    public function store(JobOrderRequest $request)
    {
        $principal_id                = $request['principal_id'];
        $job_order                   = new JobOrder;
        $job_order->principal_id     = $principal_id;
        $job_order->job_order_number = $this->generateJobOrder($principal_id);
        $job_order->date_receive     = isset($request['date_receive']) ? Carbon::parse($request['date_receive'])->format('Y-m-d') : '';
        $job_order->date_needed      = isset($request['date_needed']) ? Carbon::parse($request['date_needed'])->format('Y-m-d') : '';
        $job_order->date_expiry      = isset($request['date_expiry']) ? Carbon::parse($request['date_expiry'])->format('Y-m-d') : '';
        $job_order->job_type         = $request['job_type'];
        $job_order->status           = $request['status'];
        $job_order->user_id          = $request['user_id'];
        $job_order->save();

        $data['message']             = 'New manpower request has been created.';
        return response()->json($data);
    }

    public function show($id)
    {
        return new JobOrderResource(JobOrder::find($id));
    }

    public function update(JobOrderRequest $request, $id)
    {
        $job_order                   = JobOrder::find($id);
        $job_order->date_receive     = isset($request['date_receive']) ? Carbon::parse($request['date_receive'])->format('Y-m-d') : '';
        $job_order->date_needed      = isset($request['date_needed']) ? Carbon::parse($request['date_needed'])->format('Y-m-d') : '';
        $job_order->date_expiry      = isset($request['date_expiry']) ? Carbon::parse($request['date_expiry'])->format('Y-m-d') : '';
        $job_order->job_type         = $request['job_type'];
        $job_order->status           = $request['status'];
        $job_order->save();

        $data['message']             = 'Manpower request has been updated.';
        return response()->json($data);
    }

    public function updateUsers(Request $request, $id)
    {
        $job_order                  = JobOrder::find($id);
        $job_order->assigned_users  = $request['assigned_users'];
        $job_order->save();

        $data['message']            = 'Assigned users has been updated.';
        return response()->json($data);
    }

    public function filter(Request $request)
    {
        $data['user_ids']       = $user_ids = $request['assigned_users'];
        $data['principal_ids']  = $principal_ids = $request['principal_id'];
        $data['status']         = ['id' => $request['status'], 'name' => $request['status']];
        $data['users']          = [];
        $data['principals']     = [];

        if($user_ids) {
            $users = User::whereIn('id', $user_ids)->orderBy('fname')->get();
            foreach ($users as $user) {
                $data['users'][] = [
                    'id' => $user->id,
                    'name' => $user->fullname
                ];
            }
        }

        if($principal_ids) {
            $principals = Principal::whereIn('id', $principal_ids)->orderBy('name')->get();
            foreach ($principals as $principal) {
                $data['principals'][] = [
                    'id' => $principal->id,
                    'name' => $principal->name
                ];
            }
        }

        return response()->json($data);
    }

    public function getLineup($id, Request $request)
    {
        $lineup_status_id = $request['status_id'];
        $data['data'] = Lineup::where('job_order_id', $id)
            ->where('lineup_status_id', $lineup_status_id)
            ->count();

        return response()->json($data);
    }

    public function getJoborderPostions()
    {
        $lineupstatus = ApplicantStatus::all();
        $joborders = JobOrder::select([
                'job_order_number',
                'job_orders.id',
                'job_orders.principal_id',
                'job_order_positions.id as position_id',
                'job_order_positions.position_title'
            ])
            ->join('job_order_positions','job_order_positions.job_order_id','=','job_orders.id')
            ->get();

        $arr_data = [];
        foreach ($joborders as $joborder) {
            $arr_status = [];

            foreach ($lineupstatus as $item) {
                $arr_status[] = [
                    'status_id' => $item->id,
                    'status'    => $item->name,
                    'count'     => Lineup::select('id')->where('position_id', $joborder->position_id)->where('lineup_status_id', $item->id)->count()
                ];
            }

            $arr_data[] = [
                'job_order_number'  => $joborder->job_order_number,
                'id'                => $joborder->id,
                'principal_id'      => $joborder->principal_id,
                'position_id'       => $joborder->position_id,
                'position_title'    => $joborder->position_title,
                'arr_status'        => $arr_status
            ];
        }
        $data['data'] = $arr_data;

        return response()->json($data);
    }

    private function generateJobOrder($principal_id)
    {
        $principal   = Principal::find($principal_id);
        $randomDigit = mt_rand(1000000, 9999999);

        return $principal->code.'-'.$randomDigit;
    }
}
