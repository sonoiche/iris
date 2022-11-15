<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client\Industry;
use Illuminate\Http\Request;

class IndustryController extends Controller
{
    public function selectOption()
    {
        $data['data'] = Industry::select(['id','name'])->orderBy('name')->get();
        return response()->json($data);
    }
}
