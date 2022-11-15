<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Client\Training;
use App\Http\Controllers\Controller;
use App\Http\Resources\Applicant\TrainingResource;
use App\Http\Requests\Client\Applicant\TrainingRequest;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        $applicant_id = $request['applicant_id'];
        $trainings  = Training::where('applicant_id', $applicant_id)->get();

        return TrainingResource::collection($trainings);
    }

    public function store(TrainingRequest $request)
    {
        $training = new Training;
        $training->title                = $request['title'];
        $training->provider             = $request['provider'];
        $training->certificate_number   = $request['certificate_number'];
        $training->place_issue          = $request['place_issue'];
        $training->date_issue           = isset($request['date_issue']) ? Carbon::parse($request['date_issue'])->format('Y-m-d') : '';
        $training->date_expiry          = isset($request['date_expiry']) ? Carbon::parse($request['date_expiry'])->format('Y-m-d') : '';
        $training->remarks              = $request['remarks'];
        $training->applicant_id         = $request['applicant_id'];
        $training->save();

        $data['message']                = 'Training has been saved.';
        return response()->json($data);
    }

    public function show($id)
    {
        return new TrainingResource(Training::find($id));
    }

    public function update(TrainingRequest $request, $id)
    {
        $training = Training::find($id);
        $training->title                = $request['title'];
        $training->provider             = $request['provider'];
        $training->certificate_number   = $request['certificate_number'];
        $training->place_issue          = $request['place_issue'];
        $training->date_issue           = isset($request['date_issue']) ? Carbon::parse($request['date_issue'])->format('Y-m-d') : '';
        $training->date_expiry          = isset($request['date_expiry']) ? Carbon::parse($request['date_expiry'])->format('Y-m-d') : '';
        $training->remarks              = $request['remarks'];
        $training->save();

        $data['message']        = 'Training & Seminar has been updated.';
        $data['data']           = $training;
        return response()->json($data);
    }

    public function destroy($id)
    {
        $training  = Training::find($id);
        $training->delete();
        $data['message'] = 'Training & Seminar has been deleted.';
        return response()->json($data);
    }
}
