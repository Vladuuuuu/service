<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <title>Factură {{ $invoice->number }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1f2937;
            margin: 40px;
            line-height: 1.5;
        }
        .header {
            display: table;
            width: 100%;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 28px;
        }
        .header-left, .header-right {
            display: table-cell;
            vertical-align: middle;
        }
        .header-right {
            text-align: right;
        }
        .header h1 {
            color: #2563eb;
            font-size: 30px;
            letter-spacing: 4px;
        }
        .header .nr {
            color: #6b7280;
            font-size: 13px;
            margin-top: 4px;
        }
        .header .issued {
            color: #9ca3af;
            font-size: 11px;
            margin-top: 3px;
        }
        .parties {
            display: table;
            width: 100%;
            margin-bottom: 28px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }
        .party {
            display: table-cell;
            width: 50%;
            padding: 16px 20px;
            vertical-align: top;
        }
        .party + .party {
            border-left: 1px solid #e5e7eb;
        }
        .party-label {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #9ca3af;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .party-name {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 5px;
        }
        .party-detail {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 2px;
        }
        .car-badge {
            display: inline-block;
            margin-top: 6px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 11px;
            font-weight: bold;
            padding: 3px 10px;
            border-radius: 20px;
            letter-spacing: 0.5px;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.items thead tr {
            background-color: #2563eb;
        }
        table.items thead th {
            color: white;
            padding: 10px 14px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: bold;
        }
        table.items thead th:first-child { text-align: left; }
        table.items thead th:not(:first-child) { text-align: right; }
        table.items tbody td {
            padding: 10px 14px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 12px;
        }
        table.items tbody td:first-child { text-align: left; color: #374151; }
        table.items tbody td:not(:first-child) { text-align: right; color: #6b7280; }
        table.items tbody td.amount { font-weight: bold; color: #111827; }
        table.items tbody tr:nth-child(even) { background-color: #f9fafb; }
        table.items tfoot td {
            padding: 10px 14px;
            font-size: 12px;
            text-align: right;
        }
        table.items tfoot tr.subtotal td {
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        table.items tfoot tr.total-row td {
            font-size: 16px;
            font-weight: bold;
            color: #2563eb;
            border-top: 2px solid #2563eb;
            padding-top: 12px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            color: #9ca3af;
            font-size: 10px;
            text-align: center;
        }
        .km-note {
            font-size: 11px;
            color: #9ca3af;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-left">
            <h1>FACTURĂ</h1>
            <div class="nr">Nr. {{ $invoice->number }}</div>
            <div class="issued">Data: {{ $invoice->issued_at?->format('d.m.Y') ?? now()->format('d.m.Y') }}</div>
        </div>
        <div class="header-right">
            <div style="font-size:11px; color:#9ca3af;">Intervenție #{{ $invoice->intervention->id }}</div>
            @if($invoice->intervention->km_at_intervention)
                <div style="font-size:11px; color:#9ca3af; margin-top:3px;">Km: {{ number_format($invoice->intervention->km_at_intervention, 0, ',', '.') }}</div>
            @endif
        </div>
    </div>

    <div class="parties">
        <div class="party">
            <div class="party-label">Prestator</div>
            <div class="party-name">{{ $invoice->intervention->service->name ?? 'N/A' }}</div>
            @if($invoice->intervention->service->address ?? false)
                <div class="party-detail">{{ $invoice->intervention->service->address }}, {{ $invoice->intervention->service->city }}</div>
            @endif
            @if($invoice->intervention->service->phone ?? false)
                <div class="party-detail">Tel: {{ $invoice->intervention->service->phone }}</div>
            @endif
        </div>
        <div class="party">
            <div class="party-label">Beneficiar</div>
            <div class="party-name">{{ $invoice->intervention->car->user->name ?? 'N/A' }}</div>
            <div class="party-detail">{{ $invoice->intervention->car->user->email ?? '' }}</div>
            <div class="car-badge">
                {{ $invoice->intervention->car->brand }} {{ $invoice->intervention->car->model }}
                &nbsp;·&nbsp;{{ $invoice->intervention->car->plate }}
                &nbsp;·&nbsp;{{ $invoice->intervention->car->year }}
            </div>
        </div>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th style="text-align:left;">Descriere</th>
                <th>Cantitate</th>
                <th>Preț unitar</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            {{-- Manoperă --}}
            <tr>
                <td>
                    <strong>Manoperă</strong>
                    <br><span style="font-size:11px; color:#9ca3af;">{{ ucfirst($invoice->intervention->type) }}
                    @if($invoice->intervention->estimated_hours)
                        &nbsp;· {{ $invoice->intervention->estimated_hours }}h
                    @endif
                    </span>
                </td>
                <td>1</td>
                <td>{{ number_format($invoice->intervention->deviz_manopera ?? $invoice->total, 2, ',', '.') }} RON</td>
                <td class="amount">{{ number_format($invoice->intervention->deviz_manopera ?? $invoice->total, 2, ',', '.') }} RON</td>
            </tr>

            {{-- Piese --}}
            @foreach($invoice->intervention->parts as $part)
                <tr>
                    <td>{{ $part->name }}</td>
                    <td>{{ rtrim(rtrim(number_format($part->quantity, 2, ',', '.'), '0'), ',') }}</td>
                    <td>{{ number_format($part->unit_price, 2, ',', '.') }} RON</td>
                    <td class="amount">{{ number_format($part->subtotal(), 2, ',', '.') }} RON</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            @if($invoice->intervention->parts->isNotEmpty())
                <tr class="subtotal">
                    <td colspan="3" style="text-align:right;">Manoperă</td>
                    <td>{{ number_format($invoice->intervention->deviz_manopera ?? 0, 2, ',', '.') }} RON</td>
                </tr>
                <tr class="subtotal">
                    <td colspan="3" style="text-align:right;">Piese</td>
                    <td>{{ number_format($invoice->intervention->partsTotal(), 2, ',', '.') }} RON</td>
                </tr>
            @endif
            <tr class="total-row">
                <td colspan="3" style="text-align:right;">TOTAL DE PLATĂ</td>
                <td>{{ number_format($invoice->total, 2, ',', '.') }} RON</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Factură generată automat prin platforma ServiceAuto &nbsp;·&nbsp; Conform OG 12/2014, documentul nu necesită semnătură și ștampilă.</p>
    </div>

</body>
</html>
