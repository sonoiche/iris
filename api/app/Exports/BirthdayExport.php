<?php

namespace App\Exports;

use App\Models\Client\Applicant;
use Illuminate\Contracts\View\View;
use App\Models\Client\Configuration;
use Maatwebsite\Excel\Concerns\FromView;

class BirthdayExport implements FromView
{
    public $month;
    public $status_id;

    public function __construct($month, $status_id)
    {
        $this->month    = $month;
        $this->status_id  = $status_id;
    }

    public function view(): View
    {
        $config      = Configuration::find(1);
        $month       = json_decode($this->month, true);
        $status_id   = $this->status_id;
        $final_month = sprintf("%02d", $month['month'] + 1);
        return view('reports.birthday', [
            'applicants' => Applicant::leftJoin('lineups','lineups.applicant_id','=','applicants.applicant_number')
                ->leftJoin('applicant_statuses','applicant_statuses.id','=','lineups.lineup_status_id')
                ->select('applicants.*','applicant_statuses.name as status_name')
                ->whereRaw("month(birthdate) = ?", [$final_month])
                ->when($status_id, function ($query, $status_id) {
                    $query->where('lineups.lineup_status_id', $status_id);
                })
                ->orderBy('applicants.fname')
                ->get(),
            'month'   => $final_month,
            'logo'   => storage_path('app/public/'.$config->logo)
        ]);
    }
}
