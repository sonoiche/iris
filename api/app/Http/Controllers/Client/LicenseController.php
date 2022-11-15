<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Client\License;
use App\Http\Controllers\Controller;
use App\Http\Resources\Applicant\LicenseResource;
use App\Http\Requests\Client\Applicant\LicenseRequest;

class LicenseController extends Controller
{
    public function index(Request $request)
    {
        $applicant_id = $request['applicant_id'];
        $licenses  = License::where('applicant_id', $applicant_id)->get();

        return LicenseResource::collection($licenses);
    }

    public function store(LicenseRequest $request)
    {
        $license = new License;
        $license->title          = $request['title'];
        $license->license_number = $request['license_number'];
        $license->date_issue     = $request['date_issue'];
        $license->date_taken     = $request['date_taken'];
        $license->date_expiry    = $request['date_expiry'];
        $license->applicant_id   = $request['applicant_id'];
        $license->save();

        $data['message']            = 'New license has been saved.';
        return response()->json($data);
    }

    public function show($id)
    {
        return new LicenseResource(License::find($id));
    }

    public function update(LicenseRequest $request, $id)
    {
        $license = License::find($id);
        $license->title          = $request['title'];
        $license->license_number = $request['license_number'];
        $license->date_issue     = $request['date_issue'];
        $license->date_taken     = $request['date_taken'];
        $license->date_expiry    = $request['date_expiry'];
        $license->save();

        $data['message']         = 'License has been updated.';
        return response()->json($data);
    }

    public function destroy($id)
    {
        $license  = License::find($id);
        $license->delete();
        $data['message'] = 'License has been deleted.';
        return response()->json($data);
    }
}
