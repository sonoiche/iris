<?php

namespace App\Models\Client;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "documents";
    protected $guarded = [];
    protected $appends = ['date_issue_display','date_expiry_display','date_submitted_display','document_type_name'];

    public function getDateIssueDisplayAttribute()
    {
        $date = isset($this->attributes['date_issue']) ? $this->attributes['date_issue'] : '';
        return validDate($date) ? Carbon::parse($date)->format('M d, Y'): '';
    }

    public function getDateExpiryDisplayAttribute()
    {
        $date = isset($this->attributes['date_expiry']) ? $this->attributes['date_expiry'] : '';
        return validDate($date) ? Carbon::parse($date)->format('M d, Y'): '';
    }

    public function getDateSubmittedDisplayAttribute()
    {
        $date = isset($this->attributes['date_submitted']) ? $this->attributes['date_submitted'] : '';
        return validDate($date) ? Carbon::parse($date)->format('M d, Y'): '';
    }

    public function getDocumentTypeNameAttribute()
    {
        $document = $this->document_type;
        return isset($document->name) ? $document->name : '';
    }

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }
}
