<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <title>Factură {{ $invoice->number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 40px;
        }
        .header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2563eb;
            font-size: 28px;
            margin: 0;
        }
        .header .subtitle {
            color: #666;
            font-size: 14px;
        }
        .invoice-info {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .invoice-info .left, .invoice-info .right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .invoice-info .right {
            text-align: right;
        }
        .label {
            font-weight: bold;
            color: #555;
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .value {
            font-size: 14px;
            margin-bottom: 12px;
        }
        table.details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.details th {
            background-color: #2563eb;
            color: white;
            padding: 10px 15px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        table.details td {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
        }
        table.details tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .total-row {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            color: #2563eb;
            padding: 20px 0;
            border-top: 2px solid #2563eb;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>FACTURĂ</h1>
        <div class="subtitle">ServiceAuto Platform</div>
    </div>

    <div class="invoice-info">
        <div class="left">
            <div class="label">Service</div>
            <div class="value">{{ $invoice->intervention->service->name ?? 'N/A' }}</div>

            <div class="label">Adresă</div>
            <div class="value">{{ $invoice->intervention->service->address ?? '' }}, {{ $invoice->intervention->service->city ?? '' }}</div>

            <div class="label">Telefon</div>
            <div class="value">{{ $invoice->intervention->service->phone ?? 'N/A' }}</div>
        </div>
        <div class="right">
            <div class="label">Nr. Factură</div>
            <div class="value">{{ $invoice->number }}</div>

            <div class="label">Data emiterii</div>
            <div class="value">{{ $invoice->issued_at?->format('d.m.Y') ?? now()->format('d.m.Y') }}</div>

            <div class="label">Client / Mașină</div>
            <div class="value">
                {{ $invoice->intervention->car->user->name ?? 'N/A' }}<br>
                {{ $invoice->intervention->car->brand ?? '' }} {{ $invoice->intervention->car->model ?? '' }}
                ({{ $invoice->intervention->car->plate ?? '' }})
            </div>
        </div>
    </div>

    <table class="details">
        <thead>
            <tr>
                <th>#</th>
                <th>Descriere lucrare</th>
                <th>Tip</th>
                <th>Ore estimate</th>
                <th>Km</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>{{ $invoice->intervention->description }}</td>
                <td>{{ ucfirst($invoice->intervention->type) }}</td>
                <td>{{ $invoice->intervention->estimated_hours ?? '-' }}h</td>
                <td>{{ $invoice->intervention->km_at_intervention ? number_format($invoice->intervention->km_at_intervention, 0, ',', '.') : '-' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total-row">
        TOTAL: {{ number_format($invoice->total, 2, ',', '.') }} RON
    </div>

    <div class="footer">
        <p>Factură generată automat prin platforma ServiceAuto.</p>
        <p>Acest document nu necesită semnătură și ștampilă conform OG 12/2014.</p>
    </div>
</body>
</html>
