<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client\EmailTemplate;
use App\Http\Requests\Client\EmailTemplateRequest;
use App\Http\Resources\Client\EmailTemplateResource;

class EmailTemplateController extends Controller
{
    public function show($id)
    {
        return new EmailTemplateResource(EmailTemplate::find($id));
    }

    public function store(EmailTemplateRequest $request)
    {
        $template             = new EmailTemplate;
        $template->title      = $request['title'];
        $template->content    = $request['content'];
        $template->save();

        $data['message']    = 'Email template has been saved.';
        return response()->json($data);
    }

    public function update(EmailTemplateRequest $request, $id)
    {
        $template             = EmailTemplate::find($id);
        $template->title      = $request['title'];
        $template->content    = $request['content'];
        $template->save();

        $data['message']    = 'Email template has been updated.';
        return response()->json($data);
    }

    public function destroy($id)
    {
        $template = EmailTemplate::find($id);
        $template->delete();

        $data['message']    = 'Email template has been deleted.';
        return response()->json($data);
    }

    public function datatable(Request $request)
    {
        $columns = [
            0   => '',
            1   => 'title'
        ];

        $search     = $request['search'];

        $result     = EmailTemplate::when($search, function ($query, $search) {
                $query->where('title', 'like', '%'.$search.'%');
            });

        $totalData = $result->count();

        $totalFiltered = $totalData; 

        $limit  = $request['length'];
        $start  = $request['start'];

        $order  = $columns[$request['order.0.column']];
        $dir    = $request['order.0.dir'];
                 
        $templates = $result->offset($start)
            ->limit($limit)
            ->when($order, function ($query, $order) use ($dir) {
                if($order != '') {
                    $query->orderBy($order, $dir);
                }
            })
            ->get();

        $data = [];
        if(!empty($templates)) {
            foreach ($templates as $key =>  $template) {
                $nestedData['counter']  = $key+1;
                $nestedData['title']    = $template->title;
                $nestedData['content']  = $template->content;
                $nestedData['action']   = $template->id;
                $data[]                 = $nestedData;
            }
        }

        $json_data = [
            "draw"            => intval($request['draw']),
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        ];

        return response()->json($json_data);
    }
}
