<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Turnover Balance Report</title>
    <style>
        body { font-family: sans-serif; padding: 24px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
        th:first-child, td:first-child,
        th:nth-child(2), td:nth-child(2) { text-align: left; }
        thead { background: #f5f5f5; }
        tfoot { font-weight: bold; background: #fafafa; }
        .filters { display: flex; gap: 12px; align-items: end; }
        .filters label { display: block; margin-bottom: 4px; }
        .filters input { padding: 8px; }
        .filters button { padding: 9px 14px; }
    </style>
</head>
<body>
<h1>Turnover Balance Report</h1>

<form method="GET" class="filters">
    <div>
        <label for="from">From</label>
        <input type="date" id="from" name="from" value="{{ $from->format('Y-m-d') }}">
    </div>

    <div>
        <label for="to">To</label>
        <input type="date" id="to" name="to" value="{{ $to->format('Y-m-d') }}">
    </div>

    <button type="submit">Show</button>
</form>

<table>
    <thead>
    <tr>
        <th>Code</th>
        <th>Account</th>
        <th>Opening debit</th>
        <th>Opening credit</th>
        <th>Turnover debit</th>
        <th>Turnover credit</th>
        <th>Closing debit</th>
        <th>Closing credit</th>
    </tr>
    </thead>
    <tbody>
    @foreach($rows as $row)
        <tr>
            <td>{{ $row['account']->code }}</td>
            <td>{{ $row['account']->name }}</td>
            <td>{{ $row['opening_debit'] }}</td>
            <td>{{ $row['opening_credit'] }}</td>
            <td>{{ $row['turnover_debit'] }}</td>
            <td>{{ $row['turnover_credit'] }}</td>
            <td>{{ $row['closing_debit'] }}</td>
            <td>{{ $row['closing_credit'] }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="2">Total</td>
        <td>{{ $totals['opening_debit'] }}</td>
        <td>{{ $totals['opening_credit'] }}</td>
        <td>{{ $totals['turnover_debit'] }}</td>
        <td>{{ $totals['turnover_credit'] }}</td>
        <td>{{ $totals['closing_debit'] }}</td>
        <td>{{ $totals['closing_credit'] }}</td>
    </tr>
    </tfoot>
</table>
</body>
</html>
