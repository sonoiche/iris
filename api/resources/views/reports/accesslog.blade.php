<table class="table align-middle fs-6 gy-5 bordered">
    <tbody>
        <tr>
            <td colspan="4" style="text-align: center">
                <img src="{{ $logo }}" alt="IRIS Online" align="center">
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center; font-size: 16px">Audit Trail Report by Create, Update Activity of Applicant Log {{ $report_type }}</td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center; font-size: 16px">
                From: {{ \Carbon\Carbon::parse($from)->format('d F Y') }} - {{ \Carbon\Carbon::parse($to)->format('d F Y') }}
            </td>
        </tr>
    </tbody>
</table>
<table class="table align-middle fs-6 gy-5 bordered">
    <thead>
        <tr>
            <th style="font-size: 16px">Date/Time</th>
            <th style="font-size: 16px">Name of User</th>
            <th style="font-size: 16px">Email Address</th>
            <th style="font-size: 16px">IP Address</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($activities as $item)
        <tr>
            <td style="font-size: 16px">{{ $item->created_at_display }}</td>
            <td style="font-size: 16px">{{ $item->username }}</td>
            <td style="font-size: 16px">{{ isset($item->user) ? $item->user->email : '' }}</td>
            <td style="font-size: 16px">{{ $item->ip_address }}</td>
        </tr>
        @endforeach
    </tbody>
</table>