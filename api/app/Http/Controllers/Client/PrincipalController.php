<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Client\Employer\Principal;
use App\Http\Resources\Employer\PrincipalResource;
use App\Http\Requests\Client\Employer\PrincipalRequest;

class PrincipalController extends Controller
{
    public function datatable(Request $request)
    {
        $columns = [
            0   => '',
            1   => 'name',
            2   => 'address',
            3   => 'contact_number'
        ];

        $search  = $request['search'];

        $result  = Principal::when($search, function ($query, $search) {
                $query->where('name', 'like', '%'.$search.'%');
            });

        $totalData = $result->count();

        $totalFiltered = $totalData; 

        $limit  = $request['length'];
        $start  = $request['start'];

        $order  = $columns[$request['order.0.column']];
        $dir    = $request['order.0.dir'];

        $principals = $result->offset($start)
            ->limit($limit)
            ->when($order, function ($query, $order) use ($dir) {
                if($order != '') {
                    $query->orderBy($order, $dir);
                }
            })
            ->get();

        $data = [];
        if(!empty($principals)) {
            foreach ($principals as $key =>  $principal) {
                $nestedData['counter']               = $key+1;
                $nestedData['name']                  = $principal->name;
                $nestedData['contact_details']       = $principal->contact_details;
                $nestedData['accreditation_details'] = $principal->accreditation_details;
                $nestedData['contact_person']        = $principal->contact_person;
                $nestedData['activity']              = $principal->contact_number;
                $nestedData['action']                = $principal->id;
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

    public function store(PrincipalRequest $request)
    {
        $principal                          = new Principal;
        $principal->name                    = $request['name'];
        $principal->code                    = $request['code'];
        $principal->status                  = $request['status'];
        $principal->address                 = $request['address'];
        $principal->website                 = $request['website'];
        $principal->landline                = $request['landline'];
        $principal->mobile_number           = $request['mobile_number'];
        $principal->country_id              = $request['country_id'];
        $principal->accreditation_number    = $request['accreditation_number'];
        $principal->date_issue              = isset($request['date_issue']) ? Carbon::parse($request['date_issue'])->format('Y-m-d') : '';
        $principal->date_expiry             = isset($request['date_expiry']) ? Carbon::parse($request['date_expiry'])->format('Y-m-d') : '';
        $principal->industry_id             = $request['industry_id'];
        $principal->assigned_users          = $request['assigned_users'];
        $principal->remarks                 = $request['remarks'];

        if($request['logo'] != '' && $request->has('logo')) {
            $file  = $request->file('logo');
            $image = time().'.'.$file->getClientOriginalExtension();

            Storage::disk('public')->putFileAs(
                'uploads/principal',
                $file,
                $image
            );

            $principal->logo                = 'uploads/principal/'.$image;
        }

        $principal->save();

        $data['message'] = "Principal has been save successfully";
        return response()->json($data);
    }

    public function show($id)
    {
        return new PrincipalResource(Principal::with(['industry'])->find($id));
    }

    public function update(PrincipalRequest $request, $id)
    {
        $principal                          = Principal::find($id);
        $principal->name                    = $request['name'];
        $principal->status                  = $request['status'];
        $principal->address                 = $request['address'];
        $principal->website                 = $request['website'];
        $principal->landline                = $request['landline'];
        $principal->mobile_number           = $request['mobile_number'];
        $principal->country_id              = $request['country_id'];
        $principal->accreditation_number    = $request['accreditation_number'];
        $principal->date_issue              = isset($request['date_issue']) ? Carbon::parse($request['date_issue'])->format('Y-m-d') : '';
        $principal->date_expiry             = isset($request['date_expiry']) ? Carbon::parse($request['date_expiry'])->format('Y-m-d') : '';
        $principal->industry_id             = $request['industry_id'];
        $principal->assigned_users          = $request['assigned_users'];
        $principal->remarks                 = $request['remarks'];

        if($request['logo'] != '' && $request->has('logo')) {
            $file  = $request->file('logo');
            $image = time().'.'.$file->getClientOriginalExtension();

            Storage::disk('public')->putFileAs(
                'uploads/principal',
                $file,
                $image
            );

            $principal->logo                = 'uploads/principal/'.$image;
        }

        $principal->save();

        $data['message'] = "Principal has been save successfully";
        return response()->json($data);
    }

    public function selectOption()
    {
        $data['data'] = Principal::select(['id','name'])->orderBy('name')->get();
        return response()->json($data);
    }
}
