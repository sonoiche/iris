<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\ContactRequest;
use App\Http\Resources\Employer\ContactResource;
use App\Models\Client\Employer\Contact;

class ContactController extends Controller
{
    public function datatable(Request $request)
    {
        $columns = [
            0   => '',
            1   => 'name',
            2   => 'position',
            3   => 'email',
            4   => 'mobile_number'
        ];

        $search  = $request['search'];
        $principal_id = $request['principal_id'];

        $result  = Contact::where('principal_id', $principal_id)
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%'.$search.'%');
            });

        $totalData = $result->count();

        $totalFiltered = $totalData; 

        $limit  = $request['length'];
        $start  = $request['start'];

        $order  = $columns[$request['order.0.column']];
        $dir    = $request['order.0.dir'];

        $contacts = $result->offset($start)
            ->limit($limit)
            ->when($order, function ($query, $order) use ($dir) {
                if($order != '') {
                    $query->orderBy($order, $dir);
                }
            })
            ->get();

        $data = [];
        if(!empty($contacts)) {
            foreach ($contacts as $key =>  $contact) {
                $nestedData['counter']               = $key+1;
                $nestedData['name']                  = $contact->name;
                $nestedData['position']              = $contact->position;
                $nestedData['email']                 = $contact->email;
                $nestedData['mobile_number']         = $contact->mobile_number;
                $nestedData['action']                = $contact->id;
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

    public function store(ContactRequest $request)
    {
        $contact                    = new Contact;
        $contact->name              = $request['name'];
        $contact->email             = $request['email'];
        $contact->position          = $request['position'];
        $contact->mobile_number     = $request['mobile_number'];
        $contact->skype_username    = $request['skype_username'];
        $contact->remarks           = $request['remarks'];
        $contact->principal_id      = $request['principal_id'];
        $contact->main_contact      = $request['main_contact'] == true ? 1 : 0;

        if($request['main_contact'] == true) {
            Contact::where('principal_id', $request['principal_id'])
                ->update(['main_contact' => 0]);
        }

        $contact->save();

        $data['message']            = 'Principal Contact has been saved.';
        return response()->json($data);
    }

    public function show($id)
    {
        return new ContactResource(Contact::find($id));
    }

    public function update(ContactRequest $request, $id)
    {
        $contact                    = Contact::find($id);
        $contact->name              = $request['name'];
        $contact->email             = $request['email'];
        $contact->position          = $request['position'];
        $contact->mobile_number     = $request['mobile_number'];
        $contact->skype_username    = $request['skype_username'];
        $contact->remarks           = $request['remarks'];
        $contact->main_contact      = $request['main_contact'] == true ? 1 : 0;

        if($request['main_contact'] == true) {
            Contact::where('principal_id', $request['principal_id'])
                ->update(['main_contact' => 0]);
        }

        $contact->save();

        $data['message']            = 'Principal Contact has been updated.';
        return response()->json($data);
    }

    public function destroy($id)
    {
        $contact = Contact::find($id);
        $contact->delete();

        $data['message']            = 'Principal Contact has been deleted.';
        return response()->json($data);
    }
}
