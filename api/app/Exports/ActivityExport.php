<?php

namespace App\Exports;

use App\Models\Client\ActivityLog;
use Illuminate\Contracts\View\View;
use App\Models\Client\Configuration;
use Maatwebsite\Excel\Concerns\FromView;

class ActivityExport implements FromView
{
    public $from;
    public $to;
    public $user_id;
    public $report_type;

    public function __construct($from, $to, $user_id, $report_type)
    {
        $this->from         = $from;
        $this->to           = $to;
        $this->user_id      = $user_id;
        $this->report_type  = $report_type;
    }

    public function view(): View
    {
        $config      = Configuration::find(1);
        $report_type = $this->report_type;
        $user_id     = $this->user_id;
        $blade       = ($this->report_type == 'crud') ? 'reports.activity' : 'reports.accesslog';
        return view($blade, [
            'activities' => ActivityLog::with('user')->when($report_type, function ($query, $report_type) {
                $query->where('activity_type', $report_type);
            })
            ->when($user_id, function($query, $user_id) {
                $query->where('user_id', $user_id);
            })
            ->whereRaw("date(created_at) between ? and ?", [$this->from, $this->to])
            ->latest()
            ->get(),
            'from'   => $this->from,
            'to'     => $this->to,
            'report_type' => $this->report_type,
            'logo'   => storage_path('app/public/'.$config->logo)
        ]);
    }
}
