<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use App\Models\Client\Configuration;
use App\Models\Client\Employer\JobOrder;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Client\Employer\JobOrderPosition;

class ManpowerExport implements FromView
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

        $joborders    = JobOrder::leftJoin('principals','principals.id','=','job_orders.principal_id')
            ->leftJoin('users','users.id','=','job_orders.user_id')
            ->select('job_orders.*','principals.name as principal_name','users.fname','users.lname')
            ->when($principal_id, function ($query, $principal_id) {
                $query->where('job_orders.principal_id', $principal_id);
            })
            ->when($job_order_id, function ($query, $job_order_id) {
                $query->where('job_orders.id', $job_order_id);
            })
            ->whereRaw("date(job_orders.created_at) between ? and ?", [$this->from, $this->to])
            ->get();

        $joborders_array = [];
        if(!empty($joborders)) {
            foreach ($joborders as $joborder) {
                $nestedData['id']               = $joborder->id;
                $nestedData['principal_name']   = $joborder->principal_name;
                $nestedData['job_order']        = $joborder->job_order_number;
                $nestedData['fullname']         = $joborder->fname.' '.$joborder->lname;
                $nestedData['status']           = $joborder->status;
                $nestedData['position_count']   = JobOrderPosition::where('job_order_id',$joborder->id)->count();
                $nestedData['created_at']       = isset($joborder->created_at) ? Carbon::parse($joborder->created_at)->format('d F Y') : '';
                $joborders_array[]              = $nestedData;
            }
        }

        return view('reports.manpower', [
            'joborders' => $joborders_array,
            'from'      => $this->from,
            'to'        => $this->to,
            'logo'      => storage_path('app/public/'.$config->logo)
        ]);
    }
}
