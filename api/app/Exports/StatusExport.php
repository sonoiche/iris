<?php

namespace App\Exports;

use App\Models\Client\Applicant;
use Illuminate\Contracts\View\View;
use App\Models\Client\Configuration;
use App\Models\Client\ApplicantStatus;
use Maatwebsite\Excel\Concerns\FromView;

class StatusExport implements FromView
{
    public $from;
    public $to;
    public $status_id;

    public function __construct($from, $to, $status_id)
    {
        $this->from     = $from;
        $this->to       = $to;
        $this->status_id  = $status_id;
    }

    public function view(): View
    {
        $config = Configuration::find(1);
        return view('reports.status', [
            'applicants' => Applicant::leftJoin('lineups','lineups.applicant_id','=','applicants.applicant_number')
                ->leftjoin('users','users.id','=','applicants.user_id')
                ->leftJoin('applicant_statuses','applicant_statuses.id','=','lineups.lineup_status_id')
                ->leftJoin('applicant_positions','applicant_positions.applicant_id','=','applicants.applicant_number')
                ->select('applicants.*','users.fname as first_name','users.lname as last_name','applicant_positions.position_applied','applicant_statuses.name as status_name')
                ->where('lineups.lineup_status_id', $this->status_id)
                ->whereBetween('date_applied', [$this->from, $this->to])
                ->orderBy('applicants.fname')
                ->get(),
            'from'   => $this->from,
            'to'     => $this->to,
            'status' => ApplicantStatus::find($this->status_id),
            'logo'   => storage_path('app/public/'.$config->logo)
        ]);
    }
}
