<?php

namespace App\Exports;

use App\Models\Client\Applicant;
use Illuminate\Contracts\View\View;
use App\Models\Client\Configuration;
use Maatwebsite\Excel\Concerns\FromView;

class InterviewExport implements FromView
{
    public $from;
    public $to;
    public $principal_id;
    public $job_order_id;

    public function __construct($from, $to, $principal_id, $job_order_id)
    {
        $this->from          = $from;
        $this->to            = $to;
        $this->principal_id  = $principal_id;
        $this->job_order_id  = $job_order_id;
    }

    public function view(): View
    {
        $config       = Configuration::find(1);
        $principal_id = $this->principal_id;
        $job_order_id = $this->job_order_id;
        return view('reports.interview', [
            'applicants' => Applicant::leftJoin('interviews','interviews.applicant_id','=','applicants.applicant_number')
                ->leftJoin('principals','principals.id','=','interviews.principal_id')
                ->leftJoin('job_orders','job_orders.id','=','interviews.job_order_id')
                ->leftJoin('job_order_positions','job_order_positions.id','=','interviews.position_id')
                ->select('applicants.*','principals.name as principal_name','job_orders.job_order_number as order_number','job_order_positions.position_title','interviews.interview_date','interviews.venue')
                ->when($principal_id, function ($query, $principal_id) {
                    $query->where('principals.id', $principal_id);
                })
                ->when($job_order_id, function ($query, $job_order_id) {
                    $query->where('job_orders.id', $job_order_id);
                })
                ->whereBetween('interview_date', [$this->from, $this->to])
                ->orderBy('applicants.fname')
                ->get(),
            'from'   => $this->from,
            'to'     => $this->to,
            'logo'   => storage_path('app/public/'.$config->logo)
        ]);
    }
}
