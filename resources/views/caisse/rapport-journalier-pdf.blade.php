<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; margin: 0; }
        .header { text-align: center; border-bottom: 3px solid #0891b2; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { font-size: 20px; color: #0891b2; margin: 0 0 3px; }
        .header p { color: #64748b; font-size: 10px; margin: 0; }
        .title { text-align: center; font-size: 16px; font-weight: bold; margin: 20px 0 5px; text-transform: uppercase; letter-spacing: 1px; }
        .date-rapport { text-align: center; color: #64748b; font-size: 12px; margin-bottom: 20px; }
        .section-title { font-size: 13px; font-weight: bold; color: #0e7490; margin: 25px 0 10px; padding-bottom: 5px; border-bottom: 2px solid #0e7490; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Summary box */
        .summary { display: table; width: 100%; margin-bottom: 20px; }
        .summary-item { display: table-cell; width: 33.33%; text-align: center; padding: 12px; border: 1px solid #e2e8f0; }
        .summary-label { font-size: 9px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .summary-value { font-size: 18px; font-weight: bold; margin-top: 4px; }
        .green { color: #059669; }
        .red { color: #dc2626; }
        .blue { color: #0891b2; }

        /* Session box */
        .session-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px 16px; margin-bottom: 20px; }
        .session-box .label { font-size: 9px; color: #64748b; text-transform: uppercase; }
        .session-box .value { font-weight: 700; font-size: 12px; }
        .session-grid { display: table; width: 100%; }
        .session-cell { display: table-cell; width: 25%; padding: 6px 8px; }

        /* Tables */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #0e7490; color: #fff; padding: 8px 10px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
        tr:nth-child(even) td { background: #f8fafc; }
        tfoot td { background: #f1f5f9 !important; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }

        /* Mode colors */
        .mode-especes { background: #dcfce7; color: #059669; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: 600; }
        .mode-carte { background: #ede9fe; color: #5b21b6; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: 600; }
        .mode-mobile_money { background: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: 600; }
        .mode-cheque { background: #fce7f3; color: #9d174d; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: 600; }
        .mode-virement { background: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: 600; }

        /* Progress bar for ventilation */
        .bar-bg { background: #e2e8f0; height: 8px; border-radius: 4px; width: 100%; }
        .bar-fill { height: 8px; border-radius: 4px; }
        .bar-especes { background: #059669; }
        .bar-carte { background: #7c3aed; }
        .bar-mobile_money { background: #d97706; }
        .bar-cheque { background: #db2777; }
        .bar-virement { background: #64748b; }

        .footer { text-align: center; color: #94a3b8; font-size: 9px; margin-top: 30px; padding-top: 15px; border-top: 1px solid #e2e8f0; }
        .signatures { margin-top: 50px; display: table; width: 100%; }
        .sig-cell { display: table-cell; width: 50%; }
        .sig-line { border-bottom: 1px solid #cbd5e1; width: 200px; margin-top: 50px; }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h1>MediCare Pro</h1>
        <p>Centre Hospitalier</p>
    </div>

    <div class="title">Rapport de cl&ocirc;ture journali&egrave;re</div>
    <div class="date-rapport">Date : {{ $dateCarbon->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</div>

    {{-- Session info --}}
    @if($session)
    <div class="session-box">
        <div style="font-weight:700;margin-bottom:8px;font-size:11px;color:#0e7490;">INFORMATIONS DE SESSION</div>
        <div class="session-grid">
            <div class="session-cell">
                <div class="label">Ouverture</div>
                <div class="value">{{ $session->ouverture ? \Carbon\Carbon::parse($session->ouverture)->format('H:i') : '-' }}</div>
            </div>
            <div class="session-cell">
                <div class="label">Fermeture</div>
                <div class="value">{{ $session->fermeture ? \Carbon\Carbon::parse($session->fermeture)->format('H:i') : 'En cours' }}</div>
            </div>
            <div class="session-cell">
                <div class="label">Solde ouverture</div>
                <div class="value">{{ number_format($session->solde_ouverture ?? 0, 0, ',', ' ') }} F</div>
            </div>
            <div class="session-cell">
                <div class="label">Solde fermeture</div>
                <div class="value">{{ $session->solde_fermeture !== null ? number_format($session->solde_fermeture, 0, ',', ' ') . ' F' : '-' }}</div>
            </div>
        </div>
        @if($session->solde_fermeture !== null)
        @php
            $soldeAttendu = ($session->solde_ouverture ?? 0) + $totaux['encaissements'] - $totaux['depenses'];
            $ecart = $session->solde_fermeture - $soldeAttendu;
        @endphp
        <div style="margin-top:8px;padding-top:8px;border-top:1px solid #e2e8f0;">
            <span class="label">Solde attendu : </span><span class="value">{{ number_format($soldeAttendu, 0, ',', ' ') }} F</span>
            &nbsp;&nbsp;&mdash;&nbsp;&nbsp;
            <span class="label">&Eacute;cart : </span><span class="value" style="color:{{ $ecart == 0 ? '#059669' : '#dc2626' }};">{{ number_format($ecart, 0, ',', ' ') }} F</span>
        </div>
        @endif
    </div>
    @endif

    {{-- Summary --}}
    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">Total encaissements ({{ $totaux['nb_paiements'] }})</div>
            <div class="summary-value green">{{ number_format($totaux['encaissements'], 0, ',', ' ') }} F</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Total d&eacute;penses ({{ $totaux['nb_depenses'] }})</div>
            <div class="summary-value red">{{ number_format($totaux['depenses'], 0, ',', ' ') }} F</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Solde net</div>
            <div class="summary-value {{ $totaux['solde'] >= 0 ? 'green' : 'red' }}">{{ number_format($totaux['solde'], 0, ',', ' ') }} F</div>
        </div>
    </div>

    {{-- Ventilation par mode de paiement --}}
    <div class="section-title">Ventilation par mode de paiement</div>
    <table>
        <thead>
            <tr>
                <th style="width:30%;">Mode</th>
                <th class="text-center" style="width:15%;">Nombre</th>
                <th class="text-right" style="width:25%;">Montant</th>
                <th class="text-right" style="width:15%;">% du total</th>
                <th style="width:15%;"></th>
            </tr>
        </thead>
        <tbody>
            @php
                $modes = ['especes' => 'Esp&egrave;ces', 'carte' => 'Carte bancaire', 'mobile_money' => 'Mobile Money', 'cheque' => 'Ch&egrave;que', 'virement' => 'Virement'];
                $totalEnc = $totaux['encaissements'] ?: 1;
            @endphp
            @foreach(['especes', 'carte', 'mobile_money', 'cheque', 'virement'] as $modeKey)
            @php
                $data = $parMode->get($modeKey, ['count' => 0, 'total' => 0]);
                $pct = round(($data['total'] / $totalEnc) * 100);
            @endphp
            <tr>
                <td><span class="mode-{{ $modeKey }}">{!! $modes[$modeKey] !!}</span></td>
                <td class="text-center">{{ $data['count'] }}</td>
                <td class="text-right bold">{{ number_format($data['total'], 0, ',', ' ') }} F</td>
                <td class="text-right">{{ $pct }}%</td>
                <td>
                    <div class="bar-bg"><div class="bar-fill bar-{{ $modeKey }}" style="width:{{ $pct }}%;"></div></div>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td class="bold">TOTAL</td>
                <td class="text-center bold">{{ $totaux['nb_paiements'] }}</td>
                <td class="text-right bold">{{ number_format($totaux['encaissements'], 0, ',', ' ') }} F</td>
                <td class="text-right bold">100%</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    {{-- Détail des encaissements --}}
    <div class="section-title">D&eacute;tail des encaissements</div>
    @if($paiements->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width:10%;">Heure</th>
                <th style="width:12%;">N&deg; Re&ccedil;u</th>
                <th style="width:20%;">Patient</th>
                <th style="width:12%;">Facture</th>
                <th style="width:14%;">Mode</th>
                <th class="text-right" style="width:15%;">Montant</th>
                <th style="width:17%;">Caissier</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paiements as $p)
            <tr>
                <td>{{ \Carbon\Carbon::parse($p->date_paiement)->format('H:i') }}</td>
                <td>{{ $p->numero_recu ?? '-' }}</td>
                <td>{{ ($p->patient->prenom ?? '') . ' ' . ($p->patient->nom ?? '') }}</td>
                <td>{{ $p->facture->numero ?? '-' }}</td>
                <td><span class="mode-{{ $p->mode_paiement ?? 'especes' }}">{{ $modes[$p->mode_paiement ?? ''] ?? ucfirst($p->mode_paiement ?? '-') }}</span></td>
                <td class="text-right bold green">{{ number_format($p->montant, 0, ',', ' ') }} F</td>
                <td>{{ ($p->encaisseur->name ?? $p->encaisseur->nom ?? '-') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="bold">TOTAL ENCAISSEMENTS</td>
                <td class="text-right bold green">{{ number_format($totaux['encaissements'], 0, ',', ' ') }} F</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    @else
    <p style="text-align:center;color:#94a3b8;padding:15px 0;">Aucun encaissement pour cette journ&eacute;e</p>
    @endif

    {{-- Détail des dépenses --}}
    <div class="section-title">D&eacute;tail des d&eacute;penses</div>
    @if($depenses->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width:12%;">Heure</th>
                <th style="width:40%;">Description</th>
                <th style="width:20%;">Cat&eacute;gorie</th>
                <th class="text-right" style="width:28%;">Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($depenses as $d)
            <tr>
                <td>{{ $d->created_at ? $d->created_at->format('H:i') : '-' }}</td>
                <td>{{ $d->description }}</td>
                <td>{{ ucfirst($d->categorie ?? 'autre') }}</td>
                <td class="text-right bold red">{{ number_format($d->montant, 0, ',', ' ') }} F</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="bold">TOTAL D&Eacute;PENSES</td>
                <td class="text-right bold red">{{ number_format($totaux['depenses'], 0, ',', ' ') }} F</td>
            </tr>
        </tfoot>
    </table>
    @else
    <p style="text-align:center;color:#94a3b8;padding:15px 0;">Aucune d&eacute;pense pour cette journ&eacute;e</p>
    @endif

    {{-- Signatures --}}
    <div class="signatures">
        <div class="sig-cell">
            <p style="font-size:10px;color:#64748b;">Le Caissier</p>
            <div class="sig-line"></div>
        </div>
        <div class="sig-cell" style="text-align:right;">
            <p style="font-size:10px;color:#64748b;">Le Responsable</p>
            <div class="sig-line" style="margin-left:auto;"></div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        Document g&eacute;n&eacute;r&eacute; le {{ now()->format('d/m/Y &\agrave; H:i') }} &mdash; MediCare Pro &copy; {{ date('Y') }}
    </div>
</body>
</html>
