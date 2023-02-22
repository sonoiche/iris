<?php

namespace App\Exports;

use App\Models\Client\Applicant;
use Illuminate\Contracts\View\View;
use App\Models\Client\Configuration;
use App\Models\Client\ApplicantSource;
use Maatwebsite\Excel\Concerns\FromView;

class ApplicantSourceExport implements FromView
{
    public $from;
    public $to;
    public $source_id;

    public function __construct($from, $to, $source_id)
    {
        $this->from       = $from;
        $this->to         = $to;
        $this->source_id  = $source_id;
    }

    public function view(): View
    {
        $config = Configuration::find(1);
        $source = ApplicantSource::find($this->source_id);
        return view('reports.applicant-source', [
            'applicants' => Applicant::leftJoin('lineups','lineups.applicant_id','=','applicants.applicant_number')
                ->leftjoin('users','users.id','=','applicants.user_id')
                ->leftJoin('applicant_statuses','applicant_statuses.id','=','lineups.lineup_status_id')
                ->leftJoin('applicant_positions','applicant_positions.applicant_id','=','applicants.applicant_number')
                ->leftJoin('applicant_sources','applicant_sources.id','=','source_id')
                ->select('applicants.*','users.fname as first_name','users.lname as last_name','applicant_sources.name as source_name','applicant_positions.position_applied','applicant_statuses.name as status_name')
                ->where('source_id', $this->source_id)
                ->whereBetween('date_applied', [$this->from, $this->to])
                ->orderBy('applicants.fname')
                ->get(),
            'source' => $source,
            'from'   => $this->from,
            'to'     => $this->to,
            'logo'   => storage_path('app/public/'.$config->logo)
        ]);
    }
}
