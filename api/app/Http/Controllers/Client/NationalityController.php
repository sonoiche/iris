<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Client\NationalityResource;
use App\Models\Client\Nationality;

class NationalityController extends Controller
{
    public function index()
    {
        return NationalityResource::collection(Nationality::select(['id','name'])->orderBy('name')->get());
    }
}
