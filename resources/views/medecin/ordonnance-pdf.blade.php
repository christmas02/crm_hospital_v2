<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ordonnance {{ $ordonnance->numero_retrait }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        .page {
            padding: 30px 40px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header h1 {
            font-size: 22px;
            color: #2563eb;
            margin-bottom: 2px;
        }
        .header p {
            font-size: 11px;
            color: #666;
        }
        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 20px;
            padding: 8px;
            background: #eff6ff;
            border-radius: 4px;
            letter-spacing: 2px;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .info-box {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .info-box-inner {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 12px 15px;
            margin-right: 10px;
        }
        .info-box:last-child .info-box-inner {
            margin-right: 0;
            margin-left: 10px;
        }
        .info-box h3 {
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }
        .info-box p {
            font-size: 12px;
            margin-bottom: 3px;
        }
        .info-box strong {
            color: #111;
        }
        table.medications {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.medications thead th {
            background: #2563eb;
            color: #fff;
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table.medications thead th:first-child {
            border-radius: 4px 0 0 0;
        }
        table.medications thead th:last-child {
            border-radius: 0 4px 0 0;
            text-align: center;
        }
        table.medications tbody td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 12px;
        }
        table.medications tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        table.medications tbody td:last-child {
            text-align: center;
            font-weight: bold;
        }
        .recommandations {
            border: 1px solid #fbbf24;
            background: #fffbeb;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 25px;
        }
        .recommandations h3 {
            font-size: 11px;
            text-transform: uppercase;
            color: #92400e;
            margin-bottom: 6px;
            letter-spacing: 1px;
        }
        .recommandations p {
            font-size: 12px;
            color: #78350f;
        }
        .footer {
            margin-top: 40px;
            border-top: 1px solid #d1d5db;
            padding-top: 15px;
        }
        .footer-row {
            display: table;
            width: 100%;
        }
        .footer-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .footer-col.right {
            text-align: right;
        }
        .signature-line {
            margin-top: 40px;
            border-top: 1px solid #333;
            width: 200px;
            margin-left: auto;
            padding-top: 5px;
            text-align: center;
            font-size: 11px;
            color: #666;
        }
        .warning {
            text-align: center;
            font-size: 10px;
            color: #dc2626;
            font-style: italic;
            margin-top: 15px;
            padding: 6px;
            border: 1px dashed #fca5a5;
            border-radius: 4px;
        }
        .numero {
            font-size: 11px;
            color: #6b7280;
            text-align: right;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="page">
    <!-- Header -->
    <div class="header">
        <h1>MediCare Pro</h1>
        <p>Centre Hospitalier</p>
        <p>Abidjan, Cote d'Ivoire | Tel: +225 00 00 00 00</p>
    </div>

    <!-- Title -->
    <div class="title">ORDONNANCE MEDICALE</div>

    <!-- Numero -->
    <div class="numero">N. {{ $ordonnance->numero_retrait }}</div>

    <!-- Patient & Doctor info -->
    <div class="info-row">
        <div class="info-box">
            <div class="info-box-inner">
                <h3>Informations Patient</h3>
                <p><strong>Nom :</strong> {{ $ordonnance->patient->nom }}</p>
                <p><strong>Prenom :</strong> {{ $ordonnance->patient->prenom }}</p>
                @if($ordonnance->patient->date_naissance)
                <p><strong>Age :</strong> {{ \Carbon\Carbon::parse($ordonnance->patient->date_naissance)->age }} ans</p>
                @endif
                @if($ordonnance->patient->sexe)
                <p><strong>Sexe :</strong> {{ $ordonnance->patient->sexe == 'M' ? 'Masculin' : 'Feminin' }}</p>
                @endif
                <p><strong>Date :</strong> {{ $ordonnance->date->format('d/m/Y') }}</p>
            </div>
        </div>
        <div class="info-box">
            <div class="info-box-inner">
                <h3>Medecin Prescripteur</h3>
                <p><strong>Dr. {{ $ordonnance->medecin->prenom }} {{ $ordonnance->medecin->nom }}</strong></p>
                @if($ordonnance->medecin->specialite)
                <p>{{ $ordonnance->medecin->specialite }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Medications Table -->
    <table class="medications">
        <thead>
            <tr>
                <th style="width:35%;">Medicament</th>
                <th style="width:25%;">Posologie</th>
                <th style="width:20%;">Duree</th>
                <th style="width:20%;">Quantite</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ordonnance->medicaments as $index => $med)
            <tr>
                <td><strong>{{ $index + 1 }}.</strong> {{ $med->nom }}</td>
                <td>{{ $med->posologie }}</td>
                <td>{{ $med->duree }}</td>
                <td>{{ $med->quantite }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Recommandations -->
    @if($ordonnance->recommandations)
    <div class="recommandations">
        <h3>Recommandations</h3>
        <p>{{ $ordonnance->recommandations }}</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div class="footer-row">
            <div class="footer-col">
                <p style="font-size:11px;color:#666;">Fait a Abidjan, le {{ $ordonnance->date->format('d/m/Y') }}</p>
            </div>
            <div class="footer-col right">
                <div class="signature-line">
                    Signature du Medecin
                </div>
            </div>
        </div>

        <div class="warning">
            Ne pas depasser la dose prescrite. En cas d'effets indesirables, consultez votre medecin.
        </div>
    </div>
</div>
</body>
</html>
