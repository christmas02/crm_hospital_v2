<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Carnet de Sante - {{ $patient->prenom }} {{ $patient->nom }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; line-height: 1.5; }
        .page { page-break-after: always; padding: 40px; }
        .page:last-child { page-break-after: avoid; }

        /* Cover page */
        .cover { text-align: center; padding-top: 120px; }
        .cover-logo { width: 80px; height: 80px; background: #0891b2; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 30px; }
        .cover-title { font-size: 28px; font-weight: 800; color: #059669; margin-bottom: 8px; letter-spacing: -0.5px; }
        .cover-subtitle { font-size: 14px; color: #6b7280; margin-bottom: 60px; }
        .cover-patient { background: #f3f4f6; border-radius: 12px; padding: 30px; display: inline-block; text-align: left; min-width: 400px; }
        .cover-avatar { width: 70px; height: 70px; border-radius: 16px; background: #0891b2; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 800; margin-bottom: 16px; }
        .cover-name { font-size: 22px; font-weight: 700; margin-bottom: 4px; }
        .cover-info { font-size: 12px; color: #6b7280; margin-bottom: 3px; }

        /* Headers */
        .section-title { font-size: 16px; font-weight: 700; color: #059669; border-bottom: 2px solid #059669; padding-bottom: 6px; margin-bottom: 16px; }

        /* Tables */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #f3f4f6; color: #374151; font-weight: 600; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px 10px; text-align: left; border-bottom: 2px solid #d1d5db; }
        td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; font-size: 10.5px; }
        tr:nth-child(even) td { background: #f9fafb; }

        /* Info grid */
        .info-grid { display: table; width: 100%; margin-bottom: 16px; }
        .info-row { display: table-row; }
        .info-label { display: table-cell; width: 140px; padding: 5px 10px; font-size: 10px; color: #6b7280; text-transform: uppercase; font-weight: 600; letter-spacing: 0.3px; }
        .info-value { display: table-cell; padding: 5px 10px; font-weight: 500; }

        /* Tags */
        .tag { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 9px; font-weight: 600; margin: 1px 2px; }
        .tag-red { background: #fef2f2; color: #dc2626; }
        .tag-yellow { background: #fefce8; color: #a16207; }
        .tag-purple { background: #f5f3ff; color: #7c3aed; }
        .tag-green { background: #f0fdf4; color: #16a34a; }
        .tag-blue { background: #eff6ff; color: #2563eb; }

        /* Badge */
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: 600; }
        .badge-success { background: #dcfce7; color: #16a34a; }
        .badge-warning { background: #fef9c3; color: #a16207; }
        .badge-danger { background: #fef2f2; color: #dc2626; }
        .badge-info { background: #e0f2fe; color: #0284c7; }

        /* Footer */
        .footer { position: fixed; bottom: 20px; left: 40px; right: 40px; text-align: center; font-size: 9px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 8px; }

        .overdue { color: #dc2626; font-weight: 700; }
        .two-col { display: table; width: 100%; }
        .two-col > div { display: table-cell; width: 50%; vertical-align: top; padding-right: 16px; }
        .two-col > div:last-child { padding-right: 0; padding-left: 16px; }
    </style>
</head>
<body>

<!-- Page 1: Cover -->
<div class="page">
    <div class="cover">
        <div style="margin-bottom: 30px;">
            <svg viewBox="0 0 36 36" fill="none" width="70" height="70" style="display:inline-block;"><rect width="36" height="36" rx="8" fill="#0891b2"/><path d="M18 8v20M8 18h20" stroke="#fff" stroke-width="3" stroke-linecap="round"/></svg>
        </div>
        <div class="cover-title">CARNET DE SANTE NUMERIQUE</div>
        <div class="cover-subtitle">MediCare Pro - Systeme de Gestion Hospitaliere</div>

        <div class="cover-patient">
            <div class="cover-name">{{ $patient->prenom }} {{ $patient->nom }}</div>
            <div class="cover-info">Date de naissance : {{ $patient->date_naissance->format('d/m/Y') }} ({{ \Carbon\Carbon::parse($patient->date_naissance)->age }} ans)</div>
            <div class="cover-info">Sexe : {{ $patient->sexe == 'M' ? 'Masculin' : 'Feminin' }}</div>
            @if($patient->groupe_sanguin)
            <div class="cover-info">Groupe sanguin : {{ $patient->groupe_sanguin }}</div>
            @endif
            <div class="cover-info">Telephone : {{ $patient->telephone ?? 'Non renseigne' }}</div>
            <div class="cover-info">Adresse : {{ $patient->adresse ?? 'Non renseignee' }}</div>
            <div class="cover-info" style="margin-top:10px;color:#9ca3af;font-size:10px;">N Patient : #{{ str_pad($patient->id, 6, '0', STR_PAD_LEFT) }} | Inscrit le {{ $patient->date_inscription->format('d/m/Y') }}</div>
        </div>

        <div style="margin-top:80px;font-size:10px;color:#9ca3af;">
            Document genere le {{ now()->format('d/m/Y a H:i') }}
        </div>
    </div>
    <div class="footer">MediCare Pro | {{ $patient->prenom }} {{ $patient->nom }} | Genere le {{ now()->format('d/m/Y') }}</div>
</div>

<!-- Page 2: Medical info -->
<div class="page">
    <div class="section-title">Informations medicales</div>

    <div class="two-col">
        <div>
            <h3 style="font-size:12px;font-weight:700;margin-bottom:10px;color:#374151;">Allergies connues</h3>
            @if($patient->allergies && count(is_array($patient->allergies) ? $patient->allergies : []))
                @foreach((is_array($patient->allergies) ? $patient->allergies : explode(',', $patient->allergies)) as $allergie)
                <span class="tag tag-red">{{ trim($allergie) }}</span>
                @endforeach
            @else
                <span style="color:#9ca3af;">Aucune allergie connue</span>
            @endif

            @if($patient->dossierMedical)
            <h3 style="font-size:12px;font-weight:700;margin:20px 0 10px;color:#374151;">Maladies chroniques</h3>
            @if($patient->dossierMedical->maladies_chroniques && count($patient->dossierMedical->maladies_chroniques))
                @foreach($patient->dossierMedical->maladies_chroniques as $mc)
                <span class="tag tag-yellow">{{ $mc }}</span>
                @endforeach
            @else
                <span style="color:#9ca3af;">Aucune</span>
            @endif
            @endif
        </div>
        <div>
            @if($patient->dossierMedical)
            <h3 style="font-size:12px;font-weight:700;margin-bottom:10px;color:#374151;">Antecedents</h3>
            @if($patient->dossierMedical->antecedents && count($patient->dossierMedical->antecedents))
                @foreach($patient->dossierMedical->antecedents as $ant)
                <span class="tag tag-blue">{{ $ant }}</span>
                @endforeach
            @else
                <span style="color:#9ca3af;">Aucun</span>
            @endif

            <h3 style="font-size:12px;font-weight:700;margin:20px 0 10px;color:#374151;">Chirurgies</h3>
            @if($patient->dossierMedical->chirurgies && count($patient->dossierMedical->chirurgies))
                @foreach($patient->dossierMedical->chirurgies as $ch)
                <span class="tag tag-purple">{{ $ch }}</span>
                @endforeach
            @else
                <span style="color:#9ca3af;">Aucune</span>
            @endif

            @if($patient->dossierMedical->notes)
            <h3 style="font-size:12px;font-weight:700;margin:20px 0 10px;color:#374151;">Notes</h3>
            <p style="font-size:10.5px;color:#4b5563;">{{ $patient->dossierMedical->notes }}</p>
            @endif
            @endif
        </div>
    </div>
    <div class="footer">MediCare Pro | {{ $patient->prenom }} {{ $patient->nom }} | Genere le {{ now()->format('d/m/Y') }}</div>
</div>

<!-- Page 3: Vaccinations -->
<div class="page">
    <div class="section-title">Carnet de vaccinations</div>

    @if($patient->vaccinations->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Vaccin</th>
                <th>Maladie</th>
                <th>Date</th>
                <th>Dose</th>
                <th>Lot</th>
                <th>Site</th>
                <th>Prochain rappel</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patient->vaccinations as $v)
            <tr>
                <td style="font-weight:600;">{{ $v->vaccin }}</td>
                <td>{{ $v->maladie }}</td>
                <td>{{ $v->date_administration->format('d/m/Y') }}</td>
                <td>{{ $v->dose ?? '—' }}</td>
                <td>{{ $v->lot ?? '—' }}</td>
                <td>{{ $v->site_injection ?? '—' }}</td>
                <td>
                    @if($v->prochain_rappel)
                        @if($v->prochain_rappel->isPast())
                        <span class="overdue">{{ $v->prochain_rappel->format('d/m/Y') }} (EN RETARD)</span>
                        @else
                        {{ $v->prochain_rappel->format('d/m/Y') }}
                        @endif
                    @else — @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align:center;color:#9ca3af;padding:30px;">Aucune vaccination enregistree</p>
    @endif

    <div class="footer">MediCare Pro | {{ $patient->prenom }} {{ $patient->nom }} | Genere le {{ now()->format('d/m/Y') }}</div>
</div>

<!-- Page 4: Signes vitaux -->
<div class="page">
    <div class="section-title">Derniers signes vitaux</div>

    @if($patient->signesVitaux->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Temp.</th>
                <th>Tension</th>
                <th>Pouls</th>
                <th>Sat O2</th>
                <th>Poids</th>
                <th>Taille</th>
                <th>IMC</th>
                <th>Glycemie</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patient->signesVitaux as $sv)
            <tr>
                <td>{{ $sv->created_at->format('d/m/Y H:i') }}</td>
                <td>{!! $sv->temperature ? ($sv->temperature > 38 ? '<span class="overdue">' . $sv->temperature . ' C</span>' : $sv->temperature . ' C') : '—' !!}</td>
                <td>{!! ($sv->tension_systolique || $sv->tension_diastolique) ? (($sv->tension_systolique > 140 || $sv->tension_diastolique > 90) ? '<span class="overdue">' . ($sv->tension_systolique ?? '-') . '/' . ($sv->tension_diastolique ?? '-') . '</span>' : ($sv->tension_systolique ?? '-') . '/' . ($sv->tension_diastolique ?? '-')) : '—' !!}</td>
                <td>{!! $sv->pouls ? ($sv->pouls > 100 ? '<span class="overdue">' . $sv->pouls . '</span>' : $sv->pouls) : '—' !!}</td>
                <td>{!! $sv->saturation_o2 ? ($sv->saturation_o2 < 95 ? '<span class="overdue">' . $sv->saturation_o2 . '%</span>' : $sv->saturation_o2 . '%') : '—' !!}</td>
                <td>{{ $sv->poids ? $sv->poids . ' kg' : '—' }}</td>
                <td>{{ $sv->taille ? $sv->taille . ' cm' : '—' }}</td>
                <td>{{ $sv->imc ?? '—' }}</td>
                <td>{{ $sv->glycemie ? $sv->glycemie . ' g/L' : '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align:center;color:#9ca3af;padding:30px;">Aucun signe vital enregistre</p>
    @endif

    <div class="footer">MediCare Pro | {{ $patient->prenom }} {{ $patient->nom }} | Genere le {{ now()->format('d/m/Y') }}</div>
</div>

<!-- Page 5: Consultations -->
<div class="page">
    <div class="section-title">Historique des consultations</div>

    @if($patient->consultations->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Medecin</th>
                <th>Motif</th>
                <th>Diagnostic</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patient->consultations as $c)
            <tr>
                <td>{{ $c->date->format('d/m/Y') }}</td>
                <td>Dr. {{ $c->medecin->prenom ?? '' }} {{ $c->medecin->nom ?? '' }}</td>
                <td>{{ \Illuminate\Support\Str::limit($c->motif, 60) }}</td>
                <td>{{ \Illuminate\Support\Str::limit($c->diagnostic ?? '—', 60) }}</td>
                <td>
                    @if($c->statut == 'termine')
                    <span class="badge badge-success">Termine</span>
                    @elseif($c->statut == 'en_cours')
                    <span class="badge badge-info">En cours</span>
                    @else
                    <span class="badge badge-warning">En attente</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align:center;color:#9ca3af;padding:30px;">Aucune consultation</p>
    @endif

    <div class="footer">MediCare Pro | {{ $patient->prenom }} {{ $patient->nom }} | Genere le {{ now()->format('d/m/Y') }}</div>
</div>

<!-- Page 6: Ordonnances -->
<div class="page">
    <div class="section-title">Ordonnances</div>

    @if($patient->ordonnances->count() > 0)
        @foreach($patient->ordonnances as $ord)
        <div style="border:1px solid #e5e7eb;border-radius:8px;padding:12px;margin-bottom:14px;">
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                <strong>Ordonnance #{{ $ord->id }}</strong>
                <span style="color:#6b7280;font-size:10px;">{{ $ord->created_at->format('d/m/Y') }} | Dr. {{ $ord->medecin->prenom ?? '' }} {{ $ord->medecin->nom ?? '' }}</span>
            </div>
            @if($ord->medicaments && $ord->medicaments->count() > 0)
            <table style="margin-bottom:0;">
                <thead><tr><th>Medicament</th><th>Posologie</th><th>Duree</th><th>Quantite</th></tr></thead>
                <tbody>
                    @foreach($ord->medicaments as $med)
                    <tr>
                        <td style="font-weight:500;">{{ $med->nom ?? ($med->medicament->nom ?? '—') }}</td>
                        <td>{{ $med->posologie ?? '—' }}</td>
                        <td>{{ $med->duree ?? '—' }}</td>
                        <td>{{ $med->quantite ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
            @if($ord->recommandations)
            <div style="margin-top:6px;font-size:10px;color:#4b5563;background:#f9fafb;padding:6px 10px;border-radius:4px;">
                <strong>Recommandations :</strong> {{ $ord->recommandations }}
            </div>
            @endif
        </div>
        @endforeach
    @else
    <p style="text-align:center;color:#9ca3af;padding:30px;">Aucune ordonnance</p>
    @endif

    <div class="footer">MediCare Pro | {{ $patient->prenom }} {{ $patient->nom }} | Genere le {{ now()->format('d/m/Y') }}</div>
</div>

</body>
</html>
