<?php

namespace App\Exports;

use App\Models\Client\Applicant;
use App\Models\Client\Configuration;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ApplicantExport implements FromView
{
    public $from;
    public $to;
    public $user_id;

    public function __construct($from, $to, $user_id)
    {
        $this->from     = $from;
        $this->to       = $to;
        $this->user_id  = $user_id;
    }

    public function view(): View
    {
        $config = Configuration::find(1);
        return view('reports.applicants', [
            'applicants' => Applicant::leftJoin('lineups','lineups.applicant_id','=','applicants.applicant_number')
                ->leftJoin('applicant_statuses','applicant_statuses.id','=','lineups.lineup_status_id')
                ->leftjoin('users','users.id','=','applicants.user_id')
                ->leftJoin('applicant_sources','applicant_sources.id','=','source_id')
                ->leftJoin('applicant_positions','applicant_positions.applicant_id','=','applicants.applicant_number')
                ->select('applicants.*','users.fname as first_name','users.lname as last_name','applicant_sources.name as source_name','applicant_positions.position_applied','applicant_statuses.name as status_name')
                ->when($this->user_id, function ($query, $user_id) {
                    $query->where('applicants.user_id', $user_id);
                })
                ->whereRaw("date(applicants.created_at) between ? and ?", [$this->from, $this->to])
                ->get(),
            'from'   => $this->from,
            'to'     => $this->to,
            'logo'   => storage_path('app/public/'.$config->logo)
        ]);
    }
}
