<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Resultats - {{ $demande->numero }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a1a; padding: 30px; }
        .header { text-align: center; border-bottom: 3px solid #0891b2; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { font-size: 20px; color: #0891b2; margin-bottom: 4px; }
        .header p { font-size: 10px; color: #666; }
        .title { text-align: center; font-size: 16px; font-weight: bold; color: #1a1a1a; margin: 20px 0; padding: 8px; background: #f0fdfa; border: 1px solid #99f6e4; }
        .info-grid { display: table; width: 100%; margin-bottom: 20px; }
        .info-row { display: table-row; }
        .info-cell { display: table-cell; padding: 4px 8px; width: 50%; font-size: 11px; }
        .info-cell strong { color: #374151; }
        .numero-box { text-align: right; font-size: 13px; font-weight: bold; color: #0891b2; margin-bottom: 10px; }
        table.results { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table.results th { background: #0891b2; color: #fff; padding: 8px 10px; font-size: 10px; text-transform: uppercase; text-align: left; }
        table.results td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px; }
        table.results tr:nth-child(even) { background: #f9fafb; }
        .interp-normal { color: #16a34a; font-weight: bold; }
        .interp-bas { color: #2563eb; font-weight: bold; }
        .interp-eleve { color: #d97706; font-weight: bold; }
        .interp-critique { color: #dc2626; font-weight: bold; background: #fee2e2; padding: 2px 6px; }
        .footer { margin-top: 40px; border-top: 1px solid #e5e7eb; padding-top: 20px; }
        .signature-line { margin-top: 50px; text-align: right; }
        .signature-line .line { border-top: 1px solid #374151; width: 200px; display: inline-block; margin-top: 40px; }
        .signature-line p { font-size: 10px; color: #666; margin-top: 4px; }
        .categorie-header { background: #f1f5f9; font-weight: bold; color: #475569; }
    </style>
</head>
<body>
    <div class="header">
        <h1>MediCare Pro</h1>
        <p>Laboratoire d'analyses medicales</p>
        <p>Tel: +XXX XXX XXX | Email: labo@medicare-pro.com</p>
    </div>

    <div class="numero-box">{{ $demande->numero }}</div>

    <div class="title">RESULTATS D'ANALYSES</div>

    <div class="info-grid">
        <div class="info-row">
            <div class="info-cell"><strong>Patient:</strong> {{ $demande->patient->prenom }} {{ $demande->patient->nom }}</div>
            <div class="info-cell"><strong>Date de naissance:</strong> {{ $demande->patient->date_naissance?->format('d/m/Y') ?? '-' }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell"><strong>Medecin prescripteur:</strong> Dr. {{ $demande->medecin->prenom }} {{ $demande->medecin->nom }}</div>
            <div class="info-cell"><strong>Date de demande:</strong> {{ $demande->date_demande->format('d/m/Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell"><strong>Date des resultats:</strong> {{ $demande->date_resultat?->format('d/m/Y') ?? '-' }}</div>
            <div class="info-cell"><strong>Urgence:</strong> {{ $demande->urgence === 'tres_urgent' ? 'Tres urgent' : ucfirst($demande->urgence) }}</div>
        </div>
    </div>

    @if($demande->notes_cliniques)
    <div style="background:#f9fafb;border:1px solid #e5e7eb;padding:8px 12px;border-radius:4px;margin-bottom:15px;font-size:10px;">
        <strong>Notes cliniques:</strong> {{ $demande->notes_cliniques }}
    </div>
    @endif

    @php $grouped = $demande->resultats->groupBy(fn($r) => $r->examen->categorie); @endphp

    <table class="results">
        <thead>
            <tr>
                <th>Examen</th>
                <th>Resultat</th>
                <th>Unite</th>
                <th>Valeur de reference</th>
                <th>Interpretation</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grouped as $categorie => $resultats)
                <tr class="categorie-header">
                    <td colspan="5">{{ $categorie }}</td>
                </tr>
                @foreach($resultats as $resultat)
                <tr>
                    <td>{{ $resultat->examen->nom }}</td>
                    <td><strong>{{ $resultat->valeur ?? '-' }}</strong></td>
                    <td>{{ $resultat->unite ?? '-' }}</td>
                    <td>{{ $resultat->valeur_reference ?? '-' }}</td>
                    <td>
                        @if($resultat->interpretation)
                            <span class="interp-{{ $resultat->interpretation }}">
                                {{ $resultat->interpretation === 'eleve' ? 'Eleve' : ucfirst($resultat->interpretation) }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @if($resultat->commentaire)
                <tr>
                    <td colspan="5" style="font-size:10px;color:#666;padding-left:20px;font-style:italic;">{{ $resultat->commentaire }}</td>
                </tr>
                @endif
                @endforeach
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-line">
            <p><strong>Le biologiste</strong></p>
            @if($demande->realisePar)
                <p>{{ $demande->realisePar->name }}</p>
            @endif
            <div class="line"></div>
            <p>Signature et cachet</p>
        </div>
    </div>

    <div style="position:fixed;bottom:20px;left:30px;right:30px;text-align:center;font-size:8px;color:#999;border-top:1px solid #e5e7eb;padding-top:8px;">
        MediCare Pro - Laboratoire d'analyses | Document genere le {{ now()->format('d/m/Y a H:i') }} | {{ $demande->numero }}
    </div>
</body>
</html>
