<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deliveries Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h1>Deliveries Report</h1>
    @if($startDate && $endDate)
    <p><strong>Date Range:</strong> {{ $startDate }} - {{ $endDate }}</p>
    @elseif($startDate)
        <p><strong>From:</strong> {{ $startDate }}</p>
    @elseif($endDate)
        <p><strong>To:</strong> {{ $endDate }}</p>
@endif

    @foreach($deliveriesGrouped as $providerName => $deliveries)
        <h2>Provider: {{ $providerName }}</h2>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Receiver</th>
                    <th>Address</th>
                    <th>Worker</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deliveries as $delivery)
                    <tr>
                        <td>{{ $delivery->code }}</td>
                        <td>{{ $delivery->receiver_name }}</td>
                        <td>{{ $delivery->receiver_address }}</td>
                        <td>{{ $delivery->worker->user->name }}</td>
                        <td>{{ $delivery->delivery_date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>
