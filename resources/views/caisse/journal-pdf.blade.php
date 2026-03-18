<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; margin: 0; }
        .header { text-align: center; border-bottom: 3px solid #0891b2; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { font-size: 20px; color: #0891b2; margin: 0 0 3px; }
        .header p { color: #64748b; font-size: 10px; margin: 0; }
        .title { text-align: center; font-size: 16px; font-weight: bold; margin: 20px 0 5px; }
        .periode { text-align: center; color: #64748b; font-size: 11px; margin-bottom: 20px; }
        .summary { display: table; width: 100%; margin-bottom: 20px; }
        .summary-item { display: table-cell; width: 33.33%; text-align: center; padding: 12px; border: 1px solid #e2e8f0; }
        .summary-label { font-size: 9px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .summary-value { font-size: 18px; font-weight: bold; margin-top: 4px; }
        .green { color: #059669; }
        .red { color: #dc2626; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #0e7490; color: #fff; padding: 8px 10px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
        tr:nth-child(even) td { background: #f8fafc; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .footer { text-align: center; color: #94a3b8; font-size: 9px; margin-top: 30px; padding-top: 15px; border-top: 1px solid #e2e8f0; }
        .type-badge { padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: 600; }
        .type-entree { background: #dcfce7; color: #059669; }
        .type-sortie { background: #fee2e2; color: #dc2626; }
    </style>
</head>
<body>
    <div class="header">
        <h1>MediCare Pro</h1>
        <p>Centre Hospitalier &mdash; Journal de Caisse</p>
    </div>

    <div class="title">JOURNAL DE CAISSE</div>
    <div class="periode">Période : {{ $periode }} @if($typeFiltre !== 'tous') &mdash; Filtre : {{ $typeFiltre == 'entree' ? 'Entrées uniquement' : 'Sorties uniquement' }} @endif</div>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">Total Entrées</div>
            <div class="summary-value green">{{ number_format($totaux['entrees'], 0, ',', ' ') }} F</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Total Sorties</div>
            <div class="summary-value red">{{ number_format($totaux['sorties'], 0, ',', ' ') }} F</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Solde</div>
            <div class="summary-value {{ $totaux['solde'] >= 0 ? 'green' : 'red' }}">{{ number_format($totaux['solde'], 0, ',', ' ') }} F</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:15%;">Date</th>
                <th>Description</th>
                <th style="width:12%;">Type</th>
                <th style="width:15%;" class="text-right">Entrée</th>
                <th style="width:15%;" class="text-right">Sortie</th>
                <th style="width:15%;" class="text-right">Solde cumulé</th>
            </tr>
        </thead>
        <tbody>
            @php $soldeRunning = 0; @endphp
            @foreach($transactions as $t)
            @php
                $soldeRunning += $t->type == 'entree' ? $t->montant : -$t->montant;
            @endphp
            <tr>
                <td>{{ \Carbon\Carbon::parse($t->date)->format('d/m/Y') }}</td>
                <td>{{ $t->description }}</td>
                <td><span class="type-badge {{ $t->type == 'entree' ? 'type-entree' : 'type-sortie' }}">{{ $t->type == 'entree' ? 'Entrée' : 'Sortie' }}</span></td>
                <td class="text-right green">{{ $t->type == 'entree' ? number_format($t->montant, 0, ',', ' ') . ' F' : '-' }}</td>
                <td class="text-right red">{{ $t->type == 'sortie' ? number_format($t->montant, 0, ',', ' ') . ' F' : '-' }}</td>
                <td class="text-right bold">{{ number_format($soldeRunning, 0, ',', ' ') }} F</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background:#f1f5f9;">
                <td colspan="3" class="bold">TOTAUX</td>
                <td class="text-right bold green">{{ number_format($totaux['entrees'], 0, ',', ' ') }} F</td>
                <td class="text-right bold red">{{ number_format($totaux['sorties'], 0, ',', ' ') }} F</td>
                <td class="text-right bold">{{ number_format($totaux['solde'], 0, ',', ' ') }} F</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top:40px;">
        <div style="display:table;width:100%;">
            <div style="display:table-cell;width:50%;">
                <p style="font-size:10px;color:#64748b;">Le Caissier</p>
                <div style="border-bottom:1px solid #cbd5e1;width:200px;margin-top:40px;"></div>
            </div>
            <div style="display:table-cell;width:50%;text-align:right;">
                <p style="font-size:10px;color:#64748b;">Le Directeur</p>
                <div style="border-bottom:1px solid #cbd5e1;width:200px;margin-top:40px;margin-left:auto;"></div>
            </div>
        </div>
    </div>

    <div class="footer">
        Document généré le {{ now()->format('d/m/Y à H:i') }} &mdash; MediCare Pro &copy; {{ date('Y') }}
    </div>
</body>
</html>
