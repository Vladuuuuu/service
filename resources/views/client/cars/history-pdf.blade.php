<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <title>Istoric {{ $car->brand }} {{ $car->model }} — {{ $car->plate }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a1a; margin: 35px; }
        .header { border-bottom: 3px solid #4f46e5; padding-bottom: 18px; margin-bottom: 25px; }
        .header h1 { color: #4f46e5; font-size: 22px; margin: 0 0 2px; }
        .header .sub { color: #666; font-size: 12px; }
        .info-grid { display: table; width: 100%; margin-bottom: 25px; }
        .info-col { display: table-cell; width: 50%; vertical-align: top; }
        .info-col.right { text-align: right; }
        .label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; color: #888; font-weight: bold; margin-bottom: 2px; }
        .val { font-size: 13px; margin-bottom: 10px; }
        .section-title { font-size: 13px; font-weight: bold; color: #4f46e5; margin: 25px 0 10px; padding-bottom: 5px; border-bottom: 1px solid #e5e7eb; }
        table.history { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.history th { background: #4f46e5; color: #fff; padding: 7px 10px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.3px; }
        table.history td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px; }
        table.history tr:nth-child(even) { background: #f9fafb; }
        .km-badge { background: #ecfdf5; color: #059669; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: bold; }
        .status-completed { color: #059669; font-weight: bold; }
        .status-cancelled { color: #dc2626; }
        .status-pending { color: #d97706; }
        .status-in_progress { color: #2563eb; }
        .summary { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 15px; margin-bottom: 20px; }
        .summary-grid { display: table; width: 100%; }
        .summary-item { display: table-cell; text-align: center; }
        .summary-item .num { font-size: 20px; font-weight: bold; color: #1a1a1a; }
        .summary-item .lbl { font-size: 9px; text-transform: uppercase; color: #888; margin-top: 2px; }
        .footer { margin-top: 40px; padding-top: 15px; border-top: 1px solid #e5e7eb; color: #999; font-size: 9px; text-align: center; }
        .stamp { float: right; border: 2px solid #4f46e5; color: #4f46e5; padding: 5px 15px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: uppercase; transform: rotate(-5deg); margin-top: -10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="stamp">Document verificat</div>
        <h1>ISTORIC VEHICUL</h1>
        <div class="sub">Raport generat de platforma ServiceAuto — {{ now()->format('d.m.Y H:i') }}</div>
    </div>

    <div class="info-grid">
        <div class="info-col">
            <div class="label">Proprietar</div>
            <div class="val">{{ $car->user->name }}</div>

            <div class="label">Marcă / Model</div>
            <div class="val">{{ $car->brand }} {{ $car->model }}</div>

            <div class="label">An fabricație</div>
            <div class="val">{{ $car->year }}</div>
        </div>
        <div class="info-col right">
            <div class="label">Nr. Înmatriculare</div>
            <div class="val" style="font-size: 18px; font-weight: bold;">{{ $car->plate }}</div>

            <div class="label">Kilometraj actual</div>
            <div class="val" style="font-size: 16px; font-weight: bold;">{{ number_format($car->km_current, 0, ',', '.') }} km</div>
        </div>
    </div>

    @php
        $completed = $car->interventions->where('status', 'completed');
        $totalCost = $completed->sum('final_cost');
        $totalInvoiced = $car->interventions->sum(fn($i) => $i->invoice ? $i->invoice->total : 0);
    @endphp

    <div class="summary">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="num">{{ $car->interventions->count() }}</div>
                <div class="lbl">Intervenții total</div>
            </div>
            <div class="summary-item">
                <div class="num">{{ $completed->count() }}</div>
                <div class="lbl">Finalizate</div>
            </div>
            <div class="summary-item">
                <div class="num">{{ number_format($totalCost, 0, ',', '.') }} RON</div>
                <div class="lbl">Cost total lucrări</div>
            </div>
            <div class="summary-item">
                <div class="num">{{ $car->interventions->count() > 0 && $car->interventions->first()->km_at_intervention ? number_format($car->interventions->last()->km_at_intervention, 0, ',', '.') : '—' }}</div>
                <div class="lbl">Primul km înregistrat</div>
            </div>
        </div>
    </div>

    <div class="section-title">Evoluție kilometraj</div>
    @php
        $withKm = $car->interventions->whereNotNull('km_at_intervention')->sortBy('created_at');
    @endphp
    @if($withKm->count())
        <table class="history">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Kilometraj</th>
                    <th>Service</th>
                    <th>Tip intervenție</th>
                    <th>Diferență km</th>
                </tr>
            </thead>
            <tbody>
                @php $prevKm = null; @endphp
                @foreach($withKm as $i)
                    <tr>
                        <td>{{ $i->created_at->format('d.m.Y') }}</td>
                        <td><span class="km-badge">{{ number_format($i->km_at_intervention, 0, ',', '.') }} km</span></td>
                        <td>{{ $i->service->name }}</td>
                        <td>{{ ucfirst($i->type) }}</td>
                        <td>{{ $prevKm !== null ? '+' . number_format($i->km_at_intervention - $prevKm, 0, ',', '.') . ' km' : '—' }}</td>
                    </tr>
                    @php $prevKm = $i->km_at_intervention; @endphp
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #999;">Nu există date de kilometraj înregistrate.</p>
    @endif

    <div class="section-title">Istoric complet intervenții</div>
    @if($car->interventions->count())
        <table class="history">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Service</th>
                    <th>Tip</th>
                    <th>Descriere</th>
                    <th>Km</th>
                    <th>Cost</th>
                    <th>Status</th>
                    <th>Factură</th>
                </tr>
            </thead>
            <tbody>
                @foreach($car->interventions as $intervention)
                    <tr>
                        <td>{{ $intervention->created_at->format('d.m.Y') }}</td>
                        <td>{{ $intervention->service->name }}</td>
                        <td>{{ ucfirst($intervention->type) }}</td>
                        <td style="max-width: 150px;">{{ \Illuminate\Support\Str::limit($intervention->description, 60) }}</td>
                        <td>{{ $intervention->km_at_intervention ? number_format($intervention->km_at_intervention, 0, ',', '.') : '—' }}</td>
                        <td style="font-weight: bold;">{{ $intervention->final_cost ? number_format($intervention->final_cost, 0, ',', '.') . ' RON' : '—' }}</td>
                        <td class="status-{{ $intervention->status }}">
                            {{ match($intervention->status) {
                                'completed' => 'Finalizat',
                                'in_progress' => 'În lucru',
                                'pending' => 'Programat',
                                'cancelled' => 'Anulat',
                                default => $intervention->status,
                            } }}
                        </td>
                        <td>{{ $intervention->invoice ? $intervention->invoice->number : '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #999;">Nicio intervenție înregistrată.</p>
    @endif

    <div class="footer">
        <p>Acest document a fost generat automat de platforma ServiceAuto și certifică istoricul de service al vehiculului.</p>
        <p>Data generării: {{ now()->format('d.m.Y H:i') }} | Platforma ServiceAuto &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>
