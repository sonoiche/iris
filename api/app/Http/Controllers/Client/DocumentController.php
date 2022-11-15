<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Client\Document;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Client\Applicant\DocumentRequest;
use App\Http\Resources\Client\Applicant\DocumentResource;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $applicant_id = $request['applicant_id'];
        $documents    = Document::with('document_type')->where('applicant_id', $applicant_id)->get();

        return DocumentResource::collection($documents);
    }

    public function store(DocumentRequest $request)
    {
        $document = new Document;
        $document->name             = $request['name'];
        $document->document_type_id = $request['document_type_id'];
        $document->document_number  = $request['document_number'];
        $document->place_issue      = $request['place_issue'];
        $document->remarks          = $request['remarks'];
        $document->date_issue       = isset($request['date_issue']) ? Carbon::parse($request['date_issue'])->format('Y-m-d') : '';
        $document->date_expiry      = isset($request['date_expiry']) ? Carbon::parse($request['date_expiry'])->format('Y-m-d') : '';
        $document->date_submitted   = isset($request['date_submitted']) ? Carbon::parse($request['date_submitted'])->format('Y-m-d') : '';
        $document->applicant_id     = $request['applicant_id'];

        if($request['attachment'] != '' && $request->has('attachment')) {
            $file  = $request->file('attachment');
            $image = time().'.'.$file->getClientOriginalExtension();

            Storage::disk('public')->putFileAs(
                'uploads/applicant/documents',
                $file,
                $image
            );

            $document->attachment   = 'uploads/applicant/documents/'.$image;
        }

        $document->save();

        $data['message']            = 'Applicant document has been saved.';
        return response()->json($data);
    }

    public function show($id)
    {
        return new DocumentResource(Document::find($id));
    }

    public function update(DocumentRequest $request, $id)
    {
        $document = Document::find($id);
        $document->name             = $request['name'];
        $document->document_type_id = $request['document_type_id'];
        $document->document_number  = $request['document_number'];
        $document->place_issue      = $request['place_issue'];
        $document->remarks          = $request['remarks'];
        $document->date_issue       = isset($request['date_issue']) ? Carbon::parse($request['date_issue'])->format('Y-m-d') : '';
        $document->date_expiry      = isset($request['date_expiry']) ? Carbon::parse($request['date_expiry'])->format('Y-m-d') : '';
        $document->date_submitted   = isset($request['date_submitted']) ? Carbon::parse($request['date_submitted'])->format('Y-m-d') : '';

        if($request['attachment'] != '' && $request->has('attachment')) {
            $this->removeAttachment($document->attachment);
            $file  = $request->file('attachment');
            $image = time().'.'.$file->getClientOriginalExtension();

            Storage::disk('public')->putFileAs(
                'uploads/applicant/documents',
                $file,
                $image
            );

            $document->attachment   = 'uploads/applicant/documents/'.$image;
        }

        $document->save();

        $data['message']        = 'Applicant document has been updated.';
        $data['data']           = $document;
        return response()->json($data);
    }

    public function destroy($id)
    {
        $training  = Document::find($id);
        $training->delete();
        $data['message'] = 'Applicant document has been deleted.';
        return response()->json($data);
    }

    private function removeAttachment($file)
    {
        Storage::delete($file);
        return response()->json();
    }
}
