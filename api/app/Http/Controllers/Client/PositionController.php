<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\PositionRequest;
use App\Http\Resources\Employer\PositionResource;
use App\Models\Client\Employer\JobOrderPosition;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        return response()->json(200);
    }

    public function datatable(Request $request)
    {
        $columns = [
            0   => '',
            1   => 'position_title',
        ];

        $search  = $request['search'];
        $job_order_id  = $request['job_order_id'];
        
        $result  = JobOrderPosition::when($search, function ($query, $search) {
                $query->where('position_title', 'like', '%'.$search.'%');
            })
            ->where('job_order_id', $job_order_id);

        $totalData = $result->count();

        $totalFiltered = $totalData; 

        $limit  = $request['length'];
        $start  = $request['start'];

        $order  = $columns[$request['order.0.column']];
        $dir    = $request['order.0.dir'];
        
        $positions = $result->offset($start)
            ->limit($limit)
            ->when($order, function ($query, $order) use ($dir) {
                if($order != '') {
                    $query->orderBy($order, $dir);
                }
            })
            ->get();
            
        $data = [];
        if(!empty($positions)) {
            foreach ($positions as $key =>  $position) {
                $nestedData['counter']          = $key+1;
                $nestedData['position_title']   = $position->position_title;
                $nestedData['number_of_male']   = $position->number_of_male;
                $nestedData['number_of_female'] = $position->number_of_female;
                $nestedData['total_number']     = $position->total_number;
                $nestedData['propose_salary']   = isset($position->propose_salary) ? number_format($position->propose_salary, 2) : '0.00';
                $nestedData['action']           = $position->id;
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

    public function store(PositionRequest $request)
    {
        $position                           = new JobOrderPosition;
        $position->job_order_id             = $request['job_order_id'];
        $position->position_title           = $request['position_title'];
        $position->number_of_male           = $request['any_gender'] == 'true' ? 0 : $request['number_of_male'];
        $position->number_of_female         = $request['any_gender'] == 'true' ? 0 : $request['number_of_female'];
        $position->any_gender               = $request['any_gender'] == 'true' ? 1 : 0;
        $position->total_number             = $request['any_gender'] == 'true' ? $request['total_number'] : 0;
        $position->propose_salary           = $request['propose_salary'];
        $position->propose_food_allowance   = $request['propose_food_allowance'];
        $position->save();

        $data['message']             = 'New job order position has been created.';
        return response()->json($data);
    }

    public function show($id)
    {
        return new PositionResource(JobOrderPosition::find($id));
    }

    public function update(PositionRequest $request, $id)
    {
        $position                           = JobOrderPosition::find($id);
        $position->position_title           = $request['position_title'];
        $position->number_of_male           = $request['any_gender'] == 'true' ? 0 : $request['number_of_male'];
        $position->number_of_female         = $request['any_gender'] == 'true' ? 0 : $request['number_of_female'];
        $position->any_gender               = $request['any_gender'] == 'true' ? 1 : 0;
        $position->total_number             = $request['any_gender'] == 'true' ? $request['total_number'] : 0;
        $position->propose_salary           = $request['propose_salary'];
        $position->propose_food_allowance   = $request['propose_food_allowance'];
        $position->save();

        $data['message']             = 'Job order position has been updated.';
        return response()->json($data);
    }

    public function updateJobDescription(Request $request, $id)
    {
        $job_order                   = JobOrderPosition::find($id);
        $job_order->job_description  = $request['job_description'];
        $job_order->save();

        $data['message']             = 'Job description has been updated.';
        return response()->json($data);
    }

    public function destroy($id)
    {
        $position = JobOrderPosition::find($id);
        $position->delete();

        $data['message'] = 'Job order position has been deleted.';
        return response()->json($data);
    }
}
