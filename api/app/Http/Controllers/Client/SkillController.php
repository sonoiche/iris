<?php

namespace App\Http\Controllers\Client;

use App\Models\Client\Skill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Applicant\SkillResource;
use App\Http\Requests\Client\Applicant\SkillRequest;

class SkillController extends Controller
{
    public function index(Request $request)
    {
        $applicant_id = $request['applicant_id'];
        $skills  = Skill::where('applicant_id', $applicant_id)->get();

        return SkillResource::collection($skills);
    }

    public function store(SkillRequest $request)
    {
        $skill = new Skill;
        $skill->name            = $request['name'];
        $skill->skill_level     = $request['skill_level'];
        $skill->remarks         = $request['remarks'];
        $skill->applicant_id    = $request['applicant_id'];
        $skill->save();

        $data['message']        = 'New skill has been saved.';
        return response()->json($data);
    }

    public function show($id)
    {
        return new SkillResource(Skill::find($id));
    }

    public function update(SkillRequest $request, $id)
    {
        $skill = Skill::find($id);
        $skill->name            = $request['name'];
        $skill->skill_level     = $request['skill_level'];
        $skill->remarks         = $request['remarks'];
        $skill->save();

        $data['message']        = 'Skill has been updated.';
        $data['data']           = $skill;
        return response()->json($data);
    }

    public function destroy($id)
    {
        $skill  = Skill::find($id);
        $skill->delete();
        $data['message'] = 'Skill has been deleted.';
        return response()->json($data);
    }

    public function levels()
    {
        $data['data'] = config('iris.skill_levels');
        return response()->json($data);
    }
}
