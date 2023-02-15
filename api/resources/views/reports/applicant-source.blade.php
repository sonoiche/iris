<table class="table align-middle fs-6 gy-5 bordered">
    <tbody>
        <tr>
            <td colspan="9" style="text-align: center; font-size: 16px">Applicant Source {{ $source->name }}</td>
        </tr>
        <tr>
            <td colspan="9" style="text-align: center; font-size: 16px">
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
            <th style="font-size: 16px">Source</th>
            <th style="font-size: 16px">Last Updated</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($applicants as $applicant)
        <tr>
            <td style="font-size: 16px">{{ $applicant->fullname }}</td>
            <td style="font-size: 16px">{{ $applicant->mobile_number }}</td>
            <td style="font-size: 16px">{{ $applicant->email }}</td>
            <td style="font-size: 16px">{{ $applicant->date_applied }}</td>
            <td style="font-size: 16px">{{ $applicant->status }}</td>
            <td style="font-size: 16px">{{ $applicant->position_applied }}</td>
            <td style="font-size: 16px">{{ $applicant->encoder }}</td>
            <td style="font-size: 16px">{{ $applicant->source_name }}</td>
            <td style="font-size: 16px">{{ $applicant->updated_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>