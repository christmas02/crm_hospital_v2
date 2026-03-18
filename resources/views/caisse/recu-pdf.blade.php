<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu {{ $paiement->numero_recu }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        .page {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 30px 40px;
        }

        /* Header */
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .header h1 {
            font-size: 28px;
            color: #2563eb;
            margin-bottom: 4px;
            letter-spacing: 2px;
        }
        .header .subtitle {
            font-size: 11px;
            color: #666;
            margin-bottom: 2px;
        }

        /* Receipt title */
        .receipt-title {
            text-align: center;
            margin-bottom: 25px;
        }
        .receipt-title h2 {
            font-size: 20px;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 3px;
            border: 2px solid #2563eb;
            display: inline-block;
            padding: 8px 30px;
        }

        /* Info row */
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .info-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .info-col.right {
            text-align: right;
        }

        .info-block {
            margin-bottom: 15px;
        }
        .info-block .label {
            font-size: 10px;
            text-transform: uppercase;
            color: #888;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }
        .info-block .value {
            font-size: 13px;
            font-weight: bold;
            color: #222;
        }

        /* Receipt number box */
        .receipt-number {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 10px 15px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 5px;
        }
        .receipt-number .label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }
        .receipt-number .number {
            font-size: 16px;
            font-weight: bold;
            color: #2563eb;
        }

        /* Payment details table */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .details-table th {
            background: #2563eb;
            color: #fff;
            padding: 10px 15px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .details-table td {
            padding: 10px 15px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 12px;
        }
        .details-table tr:last-child td {
            border-bottom: none;
        }
        .details-table .right {
            text-align: right;
        }

        /* Amount box */
        .amount-box {
            background: #f0fdf4;
            border: 2px solid #22c55e;
            border-radius: 6px;
            padding: 15px 25px;
            text-align: center;
            margin: 25px 0;
        }
        .amount-box .label {
            font-size: 11px;
            text-transform: uppercase;
            color: #666;
            letter-spacing: 1px;
        }
        .amount-box .amount {
            font-size: 28px;
            font-weight: bold;
            color: #16a34a;
        }
        .amount-box .currency {
            font-size: 16px;
        }

        /* Payment mode badge */
        .mode-badge {
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Invoice reference */
        .invoice-ref {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            padding: 12px 15px;
            margin: 15px 0;
        }
        .invoice-ref .title {
            font-size: 10px;
            text-transform: uppercase;
            color: #888;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        /* Invoice lines */
        .lines-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        .lines-table th {
            background: #f3f4f6;
            padding: 6px 10px;
            font-size: 10px;
            text-transform: uppercase;
            color: #666;
            text-align: left;
            border-bottom: 1px solid #d1d5db;
        }
        .lines-table td {
            padding: 5px 10px;
            font-size: 11px;
            border-bottom: 1px solid #f3f4f6;
        }
        .lines-table .right {
            text-align: right;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 30px;
        }
        .signature-box {
            display: table-cell;
            width: 45%;
            text-align: center;
        }
        .signature-box .line {
            border-top: 1px solid #999;
            margin-top: 50px;
            padding-top: 5px;
            font-size: 11px;
            color: #666;
        }
        .footer-note {
            text-align: center;
            font-size: 10px;
            color: #999;
            margin-top: 30px;
            font-style: italic;
        }

        /* Stamp */
        .stamp {
            text-align: center;
            margin: 15px 0;
        }
        .stamp .paid {
            display: inline-block;
            border: 3px solid #22c55e;
            color: #22c55e;
            padding: 5px 20px;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            transform: rotate(-5deg);
            letter-spacing: 3px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <h1>MediCare Pro</h1>
            <div class="subtitle">Centre Hospitalier - Soins de qualite</div>
            <div class="subtitle">Tel: +225 00 00 00 00 | Email: contact@medicare-pro.com</div>
        </div>

        <!-- Receipt Title -->
        <div class="receipt-title">
            <h2>Recu de Paiement</h2>
        </div>

        <!-- Receipt Info -->
        <div class="info-row">
            <div class="info-col">
                <div class="receipt-number">
                    <div class="label">No. Recu</div>
                    <div class="number">{{ $paiement->numero_recu }}</div>
                </div>
            </div>
            <div class="info-col right">
                <div class="info-block">
                    <div class="label">Date de paiement</div>
                    <div class="value">{{ $paiement->date_paiement->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Patient Info -->
        <div class="info-row">
            <div class="info-col">
                <div class="info-block">
                    <div class="label">Patient</div>
                    <div class="value">{{ $paiement->patient->prenom }} {{ $paiement->patient->nom }}</div>
                </div>
                @if($paiement->patient->telephone)
                <div class="info-block">
                    <div class="label">Telephone</div>
                    <div class="value">{{ $paiement->patient->telephone }}</div>
                </div>
                @endif
            </div>
            <div class="info-col right">
                <div class="info-block">
                    <div class="label">Mode de paiement</div>
                    <div class="value">
                        <span class="mode-badge">
                            @switch($paiement->mode_paiement)
                                @case('especes') Especes @break
                                @case('carte') Carte bancaire @break
                                @case('mobile_money') Mobile Money @break
                                @case('cheque') Cheque @break
                                @case('virement') Virement @break
                                @default {{ $paiement->mode_paiement }}
                            @endswitch
                        </span>
                    </div>
                </div>
                @if($paiement->reference)
                <div class="info-block">
                    <div class="label">Reference</div>
                    <div class="value">{{ $paiement->reference }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Amount -->
        <div class="amount-box">
            <div class="label">Montant paye</div>
            <div class="amount">{{ number_format($paiement->montant, 0, ',', ' ') }} <span class="currency">F CFA</span></div>
        </div>

        <div class="stamp">
            <span class="paid">Paye</span>
        </div>

        <!-- Invoice Reference -->
        @if($paiement->facture)
        <div class="invoice-ref">
            <div class="title">Reference facture : {{ $paiement->facture->numero }}</div>
            @if($paiement->facture->lignes && $paiement->facture->lignes->count() > 0)
            <table class="lines-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="right">Qte</th>
                        <th class="right">P.U.</th>
                        <th class="right">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paiement->facture->lignes as $ligne)
                    <tr>
                        <td>{{ $ligne->description }}</td>
                        <td class="right">{{ $ligne->quantite }}</td>
                        <td class="right">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} F</td>
                        <td class="right">{{ number_format($ligne->montant, 0, ',', ' ') }} F</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
            <div style="margin-top: 8px; font-size: 11px;">
                <strong>Total facture :</strong> {{ number_format($paiement->facture->montant, 0, ',', ' ') }} F CFA
                @if($paiement->facture->montant_paye > 0)
                    | <strong>Total paye :</strong> {{ number_format($paiement->facture->montant_paye, 0, ',', ' ') }} F CFA
                @endif
                @if(($paiement->facture->montant_net ?: $paiement->facture->montant) - $paiement->facture->montant_paye > 0)
                    | <strong>Reste a payer :</strong> {{ number_format(($paiement->facture->montant_net ?: $paiement->facture->montant) - $paiement->facture->montant_paye, 0, ',', ' ') }} F CFA
                @endif
            </div>
        </div>
        @endif

        @if($paiement->notes)
        <div class="info-block" style="margin-top: 10px;">
            <div class="label">Notes</div>
            <div style="font-size: 11px; color: #555;">{{ $paiement->notes }}</div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div class="signature-section">
                <div class="signature-box">
                    <div class="line">Le Caissier</div>
                    @if($paiement->encaisseur)
                    <div style="font-size: 11px; margin-top: 5px; color: #444;">
                        {{ $paiement->encaisseur->name }}
                    </div>
                    @endif
                </div>
                <div class="signature-box" style="width: 10%;"></div>
                <div class="signature-box">
                    <div class="line">Le Patient / Accompagnant</div>
                </div>
            </div>

            <div class="footer-note">
                Ce recu est un document officiel de MediCare Pro. Conservez-le precieusement.<br>
                Edite le {{ now()->format('d/m/Y') }} a {{ now()->format('H:i') }}
            </div>
        </div>
    </div>
</body>
</html>
