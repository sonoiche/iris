<?php

namespace App\Exports;

use App\Models\Client\Applicant;
use Illuminate\Contracts\View\View;
use App\Models\Client\ApplicantStatus;
use Maatwebsite\Excel\Concerns\FromView;

class DeploymentExport implements FromView
{
    public $from;
    public $to;
    public $principal_id;

    public function __construct($from, $to, $principal_id)
    {
        $this->from          = $from;
        $this->to            = $to;
        $this->principal_id  = $principal_id;
    }

    public function view(): View
    {
        $principal_id = $this->principal_id;
        return view('reports.deployment', [
            'applicants' => Applicant::leftJoin('lineups','lineups.applicant_id','=','applicants.applicant_number')
                ->leftJoin('job_orders','job_orders.id','=','lineups.job_order_id')
                ->leftJoin('job_order_positions','job_order_positions.id','=','lineups.position_id')
                ->leftJoin('processings','processings.applicant_id','=','applicants.applicant_number')
                ->leftJoin('applicant_positions','applicant_positions.applicant_id','=','applicants.applicant_number')
                ->leftJoin('countries','countries.id','=','processings.country_id')
                ->select('applicants.*','applicant_positions.position_applied','job_orders.job_order_number as order_number','job_order_positions.position_title','processings.worksite','countries.name as country_name','processings.deployed_date')
                ->when($principal_id, function ($query, $principal_id) {
                    $query->where('principals.id', $principal_id);
                })
                ->whereBetween('processings.deployed_date', [$this->from, $this->to])
                ->where('lineups.lineup_status_id', ApplicantStatus::DEPLOYED)
                ->orderBy('applicants.fname')
                ->get(),
            'from'   => $this->from,
            'to'     => $this->to
        ]);
    }
}
