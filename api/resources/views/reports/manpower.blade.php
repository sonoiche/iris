<table class="table align-middle fs-6 gy-5 bordered">
    <tbody>
        <tr>
            <td colspan="6" style="text-align: center">
                <img src="{{ $logo }}" alt="IRIS Online" align="center">
            </td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: center; font-size: 16px">Manpower Request Report</td>
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
            <th style="font-size: 16px">Date Created</th>
            <th style="font-size: 16px">Principal</th>
            <th style="font-size: 16px">Manpower Request</th>
            <th style="font-size: 16px">Status</th>
            <th style="font-size: 16px">User</th>
            <th style="font-size: 16px">Position(s)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($joborders as $joborder)
        <tr>
            <td style="font-size: 16px">{{ $joborder['created_at'] }}</td>
            <td style="font-size: 16px">{{ $joborder['principal_name'] }}</td>
            <td style="font-size: 16px">{{ $joborder['job_order'] }}</td>
            <td style="font-size: 16px">{{ $joborder['status'] }}</td>
            <td style="font-size: 16px">{{ $joborder['fullname'] }}</td>
            <td style="font-size: 16px">{{ $joborder['position_count'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>