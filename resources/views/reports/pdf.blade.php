<!DOCTYPE html>
<html>

<head>
    <title>Worker Activity Report</title>
    <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10pt;
        margin: 1cm;
    }

    .header {
        text-align: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #2d3748;
    }

    .header h1 {
        font-size: 18pt;
        color: #2d3748;
        margin-bottom: 5px;
    }

    .report-info {
        font-size: 11pt;
        color: #4a5568;
        margin-bottom: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 9pt;
    }

    th {
        background-color: #2d3748;
        color: white;
        text-align: left;
        padding: 6px 8px;
        font-weight: bold;
    }

    td {
        padding: 6px 8px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: top;
    }

    tr:nth-child(even) {
        background-color: #f8fafc;
    }

    .duration-row td {
        background: #f1f5f9;
        font-weight: bold;
        color: #065f46;
    }

    .footer {
        text-align: center;
        margin-top: 20px;
        padding-top: 8px;
        border-top: 1px solid #e2e8f0;
        font-size: 8pt;
        color: #718096;
    }
    </style>
</head>

<body>
    <div class="header">
        <h1>SMART-WET WORKFORCE ACTIVITY REPORT</h1>
        <div class="report-info">
            @if(isset($date) && $date)
            Reporting Period: {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}
            @endif
            @if(isset($name) && $name)
            | Worker: {{ $name }}
            @endif
        </div>
    </div>
    <div class="timestamp">
        Report generated on: {{ now()->format('M d, Y g:i A') }} by {{ Auth::user()->name ?? 'System' }}
    </div>

    @php
    // Group by worker (uid) and date
    $workerGroups = $activities->groupBy(function($item) {
    return $item->uid . '|' . \Carbon\Carbon::parse($item->last_tap_in)->format('Y-m-d');
    });
    @endphp

    @foreach($workerGroups as $workerKey => $records)
    @php
    [$uid, $workDate] = explode('|', $workerKey);
    $sorted = $records->sortBy('last_tap_in');
    $first = $sorted->first();
    $last = $sorted->last();
    $checkpointCount = $records->pluck('checkpoint')->unique()->count();
    $duration = ($checkpointCount === 3 && $first && $last && $first->last_tap_in !== $last->last_tap_in)
    ? \Carbon\Carbon::parse($first->last_tap_in)->diff(\Carbon\Carbon::parse($last->last_tap_in))->format('%H:%I:%S')
    : 'Not done yet';
    @endphp
    <h3 style="margin-top:24px; color:#2d3748;">
        Worker: {{ $first->owner_name ?? 'Unknown' }} (UID: {{ $uid }}) | Date:
        {{ \Carbon\Carbon::parse($workDate)->format('F j, Y') }}
    </h3>
    <table>
        <thead>
            <tr>
                <th>Checkpoint</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records->sortBy('last_tap_in') as $activity)
            <tr>
                <td>{{ $activity->checkpoint }}</td>
                <td>{{ \Carbon\Carbon::parse($activity->last_tap_in)->format('g:i A') }}</td>
            </tr>
            @endforeach
            <tr class="duration-row">
                <td colspan="2">Duration to complete 3 checkpoints: {{ $duration }}</td>
            </tr>
        </tbody>
    </table>
    @endforeach

    <div class="footer">
        SMART-WET Plantation Workforce Tracking System | Confidential Report
    </div>
</body>

</html>