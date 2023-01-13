<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Client\Configuration;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ConfigRequest;
use App\Http\Resources\Client\ConfigResource;

class ConfigController extends Controller
{
    public function store(ConfigRequest $request)
    {
        $type   = $request['type'];
        $config = Configuration::find(1);
        
        switch ($type) {
            case 'agency':

                $config->agency_name    = $request['agency_name'];
                $config->agency_website = $request['agency_website'];
                $config->address        = $request['address'];
                $config->contact_number = $request['contact_number'];
                
                if($request['logo'] != '' && $request->has('logo')) {
                    $file  = $request->file('logo');
                    $image = time().'.'.$file->getClientOriginalExtension();

                    Storage::disk('public')->putFileAs(
                        'uploads/agency',
                        $file,
                        $image
                    );

                    $config->logo = 'uploads/agency/'.$image;
                }
                $data['message'] = 'Agency detail has been updated';

                break;
            
            case 'notification':

                $config->medical_day                = $request['medical_day'];
                $config->accreditation_day          = $request['accreditation_day'];
                $config->manpower_request_day       = $request['manpower_request_day'];
                $config->processing_documents_day   = $request['processing_documents_day'];
                $data['message']                    = 'Notification settings has been updated';

                break;

            case 'email':
                
                $config->sender_name    = $request['sender_name'];
                $config->sender_email   = $request['sender_email'];
                $config->signature      = $request['signature'];
                $data['message']        = 'Email configuration has been updated';

                break;

            case 'manpower':
                
                $config->mr_notify_user = ($request['mr_notify_user'] == true) ? 1 : 0;
                $config->mr_subject     = $request['mr_subject'];
                $config->mr_template    = $request['mr_template'];
                $data['message']        = 'Manpower request settings has been updated';

                break;
        }

        $config->save();
        
        $data['config']  = $config;

        return response()->json($data);
    }

    public function show($id)
    {
        return new ConfigResource(Configuration::find(1));
    }
}
