<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Client\ResumeParser;
use App\Http\Controllers\Controller;

class ResumeController extends Controller
{
    public function store(Request $request)
    {
        $resume_id  = $request['resume_id'];
        $resume     = ResumeParser::find($resume_id);
        $resume->content = $request['content'];
        $resume->save();

        $data['content'] = $resume->content;

        return response()->json($data);
    }
}
