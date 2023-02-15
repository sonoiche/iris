<table class="table align-middle fs-6 gy-5 bordered">
    <tbody>
        <tr>
            <td colspan="7" style="text-align: center; font-size: 16px">Interview Calendar Report</td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: center; font-size: 16px">
                From: {{ \Carbon\Carbon::parse($from)->format('d F Y') }} - {{ \Carbon\Carbon::parse($to)->format('d F Y') }}
            </td>
        </tr>
    </tbody>
</table>
<table class="table align-middle fs-6 gy-5 bordered">
    <thead>
        <tr>
            <th style="font-size: 16px">Interview Date</th>
            <th style="font-size: 16px">Applicant Name</th>
            <th style="font-size: 16px">Email</th>
            <th style="font-size: 16px">Mobile Number</th>
            <th style="font-size: 16px">Principal</th>
            <th style="font-size: 16px">Manpower Request</th>
            <th style="font-size: 16px">Venue</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($applicants as $applicant)
        <tr>
            <td style="font-size: 16px">{{ isset($applicant->interview_date) ? \Carbon\Carbon::parse($applicant->interview_date)->format('d F Y') : '' }}</td>
            <td style="font-size: 16px">{{ $applicant->fullname }}</td>
            <td style="font-size: 16px">{{ $applicant->email }}</td>
            <td style="font-size: 16px">{{ $applicant->mobile_number }}</td>
            <td style="font-size: 16px">{{ $applicant->principal_name }}</td>
            <td style="font-size: 16px">{{ $applicant->order_number }}</td>
            <td style="font-size: 16px">{{ $applicant->venue }}</td>
        </tr>
        @endforeach
    </tbody>
</table>