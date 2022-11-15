<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\SourceRequest;
use App\Http\Resources\Client\SourceResource;
use App\Models\Client\ApplicantSource;

class SourceController extends Controller
{
    public function index()
    {
        return SourceResource::collection(ApplicantSource::select(['id','name'])->orderBy('name')->get());
    }

    public function show($id)
    {
        return new SourceResource(ApplicantSource::find($id));
    }

    public function store(SourceRequest $request)
    {
        $source             = new ApplicantSource;
        $source->name       = $request['name'];
        $source->agency_id  = $request['agency_id'];
        $source->save();

        $data['message']    = 'Applicant source has been saved.';
        return response()->json($data);
    }

    public function update(SourceRequest $request, $id)
    {
        $source             = ApplicantSource::find($id);
        $source->name       = $request['name'];
        $source->save();

        $data['message']    = 'Applicant source has been updated.';
        return response()->json($data);
    }

    public function destroy($id)
    {
        $source = ApplicantSource::find($id);
        $source->delete();

        $data['message']    = 'Applicant source has been deleted.';
        return response()->json($data);
    }

    public function datatable(Request $request)
    {
        $columns = [
            0   => '',
            1   => 'name'
        ];

        $search     = $request['search'];
        $agency_id  = $request['agency_id'];

        $result     = ApplicantSource::when($search, function ($query, $search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->where('agency_id', $agency_id);

        $totalData = $result->count();

        $totalFiltered = $totalData; 

        $limit  = $request['length'];
        $start  = $request['start'];

        $order  = $columns[$request['order.0.column']];
        $dir    = $request['order.0.dir'];

        $sources = $result->offset($start)
            ->limit($limit)
            ->when($order, function ($query, $order) use ($dir) {
                if($order != '') {
                    $query->orderBy($order, $dir);
                }
            })
            ->get();

        $data = [];
        if(!empty($sources)) {
            foreach ($sources as $key =>  $source) {
                $nestedData['counter']  = $key+1;
                $nestedData['name']     = $source->name;
                $nestedData['action']   = $source->id;
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
}
