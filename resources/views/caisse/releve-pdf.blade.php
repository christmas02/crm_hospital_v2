<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Releve de compte - {{ $patient->prenom }} {{ $patient->nom }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; line-height: 1.5; }
        .page { padding: 30px 40px; }
        .header { display: table; width: 100%; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 3px solid #2563eb; }
        .header-left { display: table-cell; width: 50%; vertical-align: top; }
        .header-right { display: table-cell; width: 50%; vertical-align: top; text-align: right; }
        .header h1 { font-size: 20px; color: #2563eb; margin-bottom: 2px; }
        .header p { font-size: 10px; color: #666; }
        .title { text-align: center; font-size: 16px; font-weight: bold; color: #1e40af; margin-bottom: 20px; padding: 10px; background: #eff6ff; border-radius: 4px; letter-spacing: 2px; }
        .patient-box { border: 1px solid #d1d5db; border-radius: 6px; padding: 12px 15px; margin-bottom: 20px; }
        .section-title { font-size: 10px; text-transform: uppercase; color: #6b7280; margin-bottom: 6px; letter-spacing: 1px; }
        .patient-box p { font-size: 11px; margin-bottom: 2px; }
        .summary { display: table; width: 100%; margin-bottom: 20px; }
        .summary-item { display: table-cell; width: 33.33%; text-align: center; padding: 10px; }
        .summary-item .label { font-size: 9px; text-transform: uppercase; color: #6b7280; letter-spacing: 1px; margin-bottom: 4px; }
        .summary-item .value { font-size: 16px; font-weight: bold; }
        .summary-item .value.blue { color: #2563eb; }
        .summary-item .value.green { color: #16a34a; }
        .summary-item .value.red { color: #dc2626; }
        .section-heading { font-size: 12px; font-weight: bold; color: #1e40af; margin-bottom: 8px; margin-top: 20px; padding-bottom: 4px; border-bottom: 1px solid #e5e7eb; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        thead th { background: #2563eb; color: #fff; padding: 6px 8px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; }
        thead th:first-child { border-radius: 3px 0 0 0; }
        thead th:last-child { border-radius: 0 3px 0 0; }
        thead th.right { text-align: right; }
        tbody td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        tbody tr:nth-child(even) { background: #f9fafb; }
        tbody td.right { text-align: right; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        .footer { margin-top: 30px; border-top: 1px solid #d1d5db; padding-top: 10px; }
        .footer p { font-size: 9px; color: #9ca3af; }
        .legal { text-align: center; font-size: 8px; color: #9ca3af; margin-top: 10px; padding: 6px; border: 1px dashed #d1d5db; border-radius: 4px; }
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
            <p style="font-size:9px;color:#999;">Date d'edition</p>
            <p style="font-size:13px;font-weight:bold;color:#1e40af;">{{ now()->format('d/m/Y') }}</p>
        </div>
    </div>

    <!-- Title -->
    <div class="title">RELEVE DE COMPTE PATIENT</div>

    <!-- Patient Info -->
    <div class="patient-box">
        <div class="section-title">Informations Patient</div>
        <p><strong>Nom :</strong> {{ $patient->nom }}</p>
        <p><strong>Prenom :</strong> {{ $patient->prenom }}</p>
        @if($patient->telephone)
        <p><strong>Telephone :</strong> {{ $patient->telephone }}</p>
        @endif
        @if($patient->adresse)
        <p><strong>Adresse :</strong> {{ $patient->adresse }}</p>
        @endif
    </div>

    <!-- Summary -->
    <div class="summary" style="border:1px solid #d1d5db;border-radius:6px;">
        <div class="summary-item" style="border-right:1px solid #d1d5db;">
            <div class="label">Total facture</div>
            <div class="value blue">{{ number_format($totaux['total_facture'], 0, ',', ' ') }} F</div>
        </div>
        <div class="summary-item" style="border-right:1px solid #d1d5db;">
            <div class="label">Total paye</div>
            <div class="value green">{{ number_format($totaux['total_paye'], 0, ',', ' ') }} F</div>
        </div>
        <div class="summary-item">
            <div class="label">Solde du</div>
            <div class="value red">{{ number_format($totaux['solde_du'], 0, ',', ' ') }} F</div>
        </div>
    </div>

    <!-- Invoices -->
    <div class="section-heading">Factures</div>
    <table>
        <thead>
            <tr>
                <th>N. Facture</th>
                <th>Date</th>
                <th class="right">Montant</th>
                <th class="right">Paye</th>
                <th class="right">Restant</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($factures as $facture)
            @php
                $montantNet = $facture->montant_net ?: $facture->montant;
                $restant = $montantNet - $facture->montant_paye;
            @endphp
            <tr>
                <td style="font-weight:bold;">{{ $facture->numero }}</td>
                <td>{{ $facture->date->format('d/m/Y') }}</td>
                <td class="right">{{ number_format($facture->montant, 0, ',', ' ') }} F</td>
                <td class="right">{{ number_format($facture->montant_paye, 0, ',', ' ') }} F</td>
                <td class="right" style="font-weight:bold;">{{ number_format(max(0, $restant), 0, ',', ' ') }} F</td>
                <td>
                    @if($facture->statut == 'payee')
                    <span class="badge badge-success">Payee</span>
                    @elseif($facture->statut == 'annulee')
                    <span class="badge badge-danger">Annulee</span>
                    @elseif($facture->montant_paye > 0)
                    <span class="badge badge-warning">Partielle</span>
                    @else
                    <span class="badge badge-info">En attente</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;padding:16px;color:#999;">Aucune facture</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Payments -->
    <div class="section-heading">Paiements</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>N. Recu</th>
                <th>Facture</th>
                <th class="right">Montant</th>
                <th>Mode</th>
            </tr>
        </thead>
        <tbody>
            @forelse($paiements as $paiement)
            <tr>
                <td>{{ $paiement->date_paiement ? \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y H:i') : '-' }}</td>
                <td style="font-weight:bold;">{{ $paiement->numero_recu ?? '-' }}</td>
                <td>{{ $paiement->facture->numero ?? '-' }}</td>
                <td class="right" style="font-weight:bold;">{{ number_format($paiement->montant, 0, ',', ' ') }} F</td>
                <td>
                    @switch($paiement->mode_paiement)
                        @case('especes') Especes @break
                        @case('mobile_money') Mobile Money @break
                        @case('carte') Carte bancaire @break
                        @case('cheque') Cheque @break
                        @case('virement') Virement @break
                        @default {{ $paiement->mode_paiement }}
                    @endswitch
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;padding:16px;color:#999;">Aucun paiement</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Edite le {{ now()->format('d/m/Y') }} a {{ now()->format('H:i') }}</p>
        <div class="legal">
            MediCare Pro - Centre Hospitalier | Abidjan, Cote d'Ivoire<br>
            Ce document est un releve de compte patient. Il recapitule l'ensemble des factures et paiements enregistres.
        </div>
    </div>
</div>
</body>
</html>
