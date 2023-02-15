<table class="table align-middle fs-6 gy-5 bordered">
    <tbody>
        <tr>
            <td colspan="6" style="text-align: center; font-size: 16px">Audit Trail Report by Create, Update Activity of Applicant Log {{ $report_type }}</td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: center; font-size: 16px">
                From: {{ \Carbon\Carbon::parse($from)->format('d F Y') }} - {{ \Carbon\Carbon::parse($to)->format('d F Y') }}
            </td>
        </tr>
    </tbody>
</table>
<table class="table align-middle fs-6 gy-5 bordered">
    <thead>
        <tr>
            <th style="font-size: 16px">Date/Time</th>
            <th style="font-size: 16px">Applicant Number</th>
            <th style="font-size: 16px">Applicant Name</th>
            <th style="font-size: 16px">Module</th>
            <th style="font-size: 16px">Action</th>
            <th style="font-size: 16px">Name of User</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($activities as $item)
        <tr>
            <td style="font-size: 16px">{{ $item->created_at_display }}</td>
            <td style="font-size: 16px">{{ $item->applicant_id }}</td>
            <td style="font-size: 16px">{{ $item->applicant_name }}</td>
            <td style="font-size: 16px">{{ $item->module }}</td>
            <td style="font-size: 16px">{{ $item->user_action }}</td>
            <td style="font-size: 16px">{{ $item->username }}</td>
        </tr>
        @endforeach
    </tbody>
</table>