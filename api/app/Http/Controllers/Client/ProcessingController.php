<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Client\Processing;
use App\Http\Controllers\Controller;
use App\Http\Resources\Client\Applicant\ProcessingResource;

class ProcessingController extends Controller
{
    public function show($applicant_id)
    {
        return new ProcessingResource(Processing::where('applicant_id', $applicant_id)->first());
    }

    public function store(Request $request)
    {
        $applicant_id = $request['applicant_id'];
        Processing::updateOrCreate(
            ['applicant_id'         => $applicant_id],
            [
                'employer'          => $request['employer'],
                'principal_id'      => $request['principal_id'],
                'salary'            => $request['salary'],
                'direct_hire'       => $request['direct_hire'],
                'worksite'          => $request['worksite'],
                'country_id'        => $request['country_id'],
                'job_order_number'  => $request['job_order_number'],
                'date_endorse'      => isset($request['date_endorse']) ? Carbon::parse($request['date_endorse'])->format('Y-m-d') : ''
            ]
        );

        $data['message'] = 'Applicant processing has been updated.';
        $data['processing'] = new ProcessingResource(Processing::where('applicant_id', $applicant_id)->first());
        return response()->json($data);
    }
}
