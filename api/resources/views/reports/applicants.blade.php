<table class="table align-middle fs-6 gy-5 bordered">
    <tbody>
        <tr>
            <td colspan="9" style="text-align: center; font-size: 16px">Applicant Encoded List Report</td>
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
            <th style="font-size: 16px">Date Applied</th>
            <th style="font-size: 16px">Status</th>
            <th style="font-size: 16px">Mobile Number</th>
            <th style="font-size: 16px">Position Applied</th>
            <th style="font-size: 16px">Encoder</th>
            <th style="font-size: 16px">Source</th>
            <th style="font-size: 16px">Last Updated</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($applicants as $key => $item)
        <tr>
            <td style="font-size: 16px">{{ $item->fullname }}</td>
            <td style="font-size: 16px">{{ isset($item->date_applied) ? \Carbon\Carbon::parse($item->date_applied)->format('d M Y') : '' }}</td>
            <td style="font-size: 16px">{{ $item->status_name }}</td>
            <td style="font-size: 16px">{{ $item->mobile_number }}</td>
            <td style="font-size: 16px">{{ $item->position_applied }}</td>
            <td style="font-size: 16px">{{ $item->first_name.' '.$item->last_name }}</td>
            <td style="font-size: 16px">{{ $item->source_name }}</td>
            <td style="font-size: 16px">{{ isset($item->updated_at) ? \Carbon\Carbon::parse($item->updated_at)->format('F d, Y h:i A') : '' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>