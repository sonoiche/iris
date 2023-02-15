<table class="table align-middle fs-6 gy-5 bordered">
    <tbody>
        <tr>
            <td colspan="7" style="text-align: center; font-size: 16px">Deployment Report</td>
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
            <th style="font-size: 16px">Deployment Date</th>
            <th style="font-size: 16px">Applicant Name</th>
            <th style="font-size: 16px">Email</th>
            <th style="font-size: 16px">Manpower Request</th>
            <th style="font-size: 16px">Destination</th>
            <th style="font-size: 16px">Country</th>
            <th style="font-size: 16px">Agreed Salary</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($applicants as $item)
        <tr>
            <td style="font-size: 16px">{{ $applicant->deployed_date }}</td>
            <td style="font-size: 16px">{{ $applicant->fullname }}</td>
            <td style="font-size: 16px">{{ $applicant->email }}</td>
            <td style="font-size: 16px">{{ $applicant->order_number }}</td>
            <td style="font-size: 16px">{{ $applicant->worksite }}</td>
            <td style="font-size: 16px">{{ $applicant->country_name }}</td>
            <td style="font-size: 16px">{{ isset($applicant->salary) ? number_format($applicant->salary, 2) : '0.00' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>