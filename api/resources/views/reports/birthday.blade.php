<table class="table align-middle fs-6 gy-5 bordered">
    <tbody>
        <tr>
            <td colspan="6" style="text-align: center">
                <img src="{{ $logo }}" alt="IRIS Online" align="center">
            </td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: center; font-size: 16px">Applicant Birthday List Report</td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: center; font-size: 16px">
                For the month of {{ \Carbon\Carbon::parse('2023-'.$month.'-01')->format('F') }}
            </td>
        </tr>
    </tbody>
</table>
<table class="table align-middle fs-6 gy-5 bordered">
    <thead>
        <tr>
            <th style="font-size: 16px">Applicant Name</th>
            <th style="font-size: 16px">Birthdate</th>
            <th style="font-size: 16px">Age</th>
            <th style="font-size: 16px">Status</th>
            <th style="font-size: 16px">Mobile Number</th>
            <th style="font-size: 16px">Email</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($applicants as $applicant)
        <tr>
            <td style="font-size: 16px">{{ $applicant->fullname }}</td>
            <td style="font-size: 16px">{{ isset($applicant->birthdate) ? \Carbon\Carbon::parse($applicant->birthdate)->format('d F Y') : '' }}</td>
            <td style="font-size: 16px">{{ $applicant->age }}</td>
            <td style="font-size: 16px">{{ $applicant->status_name }}</td>
            <td style="font-size: 16px">{{ $applicant->mobile_number }}</td>
            <td style="font-size: 16px">{{ $applicant->email }}</td>
        </tr>
        @endforeach
    </tbody>
</table>