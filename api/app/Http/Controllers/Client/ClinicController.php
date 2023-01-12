<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Client\Clinic;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ClinicRequest;
use App\Http\Resources\Client\ClinicResource;

class ClinicController extends Controller
{
    public function index(Request $request)
    {
        $clinics   = Clinic::orderBy('name')->get();

        return ClinicResource::collection($clinics);
    }

    public function show($id)
    {
        return new ClinicResource(Clinic::find($id));
    }

    public function store(ClinicRequest $request)
    {
        $clinic                 = new Clinic;
        $clinic->name           = $request['name'];
        $clinic->address        = $request['address'];
        $clinic->contact_number = $request['contact_number'];
        $clinic->contact_person = $request['contact_person'];
        $clinic->remarks        = $request['remarks'];
        $clinic->save();

        $data['message']        = 'Clinic has been saved.';
        return response()->json($data);
    }

    public function update(ClinicRequest $request, $id)
    {
        $clinic                 = Clinic::find($id);
        $clinic->name           = $request['name'];
        $clinic->address        = $request['address'];
        $clinic->contact_number = $request['contact_number'];
        $clinic->contact_person = $request['contact_person'];
        $clinic->remarks        = $request['remarks'];
        $clinic->save();

        $data['message']        = 'Clinic has been updated.';
        return response()->json($data);
    }

    public function destroy($id)
    {
        $clinic = Clinic::find($id);
        $clinic->delete();

        $data['message'] = 'Clinic has been deleted.';
        return response()->json($data);
    }

    public function datatable(Request $request)
    {
        $columns = [
            0   => '',
            1   => 'name',
            2   => 'address',
            3   => 'contact_number',
            4   => 'contact_person'
        ];

        $search     = $request['search'];

        $result     = Clinic::when($search, function ($query, $search) {
                $query->where('name', 'like', '%'.$search.'%');
            });

        $totalData = $result->count();

        $totalFiltered = $totalData;

        $limit  = $request['length'];
        $start  = $request['start'];

        $order  = $columns[$request['order.0.column']];
        $dir    = $request['order.0.dir'];

        $clinics = $result->offset($start)
            ->limit($limit)
            ->when($order, function ($query, $order) use ($dir) {
                if($order != '') {
                    $query->orderBy($order, $dir);
                }
            })
            ->get();

        $data = [];
        if(!empty($clinics)) {
            foreach ($clinics as $key =>  $clinic) {
                $nestedData['counter']          = $key+1;
                $nestedData['name']             = $clinic->name;
                $nestedData['address']          = $clinic->address;
                $nestedData['contact_person']   = $clinic->contact_person;
                $nestedData['contact_number']   = $clinic->contact_number;
                $nestedData['action']           = $clinic->id;
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
