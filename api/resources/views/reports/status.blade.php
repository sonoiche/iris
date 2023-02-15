<table class="table align-middle fs-6 gy-5 bordered">
    <tbody>
        <tr>
            <td colspan="8" style="text-align: center; font-size: 16px">Applicant Status {{ $status->name }}</td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center; font-size: 16px">
                From: {{ \Carbon\Carbon::parse($from)->format('d F Y') }} - {{ \Carbon\Carbon::parse($to)->format('d F Y') }}
            </td>
        </tr>
    </tbody>
</table>
<table class="table align-middle fs-6 gy-5 bordered">
    <thead>
        <tr>
            <th style="font-size: 16px">Applicant Name</th>
            <th style="font-size: 16px">Mobile No.</th>
            <th style="font-size: 16px">Email Address</th>
            <th style="font-size: 16px">Date Applied</th>
            <th style="font-size: 16px">Status</th>
            <th style="font-size: 16px">Position Applied</th>
            <th style="font-size: 16px">Encoder</th>
            <th style="font-size: 16px">Last Updated</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($applicants as $applicant)
        <tr>
            <td style="font-size: 16px">{{ $applicant->fullname }}</td>
            <td style="font-size: 16px">{{ $applicant->mobile_number }}</td>
            <td style="font-size: 16px">{{ $applicant->email }}</td>
            <td style="font-size: 16px">{{ isset($applicant->date_applied) ? \Carbon\Carbon::parse($applicant->date_applied)->format('d F Y') : '' }}</td>
            <td style="font-size: 16px">{{ $applicant->status_name }}</td>
            <td style="font-size: 16px">{{ $applicant->position_applied }}</td>
            <td style="font-size: 16px">{{ $applicant->first_name.' '.$applicant->last_name }}</td>
            <td style="font-size: 16px">{{ \Carbon\Carbon::parse($applicant->updated_at)->format('d F Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>