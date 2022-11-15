<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Client\Country;
use App\Http\Controllers\Controller;

class CountryController extends Controller
{
    public function selectOption()
    {
        $data['data'] = Country::select(['id','name'])->orderBy('name')->get();
        return response()->json($data);
    }
}
