<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client\ApplicantStatus;
use App\Http\Requests\Client\StatusRequest;
use App\Http\Resources\Client\StatusResource;
use App\Models\Client\Lineup;

class StatusController extends Controller
{
    public function index()
    {
        $data['data'] = ApplicantStatus::orderBy('id')->get();
        $data['pluckStatus'] = ApplicantStatus::orderBy('id')->pluck('name');
        $data['seriesStatus'] = $this->getChart();

        return response()->json($data);
        
    }

    public function show($id)
    {
        return new StatusResource(ApplicantStatus::find($id));
    }

    public function store(StatusRequest $request)
    {
        $status             = new ApplicantStatus;
        $status->name       = $request['name'];
        $status->save();

        $data['message']    = 'Applicant status has been saved.';
        return response()->json($data);
    }

    public function update(StatusRequest $request, $id)
    {
        $status             = ApplicantStatus::find($id);
        $status->name       = $request['name'];
        $status->save();

        $data['message']    = 'Applicant status has been updated.';
        return response()->json($data);
    }

    public function destroy($id)
    {
        $status = ApplicantStatus::find($id);
        $status->delete();

        $data['message']    = 'Applicant status has been deleted.';
        return response()->json($data);
    }

    public function datatable(Request $request)
    {
        $columns = [
            0   => '',
            1   => 'name'
        ];

        $search     = $request['search'];

        $result     = ApplicantStatus::when($search, function ($query, $search) {
                $query->where('name', 'like', '%'.$search.'%');
            });

        $totalData = $result->count();

        $totalFiltered = $totalData;

        $limit  = $request['length'];
        $start  = $request['start'];

        $order  = $columns[$request['order.0.column']];
        $dir    = $request['order.0.dir'];

        $statuses = $result->offset($start)
            ->limit($limit)
            ->when($order, function ($query, $order) use ($dir) {
                if($order != '') {
                    $query->orderBy($order, $dir);
                }
            })
            ->get();

        $data = [];
        if(!empty($statuses)) {
            foreach ($statuses as $key =>  $status) {
                $nestedData['counter']  = $key+1;
                $nestedData['name']     = $status->name;
                $nestedData['action']   = $status->id;
                $data[]                 = $nestedData;
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

    private function getChart()
    {
        $statuses = ApplicantStatus::orderBy('id')->get();
        $result   = [];
        foreach ($statuses as $item) {
            $result[] = Lineup::where('lineup_status_id', $item->id)->count();
        }

        return $result;
    }
}
