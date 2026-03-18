<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Mensuel - {{ $mois }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1a1a2e; line-height: 1.5; }
        .page { padding: 40px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #0f3460; padding-bottom: 15px; }
        .header h1 { font-size: 22px; color: #0f3460; margin-bottom: 4px; }
        .header h2 { font-size: 14px; color: #555; font-weight: normal; }
        .period { text-align: center; font-size: 16px; color: #0f3460; margin-bottom: 25px; font-weight: bold; }

        .section-title { font-size: 14px; color: #0f3460; border-bottom: 2px solid #e0e0e0; padding-bottom: 5px; margin: 25px 0 12px 0; font-weight: bold; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #0f3460; color: #fff; padding: 8px 10px; text-align: left; font-size: 11px; text-transform: uppercase; }
        td { padding: 7px 10px; border-bottom: 1px solid #e0e0e0; font-size: 11px; }
        tr:nth-child(even) td { background-color: #f8f9fa; }

        .kpi-grid { display: table; width: 100%; margin-bottom: 20px; }
        .kpi-row { display: table-row; }
        .kpi-cell { display: table-cell; width: 25%; padding: 8px; text-align: center; }
        .kpi-box { border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px 8px; }
        .kpi-label { font-size: 10px; color: #777; text-transform: uppercase; margin-bottom: 4px; }
        .kpi-value { font-size: 18px; font-weight: bold; color: #0f3460; }
        .kpi-value.green { color: #27ae60; }
        .kpi-value.red { color: #e74c3c; }

        .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #ccc; text-align: center; font-size: 10px; color: #999; }
    </style>
</head>
<body>
<div class="page">

    <div class="header">
        <h1>MediCare Pro &mdash; Rapport Mensuel</h1>
        <h2>Tableau de bord de gestion hospitali&egrave;re</h2>
    </div>

    <div class="period">Mois de {{ $mois }}</div>

    {{-- KPIs --}}
    <div class="section-title">Indicateurs cl&eacute;s</div>
    <table>
        <thead>
            <tr>
                <th>Indicateur</th>
                <th style="text-align:right;">Valeur</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Nouveaux patients inscrits</td>
                <td style="text-align:right; font-weight:bold;">{{ $patients_nouveaux }}</td>
            </tr>
            <tr>
                <td>Total patients (cumul)</td>
                <td style="text-align:right; font-weight:bold;">{{ $patients_total }}</td>
            </tr>
            <tr>
                <td>Consultations</td>
                <td style="text-align:right; font-weight:bold;">{{ $consultations }}</td>
            </tr>
            <tr>
                <td>Consultations termin&eacute;es</td>
                <td style="text-align:right; font-weight:bold;">{{ $consultations_terminees }}</td>
            </tr>
            <tr>
                <td>Recettes</td>
                <td style="text-align:right; font-weight:bold; color:#27ae60;">{{ number_format($recettes, 0, ',', ' ') }} F</td>
            </tr>
            <tr>
                <td>D&eacute;penses</td>
                <td style="text-align:right; font-weight:bold; color:#e74c3c;">{{ number_format($depenses, 0, ',', ' ') }} F</td>
            </tr>
            <tr>
                <td>Solde (Recettes - D&eacute;penses)</td>
                <td style="text-align:right; font-weight:bold; color:{{ $recettes - $depenses >= 0 ? '#27ae60' : '#e74c3c' }};">{{ number_format($recettes - $depenses, 0, ',', ' ') }} F</td>
            </tr>
            <tr>
                <td>Hospitalisations</td>
                <td style="text-align:right; font-weight:bold;">{{ $hospitalisations }}</td>
            </tr>
            <tr>
                <td>Ordonnances &eacute;mises</td>
                <td style="text-align:right; font-weight:bold;">{{ $ordonnances }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Top Médecins --}}
    <div class="section-title">Top 5 M&eacute;decins (par consultations)</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>M&eacute;decin</th>
                <th>Sp&eacute;cialit&eacute;</th>
                <th style="text-align:right;">Consultations</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($top_medecins as $i => $medecin)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>Dr. {{ $medecin->prenom }} {{ $medecin->nom }}</td>
                <td>{{ $medecin->specialite }}</td>
                <td style="text-align:right; font-weight:bold;">{{ $medecin->consultations_count }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center; color:#999;">Aucune donn&eacute;e disponible</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Top Médicaments --}}
    <div class="section-title">Top 5 M&eacute;dicaments dispens&eacute;s</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>M&eacute;dicament</th>
                <th style="text-align:right;">Mouvements (sorties)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($top_medicaments as $i => $med)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $med->nom }}</td>
                <td style="text-align:right; font-weight:bold;">{{ $med->mouvements_count }}</td>
            </tr>
            @empty
            <tr><td colspan="3" style="text-align:center; color:#999;">Aucune donn&eacute;e disponible</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Rapport g&eacute;n&eacute;r&eacute; automatiquement le {{ now()->locale('fr')->isoFormat('D MMMM YYYY [&agrave;] HH:mm') }} &mdash; MediCare Pro
    </div>

</div>
</body>
</html>
