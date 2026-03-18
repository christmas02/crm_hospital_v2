<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $facture->numero }}</title>
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
            display: table;
            width: 100%;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #2563eb;
        }
        .header-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .header-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            text-align: right;
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
        .facture-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 20px;
            padding: 10px;
            background: #eff6ff;
            border-radius: 4px;
            letter-spacing: 2px;
        }
        .meta-row {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }
        .meta-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .patient-box {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 12px 15px;
            margin-right: 10px;
        }
        .date-box {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 12px 15px;
            margin-left: 10px;
            text-align: right;
        }
        .section-title {
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }
        .patient-box p, .date-box p {
            font-size: 12px;
            margin-bottom: 3px;
        }
        table.lignes {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        table.lignes thead th {
            background: #2563eb;
            color: #fff;
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table.lignes thead th:first-child {
            border-radius: 4px 0 0 0;
        }
        table.lignes thead th:last-child {
            border-radius: 0 4px 0 0;
        }
        table.lignes thead th.right {
            text-align: right;
        }
        table.lignes thead th.center {
            text-align: center;
        }
        table.lignes tbody td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 12px;
        }
        table.lignes tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        table.lignes tbody td.right {
            text-align: right;
        }
        table.lignes tbody td.center {
            text-align: center;
        }
        .total-section {
            text-align: right;
            margin-bottom: 25px;
            padding: 15px;
            background: #f0fdf4;
            border: 2px solid #22c55e;
            border-radius: 6px;
        }
        .total-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .total-amount {
            font-size: 22px;
            font-weight: bold;
            color: #166534;
        }
        .payment-info {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 25px;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-success {
            background: #dcfce7;
            color: #166534;
        }
        .badge-warning {
            background: #fef3c7;
            color: #92400e;
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
        .legal {
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            margin-top: 15px;
            padding: 8px;
            border: 1px dashed #d1d5db;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<div class="page">
    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <h1>MediCare Pro</h1>
            <p>Centre Hospitalier</p>
            <p>Abidjan, Cote d'Ivoire</p>
            <p>Tel: +225 00 00 00 00</p>
        </div>
        <div class="header-right">
            <p style="font-size:10px;color:#999;">N. Facture</p>
            <p style="font-size:16px;font-weight:bold;color:#1e40af;">{{ $facture->numero }}</p>
        </div>
    </div>

    <!-- Title -->
    <div class="facture-title">FACTURE N. {{ $facture->numero }}</div>

    <!-- Patient & Date info -->
    <div class="meta-row">
        <div class="meta-col">
            <div class="patient-box">
                <div class="section-title">Informations Patient</div>
                <p><strong>Nom :</strong> {{ $facture->patient->nom }}</p>
                <p><strong>Prenom :</strong> {{ $facture->patient->prenom }}</p>
                @if($facture->patient->telephone)
                <p><strong>Telephone :</strong> {{ $facture->patient->telephone }}</p>
                @endif
                @if($facture->patient->adresse)
                <p><strong>Adresse :</strong> {{ $facture->patient->adresse }}</p>
                @endif
            </div>
        </div>
        <div class="meta-col">
            <div class="date-box">
                <div class="section-title">Date de Facturation</div>
                <p style="font-size:14px;font-weight:bold;">{{ $facture->date->format('d/m/Y') }}</p>
                <div style="margin-top:10px;">
                    @if($facture->statut == 'payee')
                    <span class="badge badge-success">Payee</span>
                    @else
                    <span class="badge badge-warning">En attente</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Lines Table -->
    <table class="lignes">
        <thead>
            <tr>
                <th style="width:45%;">Description</th>
                <th class="center" style="width:10%;">Qte</th>
                <th class="right" style="width:20%;">Prix unitaire</th>
                <th class="right" style="width:25%;">Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($facture->lignes as $ligne)
            <tr>
                <td>{{ $ligne->description }}</td>
                <td class="center">{{ $ligne->quantite }}</td>
                <td class="right">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} F</td>
                <td class="right" style="font-weight:bold;">{{ number_format($ligne->montant ?? ($ligne->quantite * $ligne->prix_unitaire), 0, ',', ' ') }} F</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Total -->
    <div class="total-section">
        <div class="total-label">Total a payer</div>
        <div class="total-amount">{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</div>
    </div>

    <!-- Payment info -->
    @if($facture->statut == 'payee')
    <div class="payment-info">
        <div class="section-title">Informations de Paiement</div>
        <p><strong>Statut :</strong> <span class="badge badge-success">Payee</span></p>
        @if($facture->mode_paiement)
        <p style="margin-top:5px;"><strong>Mode de paiement :</strong>
            @switch($facture->mode_paiement)
                @case('especes') Especes @break
                @case('carte') Carte bancaire @break
                @case('mobile_money') Mobile Money @break
                @case('cheque') Cheque @break
                @case('virement') Virement @break
                @default {{ $facture->mode_paiement }}
            @endswitch
        </p>
        @endif
        @if($facture->date_paiement)
        <p style="margin-top:5px;"><strong>Date de paiement :</strong> {{ $facture->date_paiement->format('d/m/Y a H:i') }}</p>
        @endif
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div class="footer-row">
            <div class="footer-col">
                <p style="font-size:11px;color:#666;">Fait a Abidjan, le {{ $facture->date->format('d/m/Y') }}</p>
            </div>
            <div class="footer-col right">
                <div class="signature-line">
                    Signature et cachet
                </div>
            </div>
        </div>

        <div class="legal">
            MediCare Pro - Centre Hospitalier | Abidjan, Cote d'Ivoire<br>
            Ce document fait office de facture. Conservez-le pour vos dossiers medicaux et administratifs.
        </div>
    </div>
</div>
</body>
</html>
