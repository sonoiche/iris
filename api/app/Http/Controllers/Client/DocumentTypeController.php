<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Client\DocumentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\DocumentTypeRequest;
use App\Http\Resources\Client\DocumentTypeResource;

class DocumentTypeController extends Controller
{
    public function index(Request $request)
    {
        $agency_id = $request['agency_id'];
        $documents    = DocumentType::where('agency_id', $agency_id)->get();

        return DocumentTypeResource::collection($documents);
    }

    public function show($id)
    {
        return new DocumentTypeResource(DocumentType::find($id));
    }

    public function store(DocumentTypeRequest $request)
    {
        $document             = new DocumentType;
        $document->name       = $request['name'];
        $document->agency_id  = $request['agency_id'];
        $document->save();

        $data['message']    = 'Document type has been saved.';
        return response()->json($data);
    }

    public function update(DocumentTypeRequest $request, $id)
    {
        $document             = DocumentType::find($id);
        $document->name       = $request['name'];
        $document->save();

        $data['message']    = 'Document type has been updated.';
        return response()->json($data);
    }

    public function destroy($id)
    {
        $document = DocumentType::find($id);
        $document->delete();

        $data['message']    = 'Document type has been deleted.';
        return response()->json($data);
    }

    public function datatable(Request $request)
    {
        $columns = [
            0   => '',
            1   => 'name'
        ];

        $search     = $request['search'];
        $agency_id  = $request['agency_id'];

        $result     = DocumentType::when($search, function ($query, $search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->where('agency_id', $agency_id);

        $totalData = $result->count();

        $totalFiltered = $totalData;

        $limit  = $request['length'];
        $start  = $request['start'];

        $order  = $columns[$request['order.0.column']];
        $dir    = $request['order.0.dir'];

        $documents = $result->offset($start)
            ->limit($limit)
            ->when($order, function ($query, $order) use ($dir) {
                if($order != '') {
                    $query->orderBy($order, $dir);
                }
            })
            ->get();

        $data = [];
        if(!empty($documents)) {
            foreach ($documents as $key =>  $document) {
                $nestedData['counter']  = $key+1;
                $nestedData['name']     = $document->name;
                $nestedData['action']   = $document->id;
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
