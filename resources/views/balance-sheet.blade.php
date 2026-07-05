<!DOCTYPE html>
<html>
<head>
    <title>Оборотно-сальдовая ведомость</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .form-group { display: flex; gap: 10px; align-items: center; margin-bottom: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
        th { background-color: #f2f2f2; text-align: center; }
        .text-left { text-align: left; }
        .btn { padding: 6px 12px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Оборотно-сальдовая ведомость</h1>

    <form method="GET" action="{{ url('/balance-sheet') }}" class="form-group">
        <label>Начало периода:
            <input type="date" name="start_date" value="{{ $startDate }}">
        </label>
        <label>Конец периода:
            <input type="date" name="end_date" value="{{ $endDate }}">
        </label>
        <button type="submit" class="btn">Сформировать</button>
    </form>

    @if(count($report) > 0)
        <table>
            <thead>
                <tr>
                    <th class="text-left">Счет</th>
                    <th>Сальдо на начало (Дт)</th>
                    <th>Сальдо на начало (Кт)</th>
                    <th>Оборот по дебету</th>
                    <th>Оборот по кредиту</th>
                    <th>Сальдо на конец (Дт)</th>
                    <th>Сальдо на конец (Кт)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report as $row)
                    <tr>
                        <td class="text-left">{{ $row['account'] }}</td>
                        <td>{{ number_format($row['opening_debit'], 2, '.', ' ') }}</td>
                        <td>{{ number_format($row['opening_credit'], 2, '.', ' ') }}</td>
                        <td>{{ number_format($row['debit_turnover'], 2, '.', ' ') }}</td>
                        <td>{{ number_format($row['credit_turnover'], 2, '.', ' ') }}</td>
                        <td>{{ number_format($row['closing_debit'], 2, '.', ' ') }}</td>
                        <td>{{ number_format($row['closing_credit'], 2, '.', ' ') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Нет данных за выбранный период.</p>
    @endif
</body>
</html>