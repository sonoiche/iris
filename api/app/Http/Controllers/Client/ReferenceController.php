<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Client\Reference;
use App\Http\Controllers\Controller;
use App\Http\Resources\Applicant\ReferenceResource;
use App\Http\Requests\Client\Applicant\ReferenceRequest;

class ReferenceController extends Controller
{
    public function index(Request $request)
    {
        $applicant_id = $request['applicant_id'];
        $references   = Reference::where('applicant_id', $applicant_id)->get();

        return ReferenceResource::collection($references);
    }

    public function store(ReferenceRequest $request)
    {
        $reference = new Reference;
        $reference->name            = $request['name'];
        $reference->position        = $request['position'];
        $reference->company         = $request['company'];
        $reference->contact_number  = $request['contact_number'];
        $reference->email           = $request['email'];
        $reference->relationship    = $request['relationship'];
        $reference->applicant_id    = $request['applicant_id'];
        $reference->save();

        $data['message']            = 'Reference has been saved.';
        return response()->json($data);
    }

    public function show($id)
    {
        return new ReferenceResource(Reference::find($id));
    }

    public function update(ReferenceRequest $request, $id)
    {
        $reference = Reference::find($id);
        $reference->name            = $request['name'];
        $reference->position        = $request['position'];
        $reference->company         = $request['company'];
        $reference->contact_number  = $request['contact_number'];
        $reference->email           = $request['email'];
        $reference->relationship    = $request['relationship'];
        $reference->save();

        $data['message']        = 'Reference has been updated.';
        $data['data']           = $reference;
        return response()->json($data);
    }

    public function destroy($id)
    {
        $training  = Reference::find($id);
        $training->delete();
        $data['message'] = 'Reference has been deleted.';
        return response()->json($data);
    }
}
